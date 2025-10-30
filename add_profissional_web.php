<?php
/**
 * add_profissional_web.php
 * Ferramenta temporária de administração para criar um profissional (ex: admin)
 * diretamente pelo ambiente web (XAMPP), garantindo que o hash da senha
 * seja criado pelo mesmo PHP que irá verificá-lo.
 */

// Carrega o autoloader do Composer (MUITO IMPORTANTE)
require_once __DIR__ . '/vendor/autoload.php';

// Usa as classes que vamos precisar
use BioUBS\Conexao;
use BioUBS\UbsCrudAll;

// Variáveis para feedback ao utilizador
$errorMessage = null;
$successMessage = null;

// Verifica se o formulário foi enviado (se o método é POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 1. Coleta e Validação dos Dados ---
    $matricula = trim($_POST['matricula'] ?? '');
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $perfil = $_POST['perfil'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $senha = $_POST['senha'] ?? ''; // Usamos 'senha' e 'confirmar_senha'
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validação de campos obrigatórios
    if (empty($matricula) || empty($nome_completo) || empty($email) || empty($perfil) || empty($senha)) {
        $errorMessage = "Erro: Todos os campos com * são obrigatórios.";
    }
    // Validação da senha
    elseif (strlen($senha) < 6) {
        $errorMessage = "Erro: A senha deve ter no mínimo 6 caracteres.";
    }
    // Validação da confirmação
    elseif ($senha !== $confirmar_senha) {
        $errorMessage = "Erro: As senhas não conferem. Tente novamente.";
    }
    
    // Se não houver erros de validação, prossegue para o banco
    if ($errorMessage === null) {
        
        // --- 2. Geração do Hash (usando o PHP do XAMPP) ---
        $password_hash = password_hash($senha, PASSWORD_ARGON2ID);
        
        if ($password_hash === false) {
            // Este erro é sério, indica problema no PHP do XAMPP
            $errorMessage = "Erro Crítico: Falha ao gerar o hash da senha. O PHP do XAMPP suporta Argon2id?";
        } else {
            
            // --- 3. Preparação dos Dados para o Banco ---
            $dadosParaSalvar = [
                'matricula'       => $matricula,
                'nome_completo'   => $nome_completo,
                'email'           => $email,
                'password_hash'   => $password_hash,
                'perfil'          => $perfil,
                'data_nascimento' => $data_nascimento,
                'sexo'            => $sexo,
                'is_active'       => 1 // Ativa o utilizador por padrão
                // Adicione outros campos com null se o banco exigir
            ];

            // --- 4. Inserção no Banco de Dados ---
            try {
                $crud = new UbsCrudAll('cadastro_profissional');
                $novoId = $crud->inserir($dadosParaSalvar);
                
                $successMessage = "SUCESSO! Profissional '{$nome_completo}' (Matrícula: {$matricula}) foi criado com ID: {$novoId}. Tente fazer login agora.";
            
            } catch (PDOException $e) {
                // Trata erros de Matrícula/Email duplicado
                if ($e->getCode() == 23000) {
                     if (strpos($e->getMessage(), 'idx_matricula_unique') !== false) {
                         $errorMessage = "Erro: A matrícula '$matricula' já existe.";
                     } elseif (strpos($e->getMessage(), 'EMAIL') !== false) { 
                         $errorMessage = "Erro: O email '$email' já existe.";
                     } else {
                        $errorMessage = "Erro de chave duplicada: " . $e->getMessage();
                     }
                } else {
                    $errorMessage = "Erro de Banco de Dados: " . $e->getMessage();
                }
            } catch (Exception $e) {
                $errorMessage = "Erro Geral: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferramenta: Adicionar Profissional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Ferramenta: Adicionar Profissional (via Web)</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Use este formulário para criar um novo profissional que possa fazer login. O hash da senha será gerado pelo ambiente do servidor web (XAMPP).</p>
                        
                        <?php // Bloco para exibir mensagem de ERRO
                        if ($errorMessage): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= htmlspecialchars($errorMessage); ?>
                            </div>
                        <?php endif; ?>

                        <?php // Bloco para exibir mensagem de SUCESSO
                        if ($successMessage): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <?= htmlspecialchars($successMessage); ?>
                                <hr>
                                <a href="login.php" class="btn btn-primary">Ir para a página de Login</a>
                            </div>
                        <?php endif; ?>

                        <?php // Esconde o formulário se o utilizador foi criado com sucesso
                        if (!$successMessage): ?>
                        
                        <form action="add_profissional_web.php" method="POST">
                            
                            <h6 class="text-primary mt-4">Acesso e Perfil</h6>
                            <hr>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="matricula" class="form-label">Matrícula (Login) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="matricula" name="matricula" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="perfil" class="form-label">Perfil <span class="text-danger">*</span></label>
                                    <select id="perfil" name="perfil" class="form-select" required>
                                        <option value="" selected>Selecione...</option>
                                        <option value="COORDENADOR">COORDENADOR</option>
                                        <option value="RECEPCAO">RECEPÇÃO</option>
                                        <option value="MEDICO">MÉDICO</option>
                                        <option value="ENFERMEIRO">ENFERMEIRO</option>
                                        </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmar_senha" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                                </div>
                            </div>
                            
                            <h6 class="text-primary mt-4">Dados Pessoais</h6>
                            <hr>
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label for="nome_completo" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                                </div>
                                 <div class="col-md-4">
                                    <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                    <select id="sexo" name="sexo" class="form-select" required>
                                        <option value="" selected>Selecione...</option>
                                        <option value="Feminino">Feminino</a-label>
                                        <option value="Masculino">Masculino</a-label>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">Criar Profissional</button>
                            </div>

                        </form>
                        <?php endif; // Fim do if(!$successMessage) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>