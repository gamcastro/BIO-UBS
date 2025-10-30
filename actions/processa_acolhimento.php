<?php
// Este arquivo serve para receber os dados do formulário de acolhimento
// e salvar no banco de dados.

// 1. Incluindo os arquivos que precisamos do nosso projeto
require_once __DIR__ . '/../vendor/autoload.php';   // Para usar as funções de Inserir, Alterar, etc.

$tabela = 'fila_atendimento'; //----tabela para a query
// 2. Verificando se os dados vieram do formulário
// Checamos se a página foi acessada usando o método POST e se o 'paciente_id' não está vazio.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paciente_id']) && !empty($_POST['paciente_id'])) {

    // 3. Pegando os dados que o formulário enviou
    $id_do_paciente = $_POST['paciente_id'];
    $queixa_do_paciente = $_POST['queixa_principal'];

    // 4. Preparando para salvar no banco de dados
    try {
        // Criamos um novo "objeto" para trabalhar com o banco,
        // e dizemos a ele que queremos usar a tabela 'fila_atendimento'.

        // $colunasPermitidas = 
        // [
        //   'ID_PACIENTE',
        //   'QUEIXA_PRINCIPAL'
        // ]; //--nao informar ID chave primaria

        $crud = new UbsCrudAll($tabela);

        // Criamos um array, que é como uma lista, com os dados que queremos inserir.
        // A 'chave' do array (ex: 'ID_PACIENTE') deve ser igual ao nome da coluna na tabela.
        $dadosParaSalvar = [
            'ID_PACIENTE' => $id_do_paciente,
            'QUEIXA_PRINCIPAL' => $queixa_do_paciente
        ];

        // 5. Inserindo os dados no banco
        // Chamamos a função 'inserir' da nossa classe e passamos os dados para ela.
        $crud->inserir($dadosParaSalvar);

        // Se a inserção deu certo, o código continua para o redirecionamento abaixo.

    } catch (Exception $e) {
        // Se acontecer algum erro na hora de salvar, podemos ver o erro descomentando a linha abaixo.
        // die("Ocorreu um erro: " . $e->getMessage());
    }

} else {
    // Se alguém tentar abrir este arquivo diretamente no navegador sem enviar o formulário,
    // apenas redirecionamos para a página inicial.
}


// 6. Redirecionando de volta para a página inicial
// Depois de salvar os dados, o usuário é enviado de volta para a 'index.php'.

echo "<script>
  window.alert('Paciente enviado para a triagem com sucesso!'); 
  </script>";
  
header('Location: index.php');
exit(); // Esta função garante que o script pare de rodar aqui.

?>