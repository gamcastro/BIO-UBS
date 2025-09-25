<?php


//-------campos via post
$nome = $_POST['nome_completo']; //---------o name no formulario é livre mas deve ser recebido aqui

//---------------------- Dados Pessoais ----------------
$data_nascimento = $_POST['data_nascimento'];

$cpf = $_POST['cpf'];

$cns_profissional = $_POST['cns_profissional'];

$conselho = $_POST['conselho_classe'];

$sexo = $_POST['sexo'];

$registro_conselho = $_POST['registro_conselho'];

$estado_conselho = $_POST['estado_emissor_conselho'];
//----------------------

//------------------ Dados para contato ----------------
$email = $_POST['email'];
$telefone = $_POST['telefone'];

//---------------------- Dados do Conselho de Classe ----------------
$conselho = $_POST['conselho_classe'];
$registro_conselho = $_POST['registro_conselho'];
$estado_conselho = $_POST['estado_emissor_conselho'];

//---------------------- Dados do Endereço ----------------
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$complemento = $_POST['complemento'];
$municipio = $_POST['municipio'];
$estado_endereco = $_POST['estado_endereco'];
$ponto_referencia = $_POST['ponto_referencia'];
//----------------------

//--------------------- Dados de Acesso ----------------
$senha = $_POST['senha_hash'];


//------------------inserindo na tabela profissionais---------

$tabela = 'cadastro_profissional'; //----tabela para a query

$colunasPermitidas =
  [
    'NOME_COMPLETO',
    'CPF',
    'CNS_PROFISSIONAL',
    'DATA_NASCIMENTO',
    'SEXO',
    'EMAIL',
    'TELEFONE',
    'CONSELHO_CLASSE',
    'REGISTRO_CONSELHO',
    'ESTADO_EMISSOR_CONSELHO',
    'CEP',
    'ESTADO_ENDERECO',
    'MUNICIPIO',
    'BAIRRO',
    'LOGRADOURO',
    'NUMERO',
    'COMPLEMENTO',
    'PONTO_REFERENCIA',
    'SENHA_HASH'

  ]; //--nao informar ID chave primaria

$objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

$dados = ([
  'NOME_COMPLETO'              => $nome,
  'CPF'               => $cpf,
  'CNS_PROFISSIONAL'  => $cns_profissional,
  'DATA_NASCIMENTO'   => $data_nascimento,
  'SEXO'              => $sexo,
  'EMAIL'             => $email,
  'TELEFONE'          => $telefone,
  'CONSELHO_CLASSE'   => $conselho,
  'REGISTRO_CONSELHO' => $registro_conselho,
  'ESTADO_EMISSOR_CONSELHO' => $estado_conselho,
  'CEP'               => $cep,
  'ESTADO_ENDERECO'  => $estado_endereco,
  'MUNICIPIO'        => $municipio,
  'BAIRRO'           => $bairro,
  'LOGRADOURO'        => $logradouro,
  'NUMERO'           => $numero,
  'COMPLEMENTO'      => $complemento,
  'PONTO_REFERENCIA' => $ponto_referencia,
  'SENHA_HASH'            => $senha


]);

$isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDeProfissionais.php'
  </script>";

//----------------------------------------------------------  

die;//----- se entrar para o código aqui
