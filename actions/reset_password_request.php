<?php
/**
 * reset_password_request.php
 * Formulário para o usuário informar seu e-mail cadastrado.
 * Gera um pedido de redefinição de senha enviando para reset_password_send.php
 */
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
$csrf = generate_csrf();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Redefinir Senha - BIO-UBS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="mx-auto bg-white p-4 rounded shadow" style="max-width:400px">
    <h3 class="text-center mb-4">Esqueceu a senha?</h3>
    <form method="POST" action="reset_password_send.php">
      <!-------- Proteção CSRF ---------->
      <input type="hiddenn" name="csrf_token" value="<?= $csrf ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Digite seu e-mail cadastrado</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Enviar link de redefinição</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
