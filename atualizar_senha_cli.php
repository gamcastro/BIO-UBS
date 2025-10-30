<?php
/**
 * atualizar_senha_cli.php
 * Script de linha de comando para ATUALIZAR FORÇADAMENTE a senha de um profissional.
 * Usa trim() e Argon2id para garantir que o hash está correto.
 *
 * Uso: php atualizar_senha_cli.php
 */

// Apenas CLI
if (php_sapi_name() !== 'cli') {
    die("Este script só pode ser executado via linha de comando (CLI).\n");
}

// 1. Carrega o Autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

// 2. Usa as classes necessárias
use BioUBS\Conexao;

echo "=========================================\n";
echo "  Atualizar Senha de Profissional (CLI)\n";
echo "=========================================\n\n";

// --- Função auxiliar para pedir input ---
function prompt(string $message): string {
    echo $message . ": ";
    $input = readline(); 
    while (trim($input) === '') {
        echo "Este campo não pode ser vazio. Tente novamente.\n";
        echo $message . ": ";
        $input = readline();
    }
    return trim($input);
}

// --- Coleta os dados ---
$matricula = prompt("Digite a MATRÍCULA do profissional a ser atualizado");
$nova_senha = '';
$confirmar_senha = '';

// --- Coleta e confirma a nova senha (JÁ COM TRIM) ---
while (true) {
    echo "Digite a NOVA Senha (mínimo 6 caracteres): ";
    system('stty -echo'); 
    $nova_senha = trim(readline()); // Aplica trim()
    system('stty echo'); 
    echo "\n"; 

    if (strlen($nova_senha) < 6) {
        echo "Erro: A senha deve ter no mínimo 6 caracteres.\n";
        continue; 
    }

    echo "Confirme a NOVA Senha: ";
    system('stty -echo');
    $confirmar_senha = trim(readline()); // Aplica trim()
    system('stty echo');
    echo "\n";

    if ($nova_senha === $confirmar_senha) {
        break; // Sai do loop
    } else {
        echo "Erro: As senhas não coincidem. Tente novamente.\n";
    }
}

// 3. Gera o Hash da Senha (Corretamente)
echo "A gerar novo hash Argon2id para a senha...\n";
$password_hash = password_hash($nova_senha, PASSWORD_ARGON2ID);

if ($password_hash === false) {
    die("Erro crítico: Falha ao gerar o hash da senha.\n");
}

// 4. Tenta atualizar no banco de dados
try {
    echo "A conectar ao banco de dados...\n";
    $db = Conexao::getConn();
    
    $sql = "UPDATE cadastro_profissional SET password_hash = :hash WHERE matricula = :matricula";
    $stmt = $db->prepare($sql);
    
    $stmt->execute([
        ':hash' => $password_hash,
        ':matricula' => $matricula
    ]);

    // rowCount() diz quantas linhas foram alteradas
    $linhasAfetadas = $stmt->rowCount();

    if ($linhasAfetadas > 0) {
        echo "\n=========================================\n";
        echo " SUCESSO! Senha do profissional com matrícula '$matricula' foi atualizada.\n";
        echo "=========================================\n";
    } else {
        echo "\nAVISO: Nenhuma linha foi atualizada. A matrícula '$matricula' foi encontrada?\n";
    }

} catch (PDOException $e) {
    echo "\nERRO DE BANCO DE DADOS: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "\nERRO GERAL: " . $e->getMessage() . "\n";
}

?>