

<?php
require_once('../../class/Conexao.php');

if(isset($_GET['id'])): //----só sugirá o conteúdo de vier um ID
  
          $id = $_GET['id'];

        //-----------criterios de consulta--------------
          $camposIdBio = "*";
          $tabelaIdBio = "cadastro_paciente";
        //----------------------------------------------

        //----------CONSULTA BÁSICA COM ID E OS CRITÉRIOS ACIMA
          require_once('../../querys/ConsultaPorId.php');
            
            //-------buscando dados na tabela------------------   
            while($rowsId = $buscaId->fetch(PDO::FETCH_ASSOC)){

                $nomePaciente = $rowsId['NOME'];
                $data_nascimento = $rowsId['DATA_NASCIMENTO'];
                $cpf = $rowsId['CPF'];
                $rg = $rowsId['RG'];
                      
                      //---------uf do RG salva no cadastro----------------------
                        $cd_uf_rg = $rowsId['UF_RG'];

                          require_once('../../querys/ConsultaUnidadeFederativaPorID.php');

                          while($rowsUfRg = $buscaUfId->fetch(PDO::FETCH_ASSOC)){
                            $uf_rg = $rowsUfRg['DS_UF_SIGLA'];
                            $cd_uf_rg = $rowsUfRg['CD_UF'];
                            $uf_nome_rg = $rowsUfRg['DS_UF_NOME'];
                          }  
                      //---------------------------------------------

                $ssp = $rowsId['SSP'];

            }    
 ?> 



<!------------------janela modal-------------------------------------------->

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header bg-primary text-white border-0">
              <h5 class="modal-title mb-0">Editar registro</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <!-------------------------------------------------------->

            <form id="ed" name="ed" action="" method="post">
                  <input type="hidden" name="id" value="<?=$id?>">

            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body p-3">

              <div class="p-3 mb-3 rounded bg-light">
                <strong class="d-block mb-2 text-primary">Dados</strong>

                <div class="row g-3">
                  <div class="col-md-9">
                    <label for="nome" class="form-label small">Nome</label>
                    <input class="form-control" type="text" id="nome" name="nome" required placeholder="Nome completo" data-callback="alteraNome" data-titlecase="true" value="<?=$nomePaciente?>">
                  </div>
                  <div class="col-md-3">
                    <label for="data_nascimento" class="form-label small">Data de Nascimento</label>
                    <input class="form-control" type="date" name="data_nascimento" required value="<?=$data_nascimento?>">
                  </div>

                  <div class="col-md-3">
                    <label for="cpf" class="form-label small">CPF</label>
                    <input class="form-control" type="text" name="cpf" placeholder="ex: 000.000.000-00" data-mask="###.###.###-##" value="<?=$cpf?>">
                  </div>

                  <div class="col-md-3">
                    <label for="rg" class="form-label small">Reg</label>
                    <input class="form-control" type="text" name="rg" placeholder="ex: 0000000000-0" inputmode="numeric" data-numeric="true" value="<?=$rg?>">
                  </div>

                  <div class="col-md-3">
                    <label for="uf_rg" class="form-label small">UF</label>
                    <select name="uf_rg" class="form-select">
                      <?php $selectedUf = ($cd_uf_rg !== null && $cd_uf_rg !== '' && is_numeric($cd_uf_rg)) ? (string)$cd_uf_rg : null; ?>
                      <?php if ($selectedUf === null): ?>
                        <option value="" disabled selected>UF</option>
                      <?php else: ?>
                        <option value="" disabled>UF</option>
                      <?php endif; ?>
                      <?php include(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php'); ?>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label for="ssp" class="form-label small">Orgão</label>
                    <input class="form-control" type="text" id="ssp" name="ssp" placeholder="ex: SSP/MA" data-callback="alteraSSP" value="<?=$ssp?>">
                  </div>
                </div>
              </div>

            </div>
            <!--------------------------------------------------------->

            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="editar" class="btn btn-success">Salvar</button>
            </div>

      </form>
            <!----------------------------------------------------->

    
<!----------------------------fim da da janela modal----------------------------->


<?php 
endif;
?>
