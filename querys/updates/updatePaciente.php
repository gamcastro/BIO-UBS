<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\UbsCrudAll;

//-------ID -----------
$id = $_POST['id'];
//---------------------

/*-------campos do formulário via post */
$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$uf_rg = $_POST['uf_rg'];
$ssp = $_POST['ssp'];
//----------------------

// Normalizações no servidor para consistência
$nome = trim(preg_replace('/\s+/u', ' ', (string)$nome));
$nome = mb_convert_case(mb_strtolower($nome, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
$ssp  = mb_strtoupper((string)$ssp, 'UTF-8');
// CPF apenas dígitos
if (!function_exists('sanitize_cpf')) { require_once __DIR__ . '/../../includes/functions.php'; }
$cpf = sanitize_cpf($cpf);

//------------------alterado os dados do pacientes---------
$tabela = 'cadastro_paciente';

$colunasPermitidas = [
  'NOME',
  'DATA_NASCIMENTO',
  'CPF',
  'RG',
  'UF_RG',
  'SSP'
];

$objeto = new UbsCrudAll($tabela, $colunasPermitidas);

$erros = [];
// Validação da UF (opcional: só valida se informado)
$pdo = BioUBS\Conexao::getConn();
$stmtUf = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
if ($uf_rg !== '' && $uf_rg !== null) {
  if (!preg_match('/^\d+$/', (string)$uf_rg)) {
    $erros[] = 'Código de UF inválido.';
  } else {
    $stmtUf->bindValue(':cd', (int)$uf_rg, PDO::PARAM_INT);
    $stmtUf->execute();
    if (!$stmtUf->fetchColumn()) {
      $erros[] = 'UF não encontrada.';
    }
  }
} else {
  // Não informado: mantém nulo para update se desejado
  $uf_rg = null;
}
if ($erros) {
  $msg = implode("\n", $erros);
  echo "<script>window.alert('Erro de validação:\n$msg'); window.history.back();</script>";
  die;
}

$dados = [
    'NOME' => $nome,
    'DATA_NASCIMENTO' => $data_nascimento,
    'CPF' => $cpf,
    'RG' => $rg,
  'UF_RG' => $uf_rg,
    'SSP' => $ssp
];

$updateUbs = $objeto->atualizar($id, $dados);

echo "<script>\nwindow.alert('Cadastro alterado com sucesso!');\nwindow.location='cadastroDePacientes.php'\n</script>";

die;
