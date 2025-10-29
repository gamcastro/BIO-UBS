<?php
/**
 * reset_password_form.php
 * Página aberta via link do e-mail (contém ?token=...)
 * Confere validade do token e exibe formulário para definir nova senha.
 */
session_start();require_once __DIR__ . '/class/Conexao.php';
require_once __DIR__ . '/includes/functions.php';

$token = $_GET['token'] ?? '';
if (empty($token)){
  echo "<script>";
  echo "window.alert('Token inválido.');";
  echo "window.location='login.php'";
  echo "</script>";
  die;
} //exit('Token inválido.');

// $db = db_conn();
$db = Conexao::getConn();
$hash = hash('sha256', $token);

//---------------------------------------Verifica se o token existe, não foi usado e não expirou
$stmt = $db->prepare("SELECT * FROM password_resets WHERE token_hash = :h AND used = 0 LIMIT 1");
$stmt->execute([':h' => $hash]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset || new DateTime() > new DateTime($reset['expires'])) {

  echo "<script>";
  echo "window.alert('Token expirado ou inválido.');";
  echo "window.location='login.php'";
  echo "</script>";
    //exit('Token expirado ou inválido.');
    die;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Nova Senha - BIO-UBS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="mx-auto bg-white p-4 rounded shadow" style="max-width:400px">
    <h3 class="text-center mb-4">Definir nova senha</h3>
    <form method="POST" action="reset_password_update.php">
      <!--------- Importante: envia o token novamente ------------>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="mb-3">
        <label for="password" class="form-label">Nova senha</label>
        <input type="password" class="form-control" name="password" required minlength="8">
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-success">Atualizar senha</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
