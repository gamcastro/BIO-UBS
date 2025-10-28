<?php
/**
 * includes/mailer.php
 * Envio de e-mail com PHPMailer.
 * - Usa autoload do Composer em includes/vendor/autoload.php
 * - Configurado para Gmail com "senha de app"
 * - Presisamos ALTERAR  $MAIL_USER e $MAIL_PASS as nossa credenciais
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/vendor/autoload.php';

function send_reset_email(string $email, string $token): bool {
    //---------Em produção, troque para o nosso domínio HTTPS.
    $resetLink = "http://localhost/bio-ubs/reset_password_form.php?token=" . urlencode($token);

    // >>> CONFIGURAR AQUI <<<
    $MAIL_HOST = 'smtp.gmail.com';
    $MAIL_USER = 'bioubs2025@gmail.com'; //-------------Substitua pelo nosso email
    $MAIL_PASS = 'ppbeqhhitlmcigax';       //-------------Crie uma senha de app nas configurações do Google
    $MAIL_NAME = 'BIO-UBS';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = $MAIL_USER;
        $mail->Password   = $MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //----- corrigindo caracteres especiais no bio-ubs
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom($MAIL_USER, $MAIL_NAME);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de senha - BIO-UBS';
        $mail->Body    = "
            <h3>Redefinição de senha</h3>
            <p>Você solicitou a redefinição da sua senha. Clique no link abaixo:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>O link expira em 30 minutos.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
        return false;
    }
}
