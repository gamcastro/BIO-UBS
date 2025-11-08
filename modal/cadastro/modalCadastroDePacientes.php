
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertPaciente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> <!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
    <!-- contener da janela-->
    <div class="modal-content">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">
              <h4 class="modal-title">Cadastrando novo Paciente</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-------------------------------------------------------->










     <form id="cad" name="cad" action="" method="post"><!--------------formulário------->


            <!----------------CORPO DA JANELA------------------------->
            <div class="modal-body">

        
                <table class="table table-bordered">
                  <tr>
                    <td colspan="3" style="width: 500px">Nome:</td>
                    <td>Dt_nasc:</td>
                  </tr>

                  <tr>
                    <td colspan="3">
                      <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome completo" onkeyup="alteraNome()" minlength="3">
                      <div class="invalid-feedback">
                          Por favor, informe o nome completo (mínimo 3 caracteres).
                      </div>
                    </td>
                    <td>
                      <input class="form-control" type="date" name="data_nascimento" required="required">
                    </td>
                  </tr>

                  <tr>
                    <td>CPF:</td>
                    <td>Reg:</td>
                    <td>UF:</td>
                    <td>Orgão:</td>
                  </tr>

                  <tr>

                    <td>
                      <input class="form-control" type="" name="cpf" placeholder="ex: 000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');" required="required" minlength="14" maxlength="14">
                      <div class="invalid-feedback">
                          Por favor, informe um CPF válido (11 dígitos).
                      </div>
                    </td>

                    <td>
                      <input class="form-control" type="" name="rg" placeholder="ex: 0000000000-0">
                    </td>

                    <td>
                      <select name="uf_rg" class="form-select">
                        <option value="" disabled selected>UF</option>
                        <?php
                          require_once(__DIR__ . '/../../querys/ConsultaUnidadeFederativaSelect.php');
                        ?>
                      </select>
                      <div class="invalid-feedback">Selecione a UF do RG.</div>
                    </td>

                    <td>
                      <input class="form-control" type="" id="ssp" name="ssp" placeholder="ex: SSP/MA" onkeyup="alteraSSP()">
                    </td>
                  </tr>

                </table>
                
  
            </div>
            <!--------------------------------------------------------->


      












            
            <!---------------RODAPÉ DA JANELA---------------------->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            </div>
      
      </form><!----fim formulario--->
            <!----------------------------------------------------->

    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->