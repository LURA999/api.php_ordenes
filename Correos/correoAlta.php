<?php
include "../Conn.php";
/*NOTA: TIENE QUE ESTAR DESACTIVADO EL AVASTANTIVIRUS PARA QUE ENVIE EL MENSAJE */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\POP3;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/POP3.php';

$titulo='';
$cuerpo='';


$params = json_decode(file_get_contents('php://input'),true);


    if($_GET['tipo'] == 1 ) {

	    if($_GET['insertar'] == 1){
            echo 'Baja Levantamiento ';
            $titulo= 'Fue dado de baja del Levantamiento: '.$params[0]['cve'];
            $cuerpo= 'Link de Levantamiento:<a href="http://ordenes.red-7.net:85/#/levantamientos/abcLevantamiento/'.$params[0]['cve'].'">Levantamiento</a></li>';

        }else{
            echo 'Alta Levantamiento ';
            $titulo= 'Alta Levantamiento: '.$params[0]['cve'];
            $cuerpo= 
            '<b>Datos de Levantamiento</b>'."\n".
            'Cliente:'.$params[0]['cliente']."\n".
            'Sucursal:'.$params[0]['sucursal']."\n".
            'Coordenadas:'.$params[0]['coordenadas']."\n".
            'ciudad:'.$params[0]['ciudad']."\n".
            'Servicio:'.$params[0]['servicio']."\n".
            'Megas:'.$params[0]['mb']."\n\n".
        //  'CorreoDestino: '.$_GET['correoinstalador']."\n"
            '<b>Datos de contacto:</b>'."\n".
            'Nombre:'.$params[0]['contacto']."\n".
            'Telefono:'.$params[0]['numero']."\n".
            'Correo:'.$params[0]['correo']."\n\n".
            'Link de Levantamiento:<a href="http://ordenes.red-7.net:85/#/levantamientos/abcLevantamiento/'.$params[0]['cve'].'">Levantamiento</a></li>';
        }

    } else if( $_GET['tipo'] == 2 ) {
        if($_GET['insertar'] == 2){
            echo 'Baja Orden ';
            $sd= 'sd: '.$params[0]['correo'];
            $titulo= 'Baja Orden: '.$params[0]['cve'];
            $cuerpo= 'Link de Orden:<a href="http://ordenes.red-7.net:85/#/ordenes/abcOrden/'.$params[0]['cve'].'">Orden</a></li>';

        }else{
            echo 'Alta Orden ';
            $sd= 'sd: '.$params[0]['correo'];
            $titulo= 'Alta Orden: '.$params[0]['cve'];
            $cuerpo= 
            'Datos de Orden'."\n".
            'Cliente: '.$params[0]['cliente']."\n".
            'Sucursal:'.$params[0]['sucursal']."\n".
            'Coordenadas:'.$params[0]['coordenadas']."\n".
            'ciudad:'.$params[0]['ciudad']."\n".
            'Servicio:'.$params[0]['servicio']."\n\n".
            '<b>Datos de contacto:</b>'."\n".
            'Nombre:'.$params[0]['contacto']."\n".
            'Telefono:'.$params[0]['numero']."\n\n".
            '<b>Datos de servicio:</b>'."\n".
            'Descripcion:'.$params[0]['desc_problema']."\n".
            'Fecha programada:'.$params[0]['fecha_programada']."\n".
            /* 'Instalador:'.$_GET['instalador']."\n".*/
            'Link de Orden:<a href="http://ordenes.red-7.net:85/#/ordenes/abcOrden/'.$params[0]['cve'].'">Orden</a></li>';

        }
    }
    
    $fecha= date('m-d-Y');
    $hora2 = time()-32400;
    $hora = date("H:i:s a",$hora2);
    $mail = new PHPMailer(true);
    try{                                         // Server settings
     $mail->SMTPDebug = 2;                      // Enable verbose debug output
     $mail->isSMTP();                                            // Send using SMTP
     $mail->Host       = 'mail.septimared.net';                    // Set the SMTP server to send through
     $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
     $mail->Username   = 'notificacion@septimared.net';                     // SMTP username
     $mail->Password   = '7R@246810';                               // SMTP password
     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
     $mail->SMTPSecure = 'tls';
     $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
     $mail->isHTML(true);
     //Recipients
     $mail->setFrom('notificacion@septimared.net', 'Notificacion RED7');
     
     if($params[0]['correo'] == "0"){
     $mail->addAddress('alonsolr1999@gmail.com');     // Add a recipient
     $mail->addAddress('a17490474@itmexicali.edu.mx'); 
     $mail->isHTML(true);                                  
     $mail->Subject = $titulo;
     $mail->Body    = nl2br('Registro de hoy '.$fecha.' a las '.$hora."\n".$cuerpo);
     $mail->send();
     echo 'El mensaje a sido enviado';

     }
     if($_GET['tipoinstalador'] == 1 && $params[0]['correo'] !== "0"){
     for($i =0; $i < count($params); ++$i ){
        $mail->addAddress($params[$i]["correo"]);             
        $mail->isHTML(true);                                  
        $mail->Subject = $titulo;
        $mail->Body    = nl2br('Registro de hoy '.$fecha.' a las '.$hora."\n".$cuerpo);
        $mail->send();
        echo 'El mensaje a sido enviado';
     }
    }
    }catch(Exception $e){
        echo 'Error de envio';
    }

?>