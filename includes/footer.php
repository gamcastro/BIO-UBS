   

    </main> <!-- Fecha a tag <main class="... d-flex flex-column ..."> aberta no header.php -->
    
</div> <!-- Fecha a tag <div class="d-flex min-vh-100"> aberta no header.php -->

<!--
==================================================================
SCRIPTS JAVASCRIPT GLOBAIS
==================================================================
-->

<!----biblioteca java script bootstrap (responsavel pela modal e outros efeitos)-->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!----biblioteca axios para requisições Ajax--->

<!-- Scripts do DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<!-- Scripts personalizados do sistema -->
<script src="<?= BASE_URL ?>/js/custom.js"></script> 
<!-- Validação de data de nascimento (feedback Bootstrap, min/max dinâmicos) -->
<script src="<?= BASE_URL ?>/js/validacao-data-nascimento.js"></script>
<!-- Validação de formulários (e-mail e outros campos) -->
<script src="<?= BASE_URL ?>/js/validacao-formularios.js"></script>
<script src="<?= BASE_URL ?>/tableScript/tableSimples.js"></script>

<!--
==================================================================
SCRIPT PARA DATA/HORA DA SIDEBAR 
==================================================================
-->

<script src="<?= BASE_URL ?>/js/datetime-updater.js"></script>

</body>
</html>

