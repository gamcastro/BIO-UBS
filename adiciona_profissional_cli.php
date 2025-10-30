<?php
/**
 * add_profissional_cli.php
 * SCRIPT NÃO-INTERATIVO (Seeder)
 * Adiciona um profissional com valores pré-definidos (hardcoded) ao banco.
 * Garante que a senha é hasheada corretamente com Argon2id.
 *
 * Uso: Abra o terminal na raiz do projeto e execute:
 * php add_profissional_cli.php
 */

// Define um ambiente seguro para linha de comando
if (php_sapi_name() !== 'cli') {
    die("Este script só pode ser executado via linha de comando (CLI).\n");
}

// 1. Carrega o Autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

// 2. Usa as classes necessárias
use BioUBS\Conexao;
use BioUBS\UbsCrudAll;

echo "=========================================\n";
echo "  Adicionar Profissional (Hardcoded)\n";
echo "=========================================\n\n";

// ===================================================
// ----- DEFINA OS DADOS DO NOVO PROFISSIONAL AQUI -----
// ===================================================

$matricula     = "admin"; // O login/username
$nome_completo = "Administrador do Sistema";
$email         = "admin@bioubs.com";
$perfil        = "COORDENADOR"; // (ex: MEDICO, ENFERMEIRO, RECEPCAO, COORDENADOR)
$senha_plana   = "12345678"; // A senha em texto puro que será hasheada

// Outros campos obrigatórios (se houver). Se não forem, pode deixar null ou remover.
$data_nascimento = "1990-01-01";
$sexo            = "Masculino";

// ===================================================
// ------ O SCRIPT COMEÇA A PARTIR DAQUI ------
// ===================================================

echo "A preparar o profissional:\n";
echo "  Matrícula: $matricula\n";
echo "  Nome:      $nome_completo\n";
echo "  Email:     $email\n";
echo "  Perfil:    $perfil\n";
echo "  Senha:     [SENHA OCULTA]\n\n";

// 3. Gera o Hash da Senha com Argon2id
echo "A gerar hash Argon2id para a senha...\n";
// O trim() não é mais necessário, pois definimos a string diretamente.
$password_hash = password_hash($senha_plana, PASSWORD_ARGON2ID);

if ($password_hash === false) {
    die("Erro crítico: Falha ao gerar o hash da senha. Verifique a sua versão do PHP CLI.\n");
}

// 4. Prepara os dados para inserir no banco
$dadosParaSalvar = [
    'MATRICULA'       => $matricula,
    'NOME_COMPLETO'   => $nome_completo,
    'EMAIL'           => $email,
    'PASSWORD_HASH'   => $password_hash, // O hash seguro
    'PERFIL'          => $perfil,
    'DATA_NASCIMENTO' => $data_nascimento,
    'SEXO'            => $sexo,
    'IS_ACTIVE'       => 1 // Define como ativo por padrão
    
    // Adicione outros campos com valores padrão (null) se o seu banco exigir
    // 'cpf' => null, 
    // 'cns_profissional' => null,
];

// 5. Tenta inserir no banco de dados
try {
    echo "A conectar ao banco de dados...\n";
    $crud = new UbsCrudAll('cadastro_profissional'); 
    
    echo "A inserir profissional...\n";
    $novoId = $crud->inserir($dadosParaSalvar);

    if ($novoId) {
        echo "\n=========================================\n";
        echo " SUCESSO! Profissional criado com ID: " . $novoId . "\n";
        echo "=========================================\n";
    } else {
        echo "\nERRO: Falha ao inserir o profissional (verificar logs ou a classe UbsCrudAll).\n";
    }

} catch (PDOException $e) {
    echo "\nERRO DE BANCO DE DADOS:\n";
    // Verifica se é erro de chave duplicada (matrícula ou email)
    if ($e->getCode() == 23000) { // Código SQLSTATE para violação de integridade
         if (strpos($e->getMessage(), 'idx_matricula_unique') !== false) {
             echo "ERRO: A matrícula '$matricula' já existe no banco.\n";
         } elseif (strpos($e->getMessage(), 'EMAIL') !== false) { 
             echo "ERRO: O email '$email' já existe no banco.\n";
         } else {
            echo "Erro de chave duplicada: " . $e->getMessage() . "\n";
         }
    } else {
        echo "Não foi possível conectar ou inserir no banco: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "\nERRO GERAL: " . $e->getMessage() . "\n";
}

?>