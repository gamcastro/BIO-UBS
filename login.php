<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BIO-UBS</title>
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Define a fonte Inter como padrão e customizações de cores */
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-primary: #0d6efd; /* Azul padrão do Bootstrap */
            --bs-primary-rgb: 13, 110, 253;
        }

        body {
            background-color: #f8f9fa; /* bg-light */
        }
        
        /* Estilos para a coluna de branding com imagem de fundo */
        .branding-column {
            background-image: url('https://images.unsplash.com/photo-1576091160550-2173dba9996a?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Overlay para escurecer a imagem e melhorar a legibilidade do texto */
        .branding-column::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom right, rgba(13, 110, 253, 0.7), rgba(13, 110, 253, 0.5));
            z-index: 1;
        }

        /* Conteúdo da coluna de branding fica acima do overlay */
        .branding-content {
            position: relative;
            z-index: 2;
        }

        .form-control:focus, .form-check-input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
    <!-- <main> representa o conteúdo principal da página -->
    <main class="container-fluid">
        <div class="row min-vh-100">
            <!-- Coluna da Esquerda (Branding com Imagem) -->
            <aside class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center text-white text-center p-5 branding-column">
                <div class="branding-content">
                    <h1 class="display-3 fw-bold mb-4">
                        <i class="bi bi-heart-pulse-fill me-2"></i>BIO-UBS
                    </h1>
                    <p class="fs-4 fst-italic">Cuidando da nossa comunidade, um paciente de cada vez.</p>
                </div>
                <footer class="branding-content position-absolute bottom-0 mb-4 text-white-50">
                    <small>BIO-UBS 2025 &copy; | Versão 0.0.1</small>
                </footer>
            </aside>

            <!-- Coluna da Direita (Formulário de Login) -->
            <section class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light p-4 p-md-5">
                <div class="w-100" style="max-width: 450px;">
                    <header class="text-center mb-5">
                        <h2 class="h1 fw-bold text-dark">Bem-vindo!</h2>
                        <p class="text-muted">Acesse o sistema para continuar.</p>
                    </header>

                    <form action="index.php" method="POST">
                        <!-- Campo Usuário -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-medium">Usuário</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Seu usuário" required>
                            </div>
                        </div>

                        <!-- Campo Senha -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Senha</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Sua senha" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Opções (Lembrar-me e Esqueceu a senha) -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Lembrar-me</label>
                            </div>
                            <a href="#" class="text-decoration-none small">Esqueceu a senha?</a>
                        </div>

                        <!-- Botão Entrar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">
                                Entrar
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <!-- Bootstrap JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Lógica para mostrar/ocultar a senha
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            eyeIcon.classList.toggle('bi-eye-fill');
            eyeIcon.classList.toggle('bi-eye-slash-fill');
        });
    </script>
</body>
</html>

