
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertPaciente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> <!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
    <!-- contener da janela-->
    <div class="modal-content">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&times;</button><!------botao fechar------>
              
              <h4 class="modal-title">Cadastrando novo Paciente</h4>

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
                      <input class="form-control" type="text" id="nome" name="nome" required="required" placeholder="Nome completo" onkeyup="alteraNome()">
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
                      <input class="form-control" type="" name="cpf" placeholder="ex: 000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');">
                    </td>

                    <td>
                      <input class="form-control" type="" name="rg" placeholder="ex: 0000000000-0">
                    </td>

                    <td>
                      <select name="uf_rg" class="form-control">
                        <option value="">UF</option>
                        <?php
                          require_once('querys/ConsultaUnidadeFederativaSelect.php');
                        ?>
                      </select>  
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
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="submit" name="salvar" class="btn btn-success" >Salvar</button>
            </div>
      
      </form><!----fim formulario--->
            <!----------------------------------------------------->

    </div>

  </div>
</div>
<!----------------------------fim da da janela modal----------------------------->