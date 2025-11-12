<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = "innoxent.tariq2016@gmail.com";
    $subject = "New Book Submission from " . $_POST['fullName'];

    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $country = htmlspecialchars($_POST['country']);
    $genre = htmlspecialchars($_POST['genre']);
    $bookTitle = htmlspecialchars($_POST['bookTitle']);
    $bookDetails = htmlspecialchars($_POST['bookDetails']);

    // Email body
    $message = "
    <h2>New Book Submission</h2>
    <p><strong>Full Name:</strong> $fullName</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Country:</strong> $country</p>
    <p><strong>Genre:</strong> $genre</p>
    <p><strong>Book Title:</strong> $bookTitle</p>
    <p><strong>Book Details:</strong><br>$bookDetails</p>
    ";

    // Headers
    $boundary = md5(time());
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: $fullName <$email>\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    // Multipart message
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n";

    // Attach files
    if(!empty($_FILES['bookFiles']['name'][0])) {
        for($i=0; $i<count($_FILES['bookFiles']['name']); $i++){
            $fileName = $_FILES['bookFiles']['name'][$i];
            $fileTmp = $_FILES['bookFiles']['tmp_name'][$i];
            $fileData = chunk_split(base64_encode(file_get_contents($fileTmp)));
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $fileData . "\r\n";
        }
    }

    $body .= "--$boundary--";

    // Send email
    if(mail($to, $subject, $body, $headers)){
        echo "Your book submission has been sent successfully!";
    } else {
        echo "Failed to send your submission. Please try again.";
    }
}
?>
