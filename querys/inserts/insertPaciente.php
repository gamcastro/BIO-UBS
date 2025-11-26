<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use BioUBS\UbsCrudAll;

  //-------campos via post
  $nome = $_POST['nome']; //---------o name no formulario é livre mas deve ser recebido aqui
  $data_nascimento = $_POST['data_nascimento'];
  $cpf = $_POST['cpf'];
  $cns = $_POST['cns'];
  $nome_mae = $_POST['nome_mae'];
  $telefone_celular = $_POST['telefone_celular'];
  $telefone_contato = isset($_POST['telefone_contato']) ? $_POST['telefone_contato'] : null;
  $cep = $_POST['cep'];
  $id_municipio = $_POST['municipio'];
  $estado = $_POST['estado'];
  $endereco = $_POST['endereco'];
  $numero = $_POST['numero'];
  $complemento = $_POST['complemento'];
  $bairro = $_POST['bairro'];
  $sexo = $_POST['sexo'];
  $raca_cor = $_POST['raca_cor'];
  $telefone_residencial = $_POST['telefone_residencial'];
  $email = $_POST['email'];
  $rg = $_POST['rg'];
  $uf_rg = $_POST['uf_rg'];
  $ssp = $_POST['ssp'];
  $lgpd_consent = isset($_POST['lgpd_consent']) ? 1 : 0;
      //----------------------

// Normalizações de segurança/consistência no servidor
// - Nome e Nome da Mãe em Title Case (primeiras letras maiúsculas)
// - Espaços duplicados colapsados
// - SSP em MAIÚSCULAS (mantém comportamento atual do front)
  // Normalização avançada mantendo preposições em minúsculo
  if (!function_exists('normalize_nome')) { require_once __DIR__ . '/../../includes/functions.php'; }
  $nome = normalize_nome($nome);
  $nome_mae = normalize_nome($nome_mae);
  $ssp  = mb_strtoupper((string)$ssp, 'UTF-8');
  // CPF e CNS apenas dígitos
  if (!function_exists('sanitize_cpf')) { require_once __DIR__ . '/../../includes/functions.php'; }
  $cpf = sanitize_cpf($cpf);
  $cns = preg_replace('/[^0-9]/', '', (string)$cns);
  $rg = preg_replace('/[^0-9]/', '', (string)$rg);
  // Telefones apenas dígitos
  $telefone_celular = preg_replace('/[^0-9]/', '', (string)$telefone_celular);
  $telefone_residencial = preg_replace('/[^0-9]/', '', (string)$telefone_residencial);
  $telefone_contato = preg_replace('/[^0-9]/', '', (string)$telefone_contato);
  // CEP apenas dígitos
  $cep = preg_replace('/[^0-9]/', '', (string)$cep);

//------------------inserindo na tabela pacientes---------

    $tabela = 'cadastro_paciente'; //----tabela para a query

    $colunasPermitidas = 
    [
      'NOME', 
      'DATA_NASCIMENTO', 
      'CPF', 
      'CNS',
      'NOME_MAE',
      'TELEFONE_CELULAR',
      'CEP',
      'ID_MUNICIPIO',
      'ESTADO',
      'ENDERECO',
      'NUMERO',
      'COMPLEMENTO',
      'BAIRRO',
      'SEXO',
      'RACA_COR',
      'TELEFONE_RESIDENCIAL',
      'TELEFONE_CONTATO',
      'EMAIL',
      'RG', 
      'UF_RG', 
      'SSP',
      'LGPD_CONSENT'
    ]; //--nao informar ID chave primaria

  // Instancia a classe do namespace BioUBS explicitamente
  $objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

  // Validação das UFs (códigos devem existir na tabela ibge_ufs)
  $pdo = BioUBS\Conexao::getConn();
  $stmtUf = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
  $erros = [];
  
  // Validação UF do RG
  if ($uf_rg === '' || $uf_rg === null) {
    // opcional: mantém nulo quando não escolhido
    $uf_rg = null;
  } elseif (!preg_match('/^\\d+$/', (string)$uf_rg)) {
    $erros[] = 'Código de UF do RG inválido.';
  } else {
    $stmtUf->bindValue(':cd', (int)$uf_rg, PDO::PARAM_INT);
    $stmtUf->execute();
    if (!$stmtUf->fetchColumn()) {
      $erros[] = 'UF do RG não encontrada.';
    }
  }
  
  // Validação Estado do Endereço
  if ($estado === '' || $estado === null) {
    $erros[] = 'Estado do endereço é obrigatório.';
  } elseif (!preg_match('/^\\d+$/', (string)$estado)) {
    $erros[] = 'Código de Estado inválido.';
  } else {
    $stmtUf->bindValue(':cd', (int)$estado, PDO::PARAM_INT);
    $stmtUf->execute();
    if (!$stmtUf->fetchColumn()) {
      $erros[] = 'Estado não encontrado.';
    }
  }
  if ($erros) {
    $msg = implode("\n", $erros);
    echo "<script>window.alert('Erro de validação:\n$msg'); window.history.back();</script>";
    die;
  }

  $dados = ([
    'NOME'                  => $nome,
    'DATA_NASCIMENTO'       => $data_nascimento,
    'CPF'                   => $cpf,
    'CNS'                   => $cns,
    'NOME_MAE'              => $nome_mae,
    'TELEFONE_CELULAR'      => $telefone_celular,
    'CEP'                   => $cep,
    'ID_MUNICIPIO'          => $id_municipio,
    'ESTADO'                => $estado,
    'ENDERECO'              => $endereco,
    'NUMERO'                => $numero,
    'COMPLEMENTO'           => $complemento,
    'BAIRRO'                => $bairro,
    'SEXO'                  => $sexo,
    'RACA_COR'              => $raca_cor,
    'TELEFONE_RESIDENCIAL'  => $telefone_residencial,
    'TELEFONE_CONTATO'      => $telefone_contato,
    'EMAIL'                 => $email,
    'RG'                    => $rg,
    'UF_RG'                 => $uf_rg,
    'SSP'                   => $ssp,
    'LGPD_CONSENT'          => $lgpd_consent
  ]);

    $isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
// Verifica se o cadastro veio da recepção
$voltarParaRecepcao = isset($_POST['origem_recepcao']) && $_POST['origem_recepcao'] === '1';

if ($voltarParaRecepcao) {
  // Retorna para a recepção com mensagem de sucesso
  echo "<script>
  window.alert('✓ Paciente cadastrado com sucesso!\\n\\nVocê pode fazer o check-in agora.');
  window.location='../../pages/recepcao.php?cadastro_sucesso=1&cpf=" . urlencode($cpf) . "'
  </script>";
} else {
  // Retorna para a lista de pacientes (comportamento padrão)
  echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDePacientes.php'
  </script>";
}
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
