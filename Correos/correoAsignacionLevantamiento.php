<?php
include "../Conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\POP3;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/POP3.php';

$fecha= date('m-d-Y');
$hora2 = time()-32400;
$hora = date("H:i:s a",$hora2);

$mail = new PHPMailer(true);

try{
 //Server settings
 $mail->SMTPDebug = 2;                      // Enable verbose debug output
 $mail->isSMTP();                                            // Send using SMTP
 $mail->Host       = 'mail.septimared.net';                    // Set the SMTP server to send through
 $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
 $mail->Username   = 'notificacion@septimared.net';                     // SMTP username
 $mail->Password   = '7R@246810';                               // SMTP password
 $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
 $mail->SMTPSecure = 'tls';
 $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

 //Recipients
// $mail->setFrom('notificacion@septimared.net', 'Notificacion RED7');
// $mail->addAddress('alonsolr1999@gmail.com');     // Add a recipient
 //$mail->addAddress('a20490238@itmexicali.edu.mx');
//  $mail->addAddress('ellen@example.com');               // Name is optional
//  $mail->addReplyTo('info@example.com', 'Information');
//  $mail->addCC('cc@example.com');
//  $mail->addBCC('bcc@example.com');

 // Attachments
//  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

 // Content
 $mail->isHTML(true);                                  // Set email format to HTML
 $mail->Subject = 'Se ha creado un levantamiento';
 $mail->Body    = 'Actualmente hoy '.$fecha.' a las '.$hora;
 $mail->send();

 echo 'El mensaje a sido enviado';

}catch(Exception $e){
    echo 'Error de envio';
}


?>