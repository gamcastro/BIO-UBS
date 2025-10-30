<?php
/**
 * Tela de Login - BIO-UBS
 * - Exibe mensagem de erro da sessão (se houver).
 * - Adiciona proteção CSRF (token oculto).
 * - Form aponta para actions/auth.php.
 */
session_start(); // ESSENCIAL: Deve ser a primeira linha para aceder $_SESSION

// Verifica se há uma mensagem de erro vinda do auth.php
$login_error_message = $_SESSION['login_error'] ?? null;
// Pega a matrícula tentada (se houver) para repopular o campo
$attempted_matricula = $_SESSION['login_attempt_matricula'] ?? '';

// Limpa as variáveis da sessão IMEDIATAMENTE APÓS LÊ-LAS
 unset($_SESSION['login_error']);
unset($_SESSION['login_attempt_matricula']);

require_once __DIR__ . '/vendor/autoload.php'; // Autoloader

// Assumindo que generate_csrf() está num helper carregado pelo autoload
$csrf_token = generate_csrf(); 

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BIO-UBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Seus estilos CSS customizados aqui... */
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-primary: #0d6efd;
            --bs-primary-rgb: 13, 110, 253;
        }
        body { background-color: #f8f9fa; }
        .branding-column { background-size: cover; background-position: center; position: relative; }
        .branding-column::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom right, #0d6efd, #004297); z-index: 1;
        }
        .branding-content { position: relative; z-index: 2; }
        .form-control:focus, .form-check-input:focus {
            border-color: #80bdff; box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
        }
    </style>
</head>
<body>
    <main class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center text-white text-center p-5 branding-column">
                <div class="branding-content">
                    <h1 class="display-3 fw-bold mb-4">
                        <i class="bi bi-heart-pulse-fill me-2"></i>BIO-UBS
                    </h1>
                </div>
                <footer class="branding-content position-absolute bottom-0 mb-4 text-white">
                    <small>BIO-UBS 2025 &copy; | Versão 0.0.1</small>
                </footer>
            </aside>

            <section class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light p-4 p-md-5">
                <div class="w-100" style="max-width: 450px;">
                    <header class="text-center mb-5">
                        <h2 class="h1 fw-bold text-dark">Bem-vindo!</h2>
                        <p class="text-muted">Acesse o sistema para continuar.</p>
                    </header>

                    <?php if ($login_error_message): ?>
                        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <?= htmlspecialchars($login_error_message); ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form id="loginForm" method="POST" action="actions/auth.php" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <div class="mb-3">
                            <label for="matricula" class="form-label fw-medium">Matrícula</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <div class="form-floating">
                                    <input type="text" id="matricula" name="matricula" class="form-control" placeholder="Matrícula" 
                                           value="<?= htmlspecialchars($attempted_matricula); ?>" required autocomplete="username">
                                    <label for="matricula">Matrícula</label> 
                                    </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Senha</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <div class="form-floating">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Senha" required minlength="6" autocomplete="current-password">
                                    <label for="password">Senha</label> 
                                    </div>
                                <span style="cursor:pointer" class="input-group-text" id="togglePassword"><i class="bi bi-eye-fill"></i></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Lembrar-me</label>
                            </div>
                            <a href="./actions/reset_password_request.php" class="text-decoration-none small">Esqueceu a senha?</a>
                        </div>

                        <div id="alertBox" class="alert alert-danger d-none py-2 small text-center"></div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">Entrar</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Script para mostrar/ocultar senha (Mantido)
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        if (togglePassword && passwordInput) { // Adicionada verificação
            const eyeIcon = togglePassword.querySelector('i');
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('bi-eye-fill');
                eyeIcon.classList.toggle('bi-eye-slash-fill');
            });
        }

        // Validação simples no cliente (Mantida, mas ajustada para 'matricula')
        const loginForm = document.getElementById('loginForm');
        if (loginForm) { // Adicionada verificação
            loginForm.addEventListener('submit', e => {
                const user = document.getElementById('matricula').value.trim(); // Corrigido ID e nome
                const pass = document.getElementById('password').value;
                const alertBox = document.getElementById('alertBox');
                alertBox.classList.add('d-none'); // Esconde alerta JS
                
                // Validação de comprimento (ajustada para matrícula)
                if (user.length < 1 || pass.length < 6) { 
                    e.preventDefault(); // Impede envio do formulário
                    alertBox.textContent = "Matrícula ou senha inválidos.";
                    alertBox.classList.remove('d-none'); // Mostra alerta JS
                }
            });
        }
    </script>
</body>
</html>