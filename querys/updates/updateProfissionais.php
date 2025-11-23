<?php
/**
 * Script para processar a ATUALIZAÇÃO (Update) de um profissional.
 * Recebe os dados do formulário modalEdCadastroDeProfissional.php.
 */

// Iniciamos a sessão (boa prática para futuras mensagens de status)


// Carrega o autoloader do Composer (que deve carregar a classe UbsCrudAll)
require_once __DIR__ . '/../../vendor/autoload.php'; 

use BioUBS\UbsCrudAll;

// --- VERIFICAÇÃO INICIAL ---
// Apenas executa se o formulário foi enviado (name="editar" do botão Salvar)
if (isset($_POST['editar'])) {

    // 1. DADOS DE IDENTIFICAÇÃO
    // O ID do registro que queremos atualizar
    $id = $_POST['id']; 
    // O nome da tabela
    $tabela = 'cadastro_profissional';

    // 2. WHITELIST DE COLUNAS PERMITIDAS
    // Define quais colunas do banco de dados este script tem permissão para atualizar.
    // Os nomes aqui DEVEM ser idênticos às colunas do banco e aos atributos 'name' do formulário.
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
        'PONTO_REFERENCIA'
    ];

    // 3. INSTANCIAR A SUPERCLASSE
    // Passamos a tabela e a whitelist de colunas para o construtor
    $objeto = new UbsCrudAll($tabela, $colunasPermitidas);

    // 4. CONSTRUÇÃO DINÂMICA DO ARRAY DE DADOS
    // Esta é a "mágica" da superclasse.
    // Em vez de pegar $_POST por $_POST, lemos a whitelist
    // e puxamos apenas os dados permitidos que vieram do formulário.
    
    $dados = []; // Array que será enviado para a superclasse
    
    foreach ($colunasPermitidas as $coluna) {
        if (isset($_POST[$coluna])) {
            $valor = $_POST[$coluna];
            $dados[$coluna] = ($valor !== '') ? $valor : null;
        }
    }

    // Mapeia ID do município a partir do campo "municipio" vindo do TomSelect
    if (isset($_POST['municipio']) && $_POST['municipio'] !== '') {
        $idMun = (int)$_POST['municipio'];
        if ($idMun > 0) {
            $pdoValMun = BioUBS\Conexao::getConn();
            $stmtMun = $pdoValMun->prepare('SELECT 1 FROM ibge_municipios WHERE CD_MUNICIPIO = :m LIMIT 1');
            $stmtMun->bindValue(':m', $idMun, PDO::PARAM_INT);
            $stmtMun->execute();
            if ($stmtMun->fetchColumn()) {
                $dados['ID_MUNICIPIO'] = $idMun;
            } else {
                $dados['ID_MUNICIPIO'] = null;
            }
        } else {
            $dados['ID_MUNICIPIO'] = null;
        }
    } else {
        $dados['ID_MUNICIPIO'] = null;
    }

    // Sanitização de CPF (somente números) antes de persistir
    if (isset($dados['CPF'])) {
        if (!function_exists('sanitize_cpf')) { require_once __DIR__ . '/../../includes/functions.php'; }
        $dados['CPF'] = sanitize_cpf($dados['CPF']);
    }

    // Normalização do Nome Completo mantendo preposições em minúsculo
    if (isset($dados['NOME_COMPLETO']) && $dados['NOME_COMPLETO'] !== null) {
        if (!function_exists('normalize_nome')) { require_once __DIR__ . '/../../includes/functions.php'; }
        $dados['NOME_COMPLETO'] = normalize_nome((string)$dados['NOME_COMPLETO']);
    }

    // Sanitização do TELEFONE e aplicação de DDI 55, quando aplicável
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
    if (array_key_exists('SEXO', $dados)) {
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
            $dados['SEXO'] = null;
        }
    }

    // Validação específica para códigos de UF no update
    $ufsCodigos = ['ESTADO_EMISSOR_CONSELHO','ESTADO_ENDERECO'];
    $errosUf = [];
    $pdo = BioUBS\Conexao::getConn();
    $stmtUf = $pdo->prepare("SELECT 1 FROM ibge_ufs WHERE CD_UF = :cd LIMIT 1");
    foreach ($ufsCodigos as $campoUf) {
        if (array_key_exists($campoUf, $dados) && $dados[$campoUf] !== null && $dados[$campoUf] !== '') {
            if (!preg_match('/^\d+$/', (string)$dados[$campoUf])) {
                $errosUf[] = "Código de UF inválido para $campoUf.";
                continue;
            }
            $stmtUf->bindValue(':cd', (int)$dados[$campoUf], PDO::PARAM_INT);
            $stmtUf->execute();
            if (!$stmtUf->fetchColumn()) {
                $errosUf[] = "UF não encontrada para $campoUf.";
            }
        }
        // Campo opcional: se nulo/vazio, não valida nem gera erro
    }
    if ($errosUf) {
        $msg = implode("\n", $errosUf);
        echo "<script>window.alert('Erro de validação: \n$msg'); window.history.back();</script>";
        die;
    }

    // 5. EXECUTAR A ATUALIZAÇÃO
    // Chama o método 'atualizar', passando o ID do registro e o array de dados
    $updateUbs = $objeto->atualizar($id, $dados); 

    // 6. RESPOSTA AO USUÁRIO
    // Verifica se a superclasse retornou sucesso (true)
    if ($updateUbs) {
        // Sucesso: Alerta o usuário e redireciona para a página de listagem
        echo "<script>
            window.alert('Cadastro alterado com sucesso!');
            window.location='cadastroDeProfissionais.php';
        </script>";
    } else {
        // Falha: Alerta o usuário e o mantém na página anterior (para corrigir)
        echo "<script>
            window.alert('Erro ao alterar o cadastro. Verifique os dados e tente novamente.');
            window.history.back(); // Volta para a página anterior
        </script>";
    }

    die; // Encerra o script após a operação

} else {
    // Se alguém tentar acessar este arquivo diretamente pela URL (sem ser via POST)
    // redireciona de volta para a listagem.
    header('Location: cadastroDeProfissionais.php');
    die;
}
?>