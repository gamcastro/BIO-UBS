<?php
/**
 * auth.php
 * Recebe o POST do login.php, valida CSRF, consulta o PROFISSIONAL via PDO,
 * aplica proteção contra brute-force e cria sessão segura.
 * Implementa "Lembrar-me" com token persistente.
 */
// Inicia a sessão ANTES de qualquer output
session_start(); 

// Carrega o autoloader do Composer (que carrega Conexao, helpers, etc.)
require_once __DIR__ . '/../vendor/autoload.php'; 

// Importa a classe Conexao do namespace BioUBS
use BioUBS\Conexao; 

// Assumindo que set_secure_headers() e verify_csrf() estão em um helper carregado pelo Composer (ex: functions.php)
set_secure_headers(); 

//------------------- Apenas POST é permitido -------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    // Usar die() ou exit() é mais seguro que JavaScript para parar execução
    die('Método inválido.'); 
    // Ou redirecionar com mensagem de erro na sessão, se preferir
    // $_SESSION['login_error'] = 'Método inválido.';
    // header('Location: login.php');
    // exit;
}

//---------------------- Verifica CSRF ----------------------
// Assumindo que verify_csrf() está em um helper carregado pelo Composer
if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    die('Requisição inválida (CSRF).');
    // Ou redirecionar com mensagem de erro
    // $_SESSION['login_error'] = 'Requisição inválida (CSRF).';
    // header('Location: login.php');
    // exit;
}

// Obtém a conexão PDO através da classe Conexao
$db = Conexao::getConn(); 
// Assumindo que get_ip() está em um helper
$ip = get_ip(); 

//------------------- Bloqueio por muitas tentativas -------------------
// Assumindo que is_locked(), clear_attempts(), increment_attempt() estão em helpers
if (is_locked($db, $ip)) {
    die('Muitas tentativas. Tente novamente em alguns minutos.');
    // Ou redirecionar com mensagem de erro
    // $_SESSION['login_error'] = 'Muitas tentativas. Tente novamente em alguns minutos.';
    // header('Location: login.php');
    // exit;
}

//------------------- Sanitiza e valida credenciais -------------------
// Mudamos 'username' para 'matricula' para clareza
$matricula = trim($_POST['matricula'] ?? ''); // Recebe a matrícula do formulário
$pass = trim($_POST['password']);
$remember = isset($_POST['remember']);

// Validação básica (ajuste os comprimentos mínimos se necessário)
if (strlen($matricula) < 1 || strlen($pass) < 6) { // Matrícula não pode ser vazia
    // Incrementa tentativa ANTES de sair
    increment_attempt($db, $ip); 
    die('Matrícula ou senha inválidos.');
    // Ou redirecionar com mensagem de erro
    // $_SESSION['login_error'] = 'Matrícula ou senha inválidos.';
    // header('Location: login.php');
    // exit;
}

//------------------- Consulta segura na tabela cadastro_profissional -------------------
try {
    // *** ALTERAÇÃO PRINCIPAL AQUI ***
    $sql = "SELECT ID, MATRICULA, PASSWORD_HASH, NOME_COMPLETO, PERFIL 
            FROM cadastro_profissional 
            WHERE MATRICULA = :matricula AND IS_ACTIVE = 1 
            LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':matricula' => $matricula]); // Usa a variável $matricula
    $profissional = $stmt->fetch(PDO::FETCH_ASSOC); // Usar nome de variável mais descritivo

} catch (PDOException $e) {
    // Em produção, logar o erro em vez de exibi-lo
    error_log("Erro no login: " . $e->getMessage());
    die('Erro ao consultar o banco de dados.');
    // Ou redirecionar com mensagem genérica
    // $_SESSION['login_error'] = 'Erro no servidor. Tente novamente.';
    // header('Location: login.php');
    // exit;
}
// var_dump("Senha digitada (limpa):", $pass);
// var_dump("Profissional encontrado (deve ser array):", $profissional);
// var_dump("Hash do Banco:", $profissional['password_hash'] ?? 'NAO ENCONTRADO COMO ARRAY');
// var_dump("Hash do Banco (modo Objeto):", $profissional->password_hash ?? 'NAO ENCONTRADO COMO OBJETO');
// exit; // Para a execução aqui
$correctPassword = password_verify(password:$pass, hash:$profissional['PASSWORD_HASH'] ?? ''); // Verifica se a senha bate;
//------------------- Valida senha com password_verify -------------------
// Verifica se $profissional foi encontrado E se a senha digitada confere com o hash salvo
if ($correctPassword) {
    
    // Sucesso no Login!
    clear_attempts($db, $ip);           // Limpa contador de tentativas deste IP
    session_regenerate_id(true);        // Protege contra session fixation

    // Armazena dados essenciais na sessão
    $_SESSION['user_id'] = $profissional['ID'];         // Guarda o ID do profissional
    $_SESSION['username'] = $profissional['MATRICULA']; // Guarda a matrícula como username
    $_SESSION['user_nome'] = $profissional['NOME_COMPLETO']; // Guarda o nome (útil para exibir)
    $_SESSION['user_perfil'] = $profissional['PERFIL'];   // Guarda o perfil (útil para controle de acesso)

    // Lógica do "Lembrar-me" (se selecionado)
    if ($remember) {
        // Assumindo que create_remember_token() está em um helper e adaptado
        // para usar a tabela remember_tokens que referencia cadastro_profissional(ID)
        create_remember_token($db, (int)$profissional['ID']);
    }

    // Redireciona para a página principal do sistema
    header("Location: ../index.php");
    exit;

} else {
    // Falha no Login (matrícula não encontrada OU senha incorreta)
    increment_attempt($db, $ip); // Incrementa tentativas

    // Mensagem de erro genérica para não dar pistas a atacantes
    // Idealmente, redirecionar de volta para login.php com uma mensagem de erro na sessão
    $_SESSION['login_error'] = 'Matrícula ou senha incorretos.';
    header('Location: ../login.php');
    exit;
}
?>