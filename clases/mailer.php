<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

class Mailer {
    public function enviarEmail($email, $asunto, $cuerpo) {
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
            
            // Remitente
            $mail->setFrom(MAIL_USER, 'Tienda Hidrosistemas');
            $mail->addAddress($email);
            
            // Contenido
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>