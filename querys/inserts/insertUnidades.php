<?php

require_once  __DIR__ . '/../../vendor/autoload.php'; // Autoloader
use BioUBS\UbsCrudAll ;
//-------campos via post
//---------o name no formulario é livre mas deve ser recebido aqui

//---------------------- Dados da Unidade ----------------

$nome = $_POST['nome'];

$cnes = $_POST['cnes'];

$cnpj = $_POST['cnpj'];


//----------------------

//------------------ Dados para contato ----------------

$telefone = $_POST['telefone'];


//---------------------- Dados do Endereço ----------------
$cep = $_POST['cep'];

$estado_endereco = $_POST['uf'];
$municipio = $_POST['municipio'];
$bairro = $_POST['bairro'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];

//----------------------


//------------------inserindo na tabela unidades---------

$tabela = 'cadastro_unidade'; //----tabela para a query

$colunasPermitidas =
  [
    'NOME',
    'CNES',
    'CNPJ',
    'TELEFONE',
    'CEP',
    'ESTADO',
    'MUNICIPIO',
    'BAIRRO',
    'LOGRADOURO',
    'NUMERO',
    'COMPLEMENTO'   

  ]; //--nao informar ID chave primaria

$objeto = new \BioUBS\UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

$dados = ([
  'NOME'              => $nome,
  'CNES'              => $cnes,
  'CNPJ'              => $cnpj,
  'TELEFONE'          => $telefone,
  'CEP'               => $cep,
  'ESTADO'            => $estado_endereco,
  'MUNICIPIO'         => $municipio,
  'BAIRRO'            => $bairro,
  'LOGRADOURO'        => $logradouro,
  'NUMERO'            => $numero,
  'COMPLEMENTO'       => $complemento
  


]);

$isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDeUnidades.php'
  </script>";

//----------------------------------------------------------  

die;//----- se entrar para o código aqui
