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
        'MATRICULA', // <-- Adicionado (estava faltando no script antigo)
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
        // 'SENHA_HASH' foi REMOVIDA PROPOSITALMENTE.
        // Nunca atualize a senha em um formulário de edição de perfil,
        // a menos que seja uma tela específica de "Alterar Senha".
        // Manter isso aqui apagaria a senha do usuário.
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
        // Verifica se a coluna existe no que foi enviado via POST
        if (isset($_POST[$coluna])) {
            
            // Pega o valor do POST
            $valor = $_POST[$coluna];
            
            // Tratamento importante: Se o valor for uma string vazia (""),
            // convertemos para NULL. Isso evita erros ao salvar no banco
            // em campos que não aceitam string vazia (como 'date' ou 'int').
            $dados[$coluna] = ($valor !== '') ? $valor : null;
        }
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