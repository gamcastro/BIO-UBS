<?php


      //-------campos via post
        $nome = $_POST['nome']; //---------o name no formulario é livre mas deve ser recebido aqui
        
        $cpf = $_POST['cpf'];
       
        
      //----------------------

//------------------inserindo na tabela pacientes---------

    $tabela = 'cadastro_agente'; //----tabela para a query

    $colunasPermitidas = 
    [
      'NOME'  ,
      'CPF'
    ]; //--nao informar ID chave primaria

    $objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

    $dados = ([
        'NOME'              => $nome,
        'CPF'               => $cpf
        
    ]);

    $isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDeAgentes.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
