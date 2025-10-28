<?php
/**
 * auth.php
 * Recebe o POST do login.php, valida CSRF, consulta o usuário via PDO,
 * aplica proteção contra brute-force e cria sessão segura.
 * Também implementa o "Lembrar-me" com token persistente.
 */
session_start();
require_once('../../ConexaoUbs.php');
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/auth_helper.php';

set_secure_headers(); // cabeçalhos de segurança básicos

//-------------------Apenas POST é permitido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    // exit('Método inválido');
    echo "<script>";
    echo "window.alert('Método inválido!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}

//--------------fazendo testes pra encontrar falhas
// var_dump($_POST['csrf_token']);
// var_dump($_SESSION['csrf_token']);
// exit;

// echo "COOKIE:\n"; var_dump($_COOKIE);
// echo "POST token:\n"; var_dump($_POST['csrf_token'] ?? null);
// echo "SESSION token:\n"; var_dump($_SESSION['csrf_token'] ?? null);
// echo "SESSION token time:\n"; var_dump($_SESSION['csrf_token_time'] ?? null);
// echo "server time: " . date('Y-m-d H:i:s') . "\n";
// exit;

//----------------------Verifica CSRF
if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    // exit('Requisição inválida (CSRF).');
    echo "<script>";
    echo "window.alert('Requisição inválida (CSRF)!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}

// $db = db_conn();
$db = Conexao::getConn();
$ip = get_ip();

//---------------------------Bloqueio por muitas tentativas (5 em 15 minutos, por padrão)
if (is_locked($db, $ip)) {
    // exit('Muitas tentativas. Tente novamente em alguns minutos.');
    echo "<script>";
    echo "window.alert('Muitas tentativas. Tente novamente em alguns minutos!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}

//-------------------------Sanitiza e valida credenciais
$user = trim($_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if (strlen($user) < 3 || strlen($pass) < 6) {
    // exit('Usuário ou senha inválidos.');
    echo "<script>";
    echo "window.alert('Usuário ou senha inválidos!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}

//---------------------Consulta segura via PDO (evita SQL Injection)
$stmt = $db->prepare("SELECT id, username, password_hash FROM usuarios WHERE username = :u AND is_active = 1 LIMIT 1");
$stmt->execute([':u' => $user]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

//--------------------Valida senha com password_verify (hash seguro)
if ($dados && password_verify($pass, $dados['password_hash'])) {
    clear_attempts($db, $ip);               //---------limpa contador de tentativas deste IP
    session_regenerate_id(true);            //---------protege contra fixation
    $_SESSION['user_id'] = $dados['id'];    //---------armazena ID do usuário autenticado
    $_SESSION['username'] = $dados['username'];

    if ($remember) {
        //--------------------------Cria cookie remember-me com selector/validator (armazenado hash do validator)
        create_remember_token($db, (int)$dados['id']);
    }

    //---------------------------------Redireciona para área protegida
    header("Location: dashboard.php");
    exit;
} else {
    //--------------------------------Incrementa tentativas e devolve mensagem neutra
    increment_attempt($db, $ip);
    // echo "<script>alert('Usuário ou senha incorretos.');history.back();</script>";
    echo "<script>";
    echo "window.alert('Usuário ou senha incorretos!');";
    echo "window.location='login.php'";
    echo "</script>";
    exit;
}
