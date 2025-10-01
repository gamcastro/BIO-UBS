<?php

      //-------ID -----------
        $id = $_POST['id'];
      //---------------------

      

//------------------apagar registro de paciente---------

    $tabela = 'cadastro_profissional'; //----tabela para a query

    $objeto = new UbsCrudAll($tabela);

    $deleteUbs = $objeto->deleteId($id); //---funcao deletar por id

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Registro apagado com sucesso!');
  window.location='cadastroDeProfissionais.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
