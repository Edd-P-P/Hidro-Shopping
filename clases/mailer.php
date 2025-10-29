<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* No olvidar copiar esto en la terminal cada que se clone el archivo despues de bajar los cambios "composer require phpmailer/phpmailer" (Bajar coomposer si no se tiene) */
/* Verificar que este habilitado mbstring  */
// Rutas robustas con __DIR__
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

class Mailer {
    public function enviarEmail($email, $asunto, $cuerpo) {
        // Incluir configuración con ruta robusta
        require_once __DIR__ . '/../config/config.php';
        
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = MAIL_PORT;
            $mail->CharSet = 'UTF-8'; // Asegurar codificación UTF-8
            
            // Remitente
            $mail->setFrom(MAIL_USER, 'Tienda Hidrosistemas');
            $mail->addAddress($email);
            $mail->addReplyTo(MAIL_USER, 'Soporte Hidrosistemas');
            
            // Contenido
            $mail->isHTML(true);
            $mail->Subject = convertir_utf8($asunto); // Usar función de conversión
            $mail->Body = convertir_utf8($cuerpo); // Usar función de conversión
            $mail->AltBody = strip_tags($cuerpo); // Versión texto plano
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo a {$email}: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>