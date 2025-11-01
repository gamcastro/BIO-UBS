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
        'MUNICIPIO',
        'BAIRRO',
        'LOGRADOURO',
        'NUMERO',
        'COMPLEMENTO',
        'PONTO_REFERENCIA',
        // <-- MUDANÇA: Nome da coluna corrigido para bater com o banco
        'PASSWORD_HASH' 
    ];

    // 6. INSTANCIAR A SUPERCLASSE
    $objeto = new \BioUBS\UbsCrudAll($tabela, $colunasPermitidas);

    // 7. CONSTRUÇÃO DINÂMICA DO ARRAY DE DADOS
    $dados = []; // Array que será enviado para a superclasse
    
    foreach ($colunasPermitidas as $coluna) {
        // Verifica se a coluna existe no POST e NÃO é a senha
        // <-- MUDANÇA: Nome da coluna corrigido
        if (isset($_POST[$coluna]) && $coluna != 'PASSWORD_HASH') {
            
            $valor = $_POST[$coluna];
            // Tratamento: Se o valor for uma string vazia (""), convertemos para NULL.
            $dados[$coluna] = ($valor !== '') ? $valor : null;
        }
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

    // Limpa o CPF (remove pontos, traços, etc.)
    $cpfLimpo = preg_replace('/[^0-9]/', '', $dados['CPF']);
    
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