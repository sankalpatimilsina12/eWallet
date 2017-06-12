<?php

  function sendMail($from, $to, $subject, $message, $replyTo) {

    require_once("../resources/phpmailer/PHPMailerAutoload.php");

    $mail = new PHPMailer;

    $mail->SMTPDebug = 1;                                 // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'ssl://smtp.gmail.com';                 // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'sankalpatimilsina12@gmail.com';    // SMTP username
    $mail->Password = '';                                 // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect ti

    $mail->setFrom($from, '');
    $mail->addAddress($to, '');                           // Add a recipient
    $mail->addReplyTo($replyTo, '');
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $message;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } 

  }