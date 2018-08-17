<?php
function sendMail($mailto,$mailSub,$mailMsg,$Username,$pwd){
   

   $mail = new PHPMailer();
   $mail ->IsSmtp();
   $mail ->SMTPDebug = 0;
   $mail ->SMTPAuth = true;
   $mail ->SMTPSecure = 'ssl';
   $mail ->Host = "smtp.gmail.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username = $Username;
   $mail ->Password = $pwd;
   $mail ->SetFrom($Username);
   $mail ->Subject = $mailSub;
   $mail ->Body = $mailMsg;
   $mail ->AddAddress($mailto);

    $mail->Send();
}




   

