<?php
/**
 * includes/sidebar.php
 * * A nova barra de navegação lateral.
 * Este arquivo é incluído pelo 'header.php'.
 * Ele assume que $BASE_URL e $_SESSION já estão disponíveis.
 */
?>
<!-- 
    CSS para a sidebar. 
    (Idealmente, mova isso para o seu 'custom.css' depois) 
-->
<style>
    .sidebar {
        width: 280px; /* Largura da barra lateral */
        height: 100vh; /* Altura total da tela */
        position: sticky; /* Fica presa no lugar ao rolar */
        top: 0;
        background-color: #ffffff; /* Cor de fundo */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* Sombra suave */
        z-index: 1020; /* Fica acima de outros elementos */
    }

    .sidebar .nav-link {
        color: #343a40;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #e9f0fd; /* Cor de hover/ativo */
        color: #0d6efd; /* Cor primária do Bootstrap */
    }

    .sidebar .nav-link .bi {
        margin-right: 0.75rem;
        font-size: 1.1rem;
        color: #6c757d; /* Cor dos ícones */
    }

    .sidebar .nav-link:hover .bi,
    .sidebar .nav-link.active .bi {
        color: #0d6efd;
    }

    /* Estilo do submenu (dropdown) */
    .sidebar-submenu {
        padding-left: 2.5rem; /* Indentação */
    }
    .sidebar-submenu .nav-link {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        font-size: 0.9rem;
    }
</style>

<!-- 
    A 'sidebar' é um 'd-flex flex-column' para empurrar 
    o menu do usuário para o final usando 'mb-auto' na lista <ul>
-->
<aside class="d-flex flex-column p-3 sidebar">
    
    <!-- 1. Logo/Marca -->
    <a class="navbar-brand d-flex align-items-center mb-3 text-dark text-decoration-none" href="<?= BASE_URL ; ?>/index.php">
        <i class="bi bi-heart-pulse-fill me-2" style="font-size: 1.5rem; color: #0d6efd;"></i>
        <span class="fs-4 fw-bold">BioUBS</span>
    </a>
    <hr class="mt-0">

    <!-- 2. Botão de Ação Principal (Novo Acolhimento) -->
    <div class="nav-item acolhimento-action mb-2">
        <a class="btn btn-primary w-100 p-2" href="#" data-url="../modal/fluxos/modalAcolhimento.php"
            data-bs-toggle="modal"
            data-bs-target="#acolhimentoBioUBS">
            <i class="bi bi-plus-circle-fill me-1"></i> Novo Acolhimento
        </a>
    </div>
    <hr>

    <!-- 3. Lista de Navegação Principal -->
    <!-- 'mb-auto' empurra esta lista para cima e o dropdown do usuário para baixo -->
    <ul class="nav nav-pills flex-column mb-auto">
        
        <!-- Link do Dashboard (Index) -->
        <li class="nav-item">
            <a href="<?= BASE_URL ?>/index.php" class="nav-link">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        <!-- Dropdown de Cadastros -->
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" href="#cadastros-submenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="cadastros-submenu">
                <span>
                    <i class="bi bi-person-badge"></i>
                    Cadastros
                </span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse" id="cadastros-submenu">
                <ul class="nav flex-column sidebar-submenu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>/pages/cadastroDePacientes.php">Pacientes</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/pages/cadastroDeProfissionais.php">Profissionais</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/pages/cadastroDeUnidades.php">Unidade</a></li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="bi bi-file-earmark-text-fill"></i>
                Documentos
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="bi bi-graph-up-arrow"></i>
                Relatórios
            </a>
        </li>

    </ul>
    
    <!-- 4. Menu do Usuário (no final) -->
    <hr>
    <div class="dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-3 me-2"></i>
            <div class="d-flex flex-column text-start">
                <span class="fw-bold" style="line-height: 1;">
                     <!-- Usando 'user_nome' (Nome Completo) para ser mais amigável -->
                    <?= htmlspecialchars(explode(' ', $_SESSION['user_nome'] ?? 'Usuário')[0]) ?>
                </span>
                <small class="text-muted" style="font-size: 0.8rem;">
                    <?= htmlspecialchars($_SESSION['user_perfil'] ?? 'Perfil') ?>
                </small>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="navbarUserDropdown">
            <li><a class="dropdown-item" href="#">
                <i class="bi bi-person-fill me-2"></i>Meu Perfil
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                </a>
            </li>
        </ul>
    </div>
</aside>
