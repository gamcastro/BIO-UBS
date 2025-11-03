<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';
use BioUBS\UbsCrudAll;

$response = array();

if (isset($_POST['termo_busca'])) {
    
    //  remove qualquer formatação que ainda possa existir
    $termo_busca = preg_replace('/[^0-9]/', '', $_POST['termo_busca']);

    try {
    $crud = new UbsCrudAll('cadastro_paciente');

       
        //  REPLACE() duas vezes para limpar a coluna CPF antes de comparar
        $condicao = "WHERE REPLACE(REPLACE(CPF, '.', ''), '-', '') = :termo";
        
        $parametros = [':termo' => $termo_busca];

        $resultados = $crud->buscaLivreParams($condicao, $parametros, "ID, NOME, DATA_NASCIMENTO");
        
        $paciente = $resultados[0] ?? null;

        if ($paciente) {
            $response['status'] = 'success';
            $response['id'] = $paciente['ID'];
            $response['nome'] = $paciente['NOME'];
            
            $data_nasc = new DateTime($paciente['DATA_NASCIMENTO']);
            $response['data_nascimento'] = $data_nasc->format('d/m/Y');

        } else {
            $response['status'] = 'error';
            $response['message'] = 'Paciente não encontrado para o CPF informado.';
        }

    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Ocorreu um erro no servidor: ' . $e->getMessage();
    }

} else {
    $response['status'] = 'error';
    $response['message'] = 'Nenhum termo de busca foi fornecido.';
}

echo json_encode($response);
?>