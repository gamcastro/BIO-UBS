<?php

      //-------ID -----------
        $id = $_POST['id'];
      //---------------------

      

//------------------apagar registro de paciente---------

    $tabela = 'cadastro_agente'; 

  $objeto = new \BioUBS\UbsCrudAll($tabela);

    $deleteUbs = $objeto->deleteId($id); //---funcao deletar por id

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Registro apagado com sucesso!');
  window.location='cadastroDeAgentes.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
