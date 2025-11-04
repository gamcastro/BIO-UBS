<?php
/**
 * includes/sidebar.php
 * * A barra de navegação lateral principal do sistema.
 * Esta versão usa o tema CLARO (light) e remove o botão "Novo Acolhimento".
 */

// Pega o nome da página atual para saber qual link "ativar"
$currentPage = basename($_SERVER['PHP_SELF']);

// Pega os dados da sessão para o menu do usuário
// Pega o nome completo da sessão (ex: "Markleny Martins Pinheiro")
$userFullName = htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário');
// Quebra o nome em partes usando o espaço
$userNameParts = explode(' ', $userFullName);
// Pega apenas a primeira parte (ex: "Markleny")
$userName = $userNameParts[0]; 
$userProfile = htmlspecialchars($_SESSION['user_perfil'] ?? 'Perfil');

?>

<!-- 
  Classes do tema claro (light)
  - Adicionado 'bg-white' e 'border-end' para um visual limpo.
-->
<div class="sidebar vh-100 d-flex flex-column bg-white border-end p-3" style="width: 280px;">
    
    <!-- 1. Logo e Nome da UBS -->
    <!-- MUDANÇA: Adicionado 'justify-content-center' para centralizar -->
    <a href="<?= BASE_URL ?>/index.php" class="d-flex align-items-center justify-content-center mb-3 text-dark text-decoration-none">
        <!-- MUDANÇA: Adicionado 'text-primary' para a cor azul -->
        <i class="bi bi-heart-pulse-fill fs-4 me-2 text-primary"></i>
        <span class="fs-4 fw-bold text-primary">BioUBS</span>
    </a>

    <!-- 2. Nome da UBS e Data/Hora -->
    <!-- MUDANÇA: Adicionado 'text-center' para centralizar -->
    <div class="sidebar-header border-top border-bottom pt-3 pb-3 mb-3 text-center">
        <h6 class="text-muted small text-uppercase">UBS - Central</h6>
        <!-- O ID 'live-datetime-sidebar' é usado pelo 'datetime-updater.js' -->
        <div id="live-datetime-sidebar" class="small">Carregando data...</div>
    </div>

    <!-- 3. Menu de Navegação Principal -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= BASE_URL ?>/index.php" 
               class="nav-link <?= ($currentPage == 'index.php') ? 'active' : 'text-dark' ?>">
                <i class="bi bi-grid-fill me-2"></i>
                Dashboard
            </a>
        </li>
        
        <!-- Dropdown de Cadastros -->
        <li class="nav-item">
            <a href="#cadastroSubmenu" data-bs-toggle="collapse" 
               class="nav-link text-dark d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-folder-fill me-2"></i>
                    Cadastros
                </span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <!-- Sub-itens -->
            <div class="collapse ps-4" id="cadastroSubmenu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/pages/cadastroDePacientes.php" 
                           class="nav-link <?= ($currentPage == 'cadastroDePacientes.php') ? 'active' : 'text-muted' ?> py-1">
                           Pacientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php" 
                           class="nav-link <?= ($currentPage == 'cadastroDeProfissionais.php') ? 'active' : 'text-muted' ?> py-1">
                           Profissionais
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/pages/cadastroDeUnidades.php" 
                           class="nav-link <?= ($currentPage == 'cadastroDeUnidades.php') ? 'active' : 'text-muted' ?> py-1">
                           Unidades
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a href="#" class="nav-link text-dark">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Documentos
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark">
                <i class="bi bi-graph-up-arrow me-2"></i>
                Relatórios
            </a>
        </li>
    </ul>
    
    <!-- 4. Menu do Usuário (Rodapé da Sidebar) -->
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <div>
                <!-- Esta linha agora exibirá apenas o primeiro nome -->
                <strong class="d-block"><?= $userName ?></strong>
                <small class="text-muted"><?= $userProfile ?></small>
            </div>
        </a>
        
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
            <li><a class="dropdown-item" href="#">Configurações</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                </a>
            </li>
        </ul>
    </div>
</div>

