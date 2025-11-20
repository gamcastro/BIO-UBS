<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\UbsCrudAll;

      //-------campos via post
        $nome = $_POST['nome']; //---------o name no formulario é livre mas deve ser recebido aqui
        $data_nascimento = $_POST['data_nascimento'];
        $cpf = $_POST['cpf'];
        $rg = $_POST['rg'];
        $uf_rg = $_POST['uf_rg'];
        $ssp = $_POST['ssp'];
        $telefone_contato = isset($_POST['telefone_contato']) ? preg_replace('/[^0-9]/', '', (string)$_POST['telefone_contato']) : null;
      //----------------------

//------------------inserindo na tabela pacientes---------

    $tabela = 'cadastro_paciente'; //----tabela para a query

    $colunasPermitidas = 
    [
      'NOME', 
      'DATA_NASCIMENTO', 
      'CPF', 
      'RG', 
      'UF_RG', 
      'SSP',
      'TELEFONE_CONTATO'
    ]; //--nao informar ID chave primaria

  $objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

    $dados = ([
        'NOME'              => $nome,
        'DATA_NASCIMENTO'   => $data_nascimento,
        'CPF'               => $cpf,
      'RG'                => $rg,
      'UF_RG'             => $uf_rg,
      'SSP'               => $ssp,
      'TELEFONE_CONTATO'  => $telefone_contato
    ]);

    $isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDePacientes.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
