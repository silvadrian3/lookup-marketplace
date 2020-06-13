<?php
// using SendGrid's PHP Library
// https://github.com/sendgrid/sendgrid-php
//require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
require("sendgrid-php.php"); 
// If not using Composer, uncomment the above line

$apiKey = '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';

$from = new \SendGrid\Email($from_name, $from_email);
$to = new \SendGrid\Email($to_name, $to_email);
$content = new \SendGrid\Content($content_type, $content_body);

$mail = new \SendGrid\Mail($from, $subject, $to, $content);

$bcc1 = new  \SendGrid\Email($bcc1_name, $bcc1_email);
$mail->personalization[0]->addBcc($bcc1);

/**
$to1 = new  \SendGrid\Email(null, "adrianquijanosilva@gmail.com");
$mail->personalization[0]->addTo($to1);
$cc1 = new  \SendGrid\Email(null, "adrianquijanosilva@gmail.com");
$mail->personalization[0]->addCc($cc1);
*/

$sg = new \SendGrid($apiKey);

try {
    $response = $sg->client->mail()->send()->post($mail);
    //print $response->statusCode() . "\n";
    //print_r($response->headers());
    //print $response->body() . "\n";
    $result_msg = $success_msg;
    
} catch (Exception $e) {
    $result_msg = "Unexpected error encountered. " . $e->getMessage();
    //echo "<script>alert('".$result."');</script>";
}

//echo "<script>alert('".$result."');</script>";
?>