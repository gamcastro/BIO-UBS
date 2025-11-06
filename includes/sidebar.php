<?php

/**
 * includes/sidebar.php
 * * A barra de navegação lateral principal do sistema.
 * ESTA É A VERSÃO REFATORADA:
 * - Remove o dropdown "Cadastros".
 * - Adiciona um link direto "Pacientes".
 * - Adiciona o link "Administração" (visível apenas para perfis de gestão).
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

// Pega a primeira letra do nome e a torna maiúscula (seguro para UTF-8)
$userInitial = mb_strtoupper(mb_substr($userName, 0, 1));

// Define os perfis que podem ver o link de Administração
// (Baseado na nossa discussão e no arquivo image_ed13e7.png)
$perfis_admin = ['COORDENADOR UBS', 'Administrador Município', 'Administrador do Sistema'];
$isAdmin = in_array($userProfile, $perfis_admin);
?>

<!-- Tema Claro (Light) -->
<div class="sidebar vh-100 d-flex flex-column bg-white border-end p-3" style="width: 280px;">

    <!-- 1. Logo (Centralizado) -->
    <a href="<?= BASE_URL ?>/index.php" class="d-flex align-items-center justify-content-center mb-3 text-dark text-decoration-none">
        <!-- Ícone azul -->
        <i class="bi bi-heart-pulse-fill fs-4 me-2 text-primary"></i>
        <span class="fs-4 fw-bold">BioUBS</span>
    </a>

    <!-- Espaço reservado no topo da sidebar (agora a informação da UBS fica no header) -->
    <div class="mb-3" style="min-height:4.2rem;"></div>

    <!-- 3. Menu de Navegação Principal (Refatorado) -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= BASE_URL ?>/index.php"
                class="nav-link <?= ($currentPage == 'index.php') ? 'active' : 'text-dark' ?>">
                <i class="bi bi-grid-fill me-2"></i>
                Dashboard
            </a>
        </li>

        <!-- MUDANÇA: Link direto para Pacientes -->
        <li class="nav-item">
            <a href="<?= BASE_URL ?>/pages/cadastroDePacientes.php"
                class="nav-link <?= ($currentPage == 'cadastroDePacientes.php') ? 'active' : 'text-dark' ?>">
                <i class="bi bi-people-fill me-2"></i>
                Pacientes
            </a>
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

        <!-- MUDANÇA: Link de Administração (Condicional) -->
        <?php if ($isAdmin): ?>
            <li class="nav-item mt-2 border-top pt-2">
                <a href="<?= BASE_URL ?>/pages/administracao.php"
                    class="nav-link <?= ($currentPage == 'administracao.php') ? 'active' : 'text-dark' ?>">
                    <i class="bi bi-gear-fill me-2"></i>
                    Administração
                </a>
            </li>
        <?php endif; ?>

    </ul>

    <!-- 4. Menu do Usuário (Rodapé da Sidebar) -->
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">

            <!-- Avatar com a Inicial (Estilo Sutil) -->
            <div class="me-2 user-avatar">
                <?= $userInitial ?>
            </div>

            <div>
                <!-- Primeiro Nome -->
                <strong class="d-block"><?= $userName ?></strong>
                <small class="text-muted"><?= $userProfile ?></small>
            </div>
        </a>

        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
            <li><a class="dropdown-item" href="#">Configurações</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                </a>
            </li>
        </ul>
    </div>
</div>