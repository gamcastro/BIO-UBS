<?php
/**
 * Tela de Login - BIO-UBS
 * - Adicionando proteção CSRF (token oculto)
 * - Form aponta para auth.php (processamento seguro com PDO)
 * - Validações mínimas no cliente (JS)
 */
session_start();
require_once __DIR__ . '/includes/csrf.php'; //--------------gera/valida tokens CSRF
$csrf_token = generate_csrf();               //--------------token válido por 30 min

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BIO-UBS</title>
    <!------- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!------- Bootstrap Icons via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!------- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
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
            <!------- Coluna da Esquerda (Marca com Imagem) -->
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

            <!------- Coluna da Direita (Formulário de Login) -->
            <section class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light p-4 p-md-5">
                <div class="w-100" style="max-width: 450px;">
                    <header class="text-center mb-5">
                        <h2 class="h1 fw-bold text-dark">Bem-vindo!</h2>
                        <p class="text-muted">Acesse o sistema para continuar.</p>
                    </header>

                    <!------- IMPORTANTE!!!: action aponta para auth.php (processamento seguro) -->
                    <form id="loginForm" method="POST" action="auth.php" novalidate>
                        <!------- CSRF impede envio malicioso de outro site -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <!------- Campo Usuário -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-medium">Matrícula</label>
                            <!-- <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Seu usuário" required maxlength="80" autocomplete="username">
                            </div> -->

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <div class="form-floating">
                                    <input type="text" id="username" name="username" class="form-control" id="floatingInputGroup1" placeholder="Matrícula">
                                    <label for="floatingInputGroup1">Matrícula</label>
                                </div>
                            </div>

                        </div>

                        <!------- Campo Senha -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Senha</label>
                            <!-- <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                                
                                <input type="password" id="password" name="password" class="form-control" placeholder="Sua senha" required minlength="8" autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div> -->

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <div class="form-floating">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Senha" required minlength="8" autocomplete="current-password" id="floatingInputGroup2">
                                    <label for="floatingInputGroup2">Senha</label>
                                </div>
                                    <span style="cursor:pointer" class="input-group-text" id="togglePassword"><i class="bi bi-eye-fill"></i></span>
                            </div>


                        </div>

                        <!------- Opções (Lembrar-me e Esqueceu a senha) -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <!-- <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Lembrar-me</label> -->
                            </div>
                            <!------- Link para fluxo de reset de senha -->
                            <a href="reset_password_request.php" class="text-decoration-none small">Esqueceu a senha?</a>
                        </div>

                        <!------- Caixa de alerta (erros do cliente) -->
                        <div id="alertBox" class="alert alert-danger d-none py-2 small text-center"></div>

                        <!------- Botão Entrar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">Entrar</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <!------- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        //---------------Mostra/oculta a senha----------------------
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = togglePassword.querySelector('i');
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye-fill');
            eyeIcon.classList.toggle('bi-eye-slash-fill');
        });

        //------------------Validação simples no cliente (não substitui a do servidor)
        document.getElementById('loginForm').addEventListener('submit', e => {
            const user = document.getElementById('username').value.trim();
            const pass = document.getElementById('password').value;
            const alertBox = document.getElementById('alertBox');
            alertBox.classList.add('d-none');
            if (user.length < 3 || pass.length < 6) {
                e.preventDefault();
                alertBox.textContent = "Usuário ou senha inválidos.";
                alertBox.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
