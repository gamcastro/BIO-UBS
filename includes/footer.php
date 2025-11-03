    </main> <!-- Fecha a tag <main class="flex-grow-1 p-4"> aberta no header.php -->
    
</div> <!-- MUDANÇA: Fecha a tag <div class="d-flex"> aberta no header.php -->

<!--
==================================================================
RODAPÉ (Como solicitado por você)
==================================================================
-->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; 2025 Copyright: <a href="<?= BASE_URL ?>/index.php" class="text-white text-decoration-none">BIO UBS</a>
</footer>

<!--
==================================================================
SCRIPTS JAVASCRIPT GLOBAIS (Mantendo sua estrutura)
==================================================================
-->

<!----biblioteca java script bootstrap (responsavel pela modal e outros efeitos)-->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!----biblioteca axios para requisições Ajax--->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script src="<?= BASE_URL ?>/js/custom.js"></script> 
<script src="<?= BASE_URL ?>/tableScript/tableSimples.js"></script>
<!-- 
  NOTA: O seu arquivo 'custom.js' ou 'tableSimples.js' 
  deve conter a lógica para inicializar os DataTables e 
  carregar as modais (como a 'acolhimentoBioUBS').
-->
</body>
</html>