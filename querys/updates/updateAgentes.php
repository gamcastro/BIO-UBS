<?php


      //-------ID -----------
        $id = $_POST['id']; 
      //---------------------


      /*-------campos do formulário 
      via post
      */
        $nome = $_POST['nome']; 
      
        $cpf = $_POST['cpf'];
       
      //----------------------

//------------------alterado os dados do pacientes---------


    $tabela = 'cadastro_agente'; 

    $colunasPermitidas = 
    [
      'NOME', 
      'CPF'
      
    ];

    $objeto = new UbsCrudAll($tabela, $colunasPermitidas);

    $dados = ([
        'NOME'              => $nome,
        'CPF'               => $cpf
    ]);


    $updateUbs = $objeto->atualizar($id, $dados); //---funcao atualizar com 2 parametros

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro alterado com sucesso!');
  window.location='cadastroDeAgentes.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
