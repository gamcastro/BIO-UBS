
<!----------------------------janela modal--------------------------------------------------------->

<div id="insertProfissional" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> <!------aqui consigo mudar o tamanho da modal para modal-lg modal-sm------>
    <!-- contener da janela-->
    <div class="modal-content">

            <!-------------CABEÇALHO DA JANELA------------------------->
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&times;</button><!------botao fechar------>
              
              <h4 class="modal-title">Cadastrando novo Profissional</h4>

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
                    <td>CNS:</td>
                    <td>Conselho de Classe:</td>
                    <td>Estado Emissor:</td>
                    <td>Reigistro no Conselho:
                  </tr>

                  <tr>

                    <td>
                      <input class="form-control" type="" name="cpf" placeholder="ex: 000.000.000-00" onkeypress="return mascaras(event, this, '###.###.###-##');">
                    </td>

                    <td>
                      <input class="form-control" type="" name="cns" placeholder="ex: 000000000000000">
                    </td>
                    <td>
                      <input class="form-control" type="" name="conselho" placeholder="ex: conselho regional de medicina">
                    </td>

                    <td>
                      <select name="uf_cns" class="form-control">
                        <option value="">UF</option>
                        <?php
                          require_once('querys/ConsultaUnidadeFederativaSelect.php');
                        ?>
                      </select>  
                    </td>

                    <td>
                      <input class="form-control" type="" id="registro_conselho" name="registro_conselho" placeholder="ex: 000000" >
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