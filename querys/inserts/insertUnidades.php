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
$municipio = $_POST['municipio']; // ID do município via TomSelect
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
    'ID_MUNICIPIO',
    'BAIRRO',
    'LOGRADOURO',
    'NUMERO',
    'COMPLEMENTO'   

  ]; //--nao informar ID chave primaria

$objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

$dados = ([
  'NOME'              => $nome,
  'CNES'              => $cnes,
  'CNPJ'              => $cnpj,
  'TELEFONE'          => $telefone,
  'CEP'               => $cep,
  // Validação da UF (código deve existir na tabela ibge_ufs)
  // Executada antes de montar o array final
  // (mantido aqui para mínima alteração estrutural)
  'ESTADO'            => $estado_endereco,
  'ID_MUNICIPIO'      => (isset($municipio) && $municipio !== '' ? (int)$municipio : null),
  'BAIRRO'            => $bairro,
  'LOGRADOURO'        => $logradouro,
  'NUMERO'            => $numero,
  'COMPLEMENTO'       => $complemento
  


]);

// Validação da UF após obter conexão
$pdo = BioUBS\Conexao::getConn();
$erros = [];
if ($estado_endereco === '' || $estado_endereco === null) {
  // opcional: mantém nulo quando não escolhido
  $estado_endereco = null;
} elseif (!preg_match('/^\d+$/', (string)$estado_endereco)) {
  $erros[] = 'Código de UF inválido.';
} else {
  $stmt = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
  $stmt->bindValue(':cd', (int)$estado_endereco, PDO::PARAM_INT);
  $stmt->execute();
  if (!$stmt->fetchColumn()) {
    $erros[] = 'UF não encontrada.';
  }
}
if ($erros) {
  $msg = implode("\n", $erros);
  echo "<script>window.alert('Erro de validação:\n$msg'); window.history.back();</script>";
  die;
}

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
