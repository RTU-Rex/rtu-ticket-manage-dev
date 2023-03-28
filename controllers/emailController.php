<?php

  require("./includes/PHPMailer.php");
  require("./includes/SMTP.php");

  function validate($data){
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
   }

   if(isset($_POST['sendEmail'])){ 
      $mail = new PHPMailer\PHPMailer\PHPMailer();
      $mail->IsSMTP(); // enable SMTP
  
      $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
      $mail->SMTPAuth = true; // authentication enabled
      $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
      $mail->Host = "smtp.gmail.com";
      $mail->Port = 465; // or 587
      $mail->IsHTML(true);
      $mail->Username = "tonnorombaba@gmail.com";
      $mail->Password = "xlicptluhnduigrp";
      $mail->SetFrom("tonnorombaba@gmail.com");
      $mail->Subject = "Test";
      $mail->Body = "hello";
      $mail->AddAddress("tonnorombaba@gmail.com");
  
       if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
       } else {
          echo "Message has been sent";
       }
   }

   
?>