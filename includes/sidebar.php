<?php
/**
 * includes/sidebar.php
 * * A barra de navegação lateral principal do sistema.
 * Esta versão usa o tema CLARO (light), centraliza o logo,
 * estiliza o "Info Card" de UBS/Data e exibe a inicial do usuário.
 */

// Pega o nome da página atual para saber qual link "ativar"
$currentPage = basename($_SERVER['PHP_SELF']);

// Pega os dados da sessão para o menu do usuário
// Pega o nome completo da sessão (ex: "Markleny Martins Pinheiro")
$userFullName = htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário');
// Quebra o nome em partes usando o espaço
$userNameParts = explode(' ', $userFullName);
// Pega apenas a primeira parte (ex: "Markleny")
$userName = ucfirst(strtolower($userNameParts[0])); 
$userProfile = htmlspecialchars($_SESSION['user_perfil'] ?? 'Perfil');

// Pega a primeira letra do nome e a torna maiúscula (seguro para UTF-8)
$userInitial = mb_strtoupper(mb_substr($userName, 0, 1));

?>

<!-- 
  Classes do tema claro (light)
  - 'bg-white' e 'border-end' para um visual limpo.
-->
<div class="sidebar vh-100 d-flex flex-column bg-white border-end p-3" style="width: 280px;">
    
    <!-- 1. Logo e Nome da UBS -->
    <!-- 'justify-content-center' para centralizar -->
    <a href="<?= BASE_URL ?>/index.php" class="d-flex align-items-center justify-content-center mb-3 text-dark text-decoration-none">
        <!-- 'text-primary' para a cor azul -->
        <i class="bi bi-heart-pulse-fill fs-4 me-2 text-primary"></i>
        <span class="fs-4 fw-bold">BioUBS</span>
    </a>

    <!-- 
      2. Info Card da UBS e Data/Hora 
      MUDANÇA: Estilizado como um "card" (bg-light) com ícones.
    -->
    <div class="sidebar-header bg-light rounded p-2 mb-3" style="font-size: 0.9rem;">
        <div class="d-flex align-items-center">
            <i class="bi bi-building me-2 text-primary"></i>
            <h6 class="text-dark small fw-bold text-uppercase mb-0">UBS - Central</h6>
        </div>
        <hr class="my-1"> <!-- Linha fina de separação -->
        <div class="d-flex align-items-center">
            <i class="bi bi-clock me-2 text-muted"></i>
            <!-- O ID 'live-datetime-sidebar' é usado pelo 'datetime-updater.js' -->
            <div id="live-datetime-sidebar" class="small text-muted">Carregando data...</div>
        </div>
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
            
            <!-- 
              Avatar com a letra inicial
              - Fundo cinza escuro sutil e letra em azul claro
            -->
            <div class="me-2" 
                 style="width: 38px; height: 38px; border-radius: 50%; background-color: #6c757d; color: #add8e6; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem;">
                <?= $userInitial ?>
            </div>
            
            <div>
                <!-- Apenas o primeiro nome -->
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