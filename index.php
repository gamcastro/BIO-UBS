<?php
// Define o título que será usado no <title> dentro do header.php
$tituloDaPagina = "BIO-UBS - Página Inicial";
// Inclui o header.php (que agora abre a tag <main class="flex-grow-1 container py-4">)
include_once('includes/header.php');
?>

<div class="text-center">
    <h1 class="display-4 mb-3">Bem-vindo ao BIO-UBS</h1>
    <p class="lead text-muted">Selecione uma opção no menu acima para começar.</p>
</div>

<?php
// Inclui o footer.php (que agora fecha a tag </main> e adiciona o <footer>)
include_once('includes/footer.php');
?>