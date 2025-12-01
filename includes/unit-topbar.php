<?php
// Partial: includes/unit-topbar.php
// Mostra o nome da UBS e a data/hora no topo do conteúdo principal.
$ubsNome = isset($ubsNome) ? htmlspecialchars($ubsNome) : (htmlspecialchars($_SESSION['ubs_nome'] ?? 'UBS - Central'));
?>
<div class="d-flex align-items-center justify-content-between p-3 bg-white shadow-sm unit-topbar" style="gap:1rem;">
    <div class="d-flex align-items-center" style="min-width:0;">
        <i class="bi bi-building me-2 text-primary fs-5"></i>
        <div class="text-truncate">
            <strong class="d-block" style="letter-spacing:.02em;"><?= $ubsNome ?></strong>
        </div>
    </div>
    <div id="live-datetime-top" class="small text-muted text-end" style="white-space:nowrap;">Carregando data...</div>
</div>
