<?php
require ('PHPMailer/PHPMailerAutoload.php');
class Mail
{
        public static function sendMail($subject, $body, $address)
        {
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = '465';
                $mail->isHTML();
                $mail->Username = 'connect.socialmail@gmail.com';
                $mail->Password = '368276b2fb0439a3';
                $mail->SetFrom('no-reply@neighbor.org');
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->AddAddress($address);

                if (!$mail->send()) {
                        $mail = "Message could not be sent.";
                } else {
                        $mail = "Message sent";
                }

                return $mail;
        }
}
