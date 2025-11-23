<?php
/**
 * Script para processar o CADASTRO (Insert) de um novo profissional.
 * Recebe os dados do formulário modalCadastroDeProfissional.php.
 */

// 1. Importar a classe com seu Namespace

require_once __DIR__ . '/../../vendor/autoload.php'; 
use BioUBS\UbsCrudAll ;

// 2. Carrega o autoloader do Composer


// 3. VERIFICAÇÃO INICIAL
// Apenas executa se o formulário foi enviado (name="cadastrar" do botão Salvar)
if (isset($_POST['salvar'])) {

    // 4. DADOS DE IDENTIFICAÇÃO
    $tabela = 'cadastro_profissional';

    // 5. WHITELIST DE COLUNAS PERMITIDAS
    // Define quais colunas do banco de dados este script tem permissão para inserir.
    $colunasPermitidas = [
        'NOME_COMPLETO',
        'MATRICULA', 
        'CPF',
        'CNS_PROFISSIONAL',
        'DATA_NASCIMENTO',
        'SEXO',
        'PERFIL',
        'EMAIL',
        'TELEFONE',
        'CONSELHO_CLASSE',
        'REGISTRO_CONSELHO',
        'ESTADO_EMISSOR_CONSELHO',
        'CEP',
        'ESTADO_ENDERECO',
        'ID_MUNICIPIO',
        'BAIRRO',
        'LOGRADOURO',
        'NUMERO',
        'COMPLEMENTO',
        'PONTO_REFERENCIA',
        // <-- MUDANÇA: Nome da coluna corrigido para bater com o banco
        'PASSWORD_HASH' 
    ];

    // 6. INSTANCIAR A SUPERCLASSE
    $objeto = new UbsCrudAll($tabela, $colunasPermitidas);

    // 7. CONSTRUÇÃO DINÂMICA DO ARRAY DE DADOS
    $dados = []; // Array que será enviado para a superclasse
    
    foreach ($colunasPermitidas as $coluna) {
        if (isset($_POST[$coluna]) && $coluna != 'PASSWORD_HASH') {
            $valor = $_POST[$coluna];
            $dados[$coluna] = ($valor !== '') ? $valor : null;
        }
    }

    // Município (ID) vindo do input name="municipio" via TomSelect
    $idMun = null;
    if (isset($_POST['municipio']) && $_POST['municipio'] !== '') {
        $idMun = (int)$_POST['municipio'];
        if ($idMun <= 0) { $idMun = null; }
    }
    // Valida existência do município (opcional se não selecionado)
    if ($idMun) {
        $pdoValMun = BioUBS\Conexao::getConn();
        $stmtMun = $pdoValMun->prepare('SELECT 1 FROM ibge_municipios WHERE CD_MUNICIPIO = :m LIMIT 1');
        $stmtMun->bindValue(':m', $idMun, PDO::PARAM_INT);
        $stmtMun->execute();
        if ($stmtMun->fetchColumn()) {
            $dados['ID_MUNICIPIO'] = $idMun;
        } else {
            $dados['ID_MUNICIPIO'] = null; // município inválido -> ignora
        }
    } else {
        $dados['ID_MUNICIPIO'] = null;
    }

    // Validação específica para códigos de UF (devem existir na tabela ibge_ufs)
    $ufsCodigos = ['ESTADO_EMISSOR_CONSELHO','ESTADO_ENDERECO'];
    $errosUf = [];
    $pdo = BioUBS\Conexao::getConn();
    $stmtUf = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
    foreach ($ufsCodigos as $campoUf) {
        if (isset($dados[$campoUf]) && $dados[$campoUf] !== null) {
            // aceita apenas dígitos
            if (!preg_match('/^\d+$/', (string)$dados[$campoUf])) {
                $errosUf[] = "Código de UF inválido para $campoUf.";
                continue;
            }
            $stmtUf->bindValue(':cd', (int)$dados[$campoUf], PDO::PARAM_INT);
            $stmtUf->execute();
            if (!$stmtUf->fetchColumn()) {
                $errosUf[] = "UF não encontrada para $campoUf.";
            }
        } else {
            // Campo é opcional; se não enviado (null), apenas ignoramos
            // Não gera erro se usuário não selecionou UF
        }
    }
    if ($errosUf) {
        $msg = implode("\n", $errosUf);
        echo "<script>window.alert('Erro de validação: \n$msg'); window.history.back();</script>";
        die;
    }

    // 8. GERENCIAMENTO DA SENHA PADRÃO (COM ARGON2ID)
    // O formulário não envia senha, então criamos uma senha padrão (o CPF).
    
    if (empty($dados['CPF'])) {
        echo "<script>
            window.alert('Erro: O campo CPF é obrigatório para gerar a senha inicial.');
            window.history.back();
        </script>";
        die;
    }

    // Normalização do Nome Completo mantendo preposições em minúsculo
    if (isset($dados['NOME_COMPLETO']) && $dados['NOME_COMPLETO'] !== null) {
        if (!function_exists('normalize_nome')) { require_once __DIR__ . '/../../includes/functions.php'; }
        $dados['NOME_COMPLETO'] = normalize_nome((string)$dados['NOME_COMPLETO']);
    }

    // Limpa o CPF (remove pontos, traços, etc.) para senha e para persistência
    if (!function_exists('sanitize_cpf')) { require_once __DIR__ . '/../../includes/functions.php'; }
    $cpfLimpo = sanitize_cpf($dados['CPF'] ?? '');
    $dados['CPF'] = $cpfLimpo;

    // Sanitiza TELEFONE (mantém apenas dígitos) e aplica DDI 55 se vier sem
    if (isset($dados['TELEFONE']) && $dados['TELEFONE'] !== null) {
        // Armazenar apenas dígitos (não prefixar DDI '55').
        $telLimpo = preg_replace('/\D+/', '', (string)$dados['TELEFONE']);
        $dados['TELEFONE'] = $telLimpo;
    }

    // Sanitiza CEP (mantém apenas dígitos)
    if (isset($dados['CEP']) && $dados['CEP'] !== null) {
        $dados['CEP'] = preg_replace('/\D+/', '', (string)$dados['CEP']);
    }

    // Sanitiza CNS do profissional: armazena apenas dígitos
    if (isset($dados['CNS_PROFISSIONAL']) && $dados['CNS_PROFISSIONAL'] !== null) {
        $cnsLimpo = preg_replace('/\D+/', '', (string)$dados['CNS_PROFISSIONAL']);
        $dados['CNS_PROFISSIONAL'] = $cnsLimpo !== '' ? $cnsLimpo : null;
    }

    // Normaliza o campo SEXO para os valores do ENUM do banco: 'Masculino' / 'Feminino'
    $sexoValor = null;
    if (isset($dados['SEXO'])) {
        $sexoValor = $dados['SEXO'];
    } elseif (isset($_POST['sexo'])) {
        $sexoValor = $_POST['sexo'];
    }
    if ($sexoValor !== null) {
        $s = mb_strtolower(trim((string)$sexoValor), 'UTF-8');
        if ($s === 'm' || mb_stripos($s, 'mascul') !== false) {
            $dados['SEXO'] = 'Masculino';
        } elseif ($s === 'f' || mb_stripos($s, 'femin') !== false) {
            $dados['SEXO'] = 'Feminino';
        } else {
            // valor inesperado -> grava NULL para evitar erro de enum
            $dados['SEXO'] = null;
        }
    }
    
    // <-- MUDANÇA: Usando Argon2id (como solicitado) e salvando na coluna correta 'PASSWORD_HASH'
    $dados['PASSWORD_HASH'] = password_hash($cpfLimpo, PASSWORD_ARGON2ID);


    // 9. EXECUTAR A INCLUSÃO
    $insertUbs = $objeto->inserir($dados); 

    // 10. RESPOSTA AO USUÁRIO
    if ($insertUbs) {
        // Sucesso: Informa ao admin a senha padrão.
        echo "<script>
            window.alert('Cadastro efetuado com sucesso! A senha inicial é o CPF (somente números).');
            window.location='cadastroDeProfissionais.php';
        </script>";
    } else {
        // Falha (Ex: CPF/Matrícula duplicado)
        echo "<script>
            window.alert('Erro ao efetuar o cadastro. Verifique se o CPF ou a Matrícula já existem.');
            window.history.back();
        </script>";
    }

    die; 

} else {
    // Acesso direto ao arquivo
    header('Location: cadastroDeProfissionais.php');
    die;
}
?>