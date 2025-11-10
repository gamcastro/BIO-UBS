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
      //----------------------

// Normalizações de segurança/consistência no servidor
// - Nome em Title Case (primeiras letras maiúsculas)
// - Espaços duplicados colapsados
// - SSP em MAIÚSCULAS (mantém comportamento atual do front)
  $nome = trim(preg_replace('/\s+/u', ' ', (string)$nome));
  $nome = mb_convert_case(mb_strtolower($nome, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
  $ssp  = mb_strtoupper((string)$ssp, 'UTF-8');
  // CPF apenas dígitos
  if (!function_exists('sanitize_cpf')) { require_once __DIR__ . '/../../includes/functions.php'; }
  $cpf = sanitize_cpf($cpf);

//------------------inserindo na tabela pacientes---------

    $tabela = 'cadastro_paciente'; //----tabela para a query

    $colunasPermitidas = 
    [
      'NOME', 
      'DATA_NASCIMENTO', 
      'CPF', 
      'RG', 
      'UF_RG', 
      'SSP'
    ]; //--nao informar ID chave primaria

  // Instancia a classe do namespace BioUBS explicitamente
  $objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

  // Validação da UF (código deve existir na tabela ibge_ufs)
  $pdo = BioUBS\Conexao::getConn();
  $stmtUf = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
  $erros = [];
  if ($uf_rg === '' || $uf_rg === null) {
    // opcional: mantém nulo quando não escolhido
    $uf_rg = null;
  } elseif (!preg_match('/^\\d+$/', (string)$uf_rg)) {
    $erros[] = 'Código de UF inválido.';
  } else {
    $stmtUf->bindValue(':cd', (int)$uf_rg, PDO::PARAM_INT);
    $stmtUf->execute();
    if (!$stmtUf->fetchColumn()) {
      $erros[] = 'UF não encontrada.';
    }
  }
  if ($erros) {
    $msg = implode("\n", $erros);
    echo "<script>window.alert('Erro de validação:\n$msg'); window.history.back();</script>";
    die;
  }

  $dados = ([
    'NOME'              => $nome,
    'DATA_NASCIMENTO'   => $data_nascimento,
    'CPF'               => $cpf,
    'RG'                => $rg,
    'UF_RG'             => $uf_rg,
    'SSP'               => $ssp
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
