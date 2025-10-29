<?php
/**
 * reset_password_update.php
 * Recebe a nova senha, valida, atualiza o hash do usuário e marca o token como usado.
 */
session_start();
require_once __DIR__ . '/class/Conexao.php';
require_once __DIR__ . '/includes/functions.php';

$token = $_POST['token'] ?? '';
$pass  = $_POST['password'] ?? '';

if (strlen($pass) < 6) exit('Senha muito curta.');

// $db = db_conn();
$db = Conexao::getConn();
$hash = hash('sha256', $token);

//----------------------------Revalida token e expiração
$stmt = $db->prepare("SELECT * FROM password_resets WHERE token_hash = :h AND used = 0 LIMIT 1");
$stmt->execute([':h' => $hash]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || new DateTime() > new DateTime($row['expires'])) {
    // exit('Token expirado ou inválido.');
    echo "<script>";
    echo "window.alert('Token expirado ou inválido!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}

//-----------------------------Atualiza senha do usuário
$newHash = password_hash($pass, PASSWORD_DEFAULT);
$upd = $db->prepare("UPDATE usuarios SET password_hash = :p WHERE id = :id");
$upd->execute([':p' => $newHash, ':id' => $row['user_id']]);

//-----------------------------Marca token como usado
$db->prepare("UPDATE password_resets SET used = 1 WHERE id = :id")->execute([':id' => $row['id']]);

echo "<script>alert('Senha redefinida com sucesso!');window.location='login.php';</script>";
