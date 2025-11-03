<?php
/**
 * reset_password_send.php
 * Valida o e-mail informado, verifica o token CSRF, gera o token de redefinição
 * e envia o link de redefinição por e-mail (PHPMailer).
 */

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use BioUBS\Conexao ;
//-------------------------------------------------------
//----------------------------- 1️ Validação do token CSRF
//-------------------------------------------------------
if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    // exit('CSRF inválido.');
    echo "<script>";
    echo "window.alert('CSRF inválido!');";
    echo "window.location='../login.php'";
    echo "</script>";
    //exit('Token expirado ou inválido.');
    exit;
}

//-------------------------------------------------------
//--------------------------------- 2 Validação do e-mail
//-------------------------------------------------------
$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // exit('E-mail inválido.');
    echo "<script>";
    echo "window.alert('E-mail inválido!');";
    echo "window.location='../login.php'";
    echo "</script>";
    exit;
}

//-------------------------------------------------------
//-----------------3 Verifica se o e-mail existe no banco
//-------------------------------------------------------
// $db = db_conn();
$db = Conexao::getConn();
$stmt = $db->prepare("SELECT id FROM cadastro_profissional WHERE EMAIL = :e LIMIT 1");
$stmt->execute([':e' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//------ Não revela se o e-mail existe ou não (protege privacidade)
if (!$user) {
    echo "<script>";
    echo "window.alert('Se o e-mail estiver cadastrado, enviaremos um link!');";
    echo "window.location='../login.php'";
    echo "</script>";
    exit;
}

//-------------------------------------------------------
//------------------ 4 Gera token de redefinição de senha
//-------------------------------------------------------
$token   = bin2hex(random_bytes(32));              //--------token único (64 chars)
$hash    = hash('sha256', $token);                 //--------hash que vai pro banco
$expires = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

//--------------------------------Apaga tokens antigos do mesmo usuário (opcional, boa prática)
$db->prepare("DELETE FROM password_resets WHERE user_id = :u")->execute([':u' => $user['id']]);

//--------------------------------------------Grava novo token
$stmt = $db->prepare("
    INSERT INTO password_resets (user_id, token_hash, expires)
    VALUES (:u, :t, :e)
");
$stmt->execute([':u' => $user['id'], ':t' => $hash, ':e' => $expires]);

//-------------------------------------------------------
//------------ 5️ Envia o e-mail com o link de redefinição
//-------------------------------------------------------
if (send_reset_email($email, $token)) {
    // echo "<script>alert('Se o e-mail estiver cadastrado, enviaremos um link.');window.location='login.php';</script>";
    echo "<script>";
    echo "window.alert('Se o e-mail estiver cadastrado, enviaremos um link!');";
    echo "window.location='../login.php'";
    echo "</script>";
    exit;
} else {
    //--------------------Caso o envio falhe, exibe mensagem genérica (sem expor erro técnico)
    // echo "<script>alert('Falha ao enviar e-mail. Tente novamente.');history.back();</script>";
    echo "<script>";
    echo "window.alert('Falha ao enviar e-mail. Tente novamente!');";
    echo "window.location='../login.php'";
    echo "</script>";
    exit;
    
}
