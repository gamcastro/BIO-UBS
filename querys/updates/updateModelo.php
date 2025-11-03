<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\UbsCrudAll;

      //-------ID -----------
        $id = $_POST['id']; 
      //---------------------


      /*-------campos do formulário 
      via post
      */
        $nome = $_POST['nome']; 
        $data_nascimento = $_POST['data_nascimento'];
        $cpf = $_POST['cpf'];
        $rg = $_POST['rg'];
        $uf_rg = $_POST['uf_rg'];
        $ssp = $_POST['ssp'];
      //----------------------

//------------------alterado os dados do pacientes---------


    $tabela = 'cadastro_paciente'; 

    $colunasPermitidas = 
    [
      'NOME', 
      'DATA_NASCIMENTO', 
      'CPF', 
      'RG', 
      'UF_RG', 
      'SSP'
    ];

  $objeto = new UbsCrudAll($tabela, $colunasPermitidas);

    $dados = ([
        'NOME'              => $nome,
        'DATA_NASCIMENTO'   => $data_nascimento,
        'CPF'               => $cpf,
        'RG'                => $rg,
        'UF_RG'             => $uf_rg,
        'SSP'               => $ssp
    ]);


    $updateUbs = $objeto->atualizar($id, $dados); //---funcao atualizar com 2 parametros

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro alterado com sucesso!');
  window.location='cadastroDePacientes.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
