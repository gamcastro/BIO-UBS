<?php
/**
 * actions/processar_checkin.php
 * Processa o check-in de um paciente e insere na fila de atendimento
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';
use BioUBS\UbsCrudAll;

// Inicia sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = array();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Sessão expirada. Faça login novamente.';
    echo json_encode($response);
    exit;
}

// Verifica se os dados foram enviados
if (!isset($_POST['id_paciente'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID do paciente não fornecido.';
    echo json_encode($response);
    exit;
}

$id_paciente = intval($_POST['id_paciente']);
$queixa_principal = trim($_POST['queixa_principal'] ?? '');
$id_unidade = $_SESSION['ubs_id'] ?? null;

// Valida a unidade
if (!$id_unidade) {
    $response['status'] = 'error';
    $response['message'] = 'Unidade não identificada na sessão.';
    echo json_encode($response);
    exit;
}

try {
    $crud = new UbsCrudAll('fila_atendimento');
    
    // Verifica se o paciente já está na fila (status diferente de FINALIZADO)
    $condicao = "WHERE ID_PACIENTE = :id_paciente AND STATUS != 'FINALIZADO'";
    $parametros = [':id_paciente' => $id_paciente];
    $jaEmFila = $crud->buscaLivreParams($condicao, $parametros);
    
    if (!empty($jaEmFila)) {
        $response['status'] = 'error';
        $response['message'] = 'Este paciente já está na fila de atendimento.';
        echo json_encode($response);
        exit;
    }
    
    // Prepara os dados para inserção
    $dados = [
        'ID_PACIENTE' => $id_paciente,
        'ID_UNIDADE' => $id_unidade,
        'QUEIXA_PRINCIPAL' => !empty($queixa_principal) ? $queixa_principal : null,
        'STATUS' => 'AGUARDANDO_TRIAGEM'
        // DATA_HORA_CHEGADA será preenchida automaticamente pelo banco (CURRENT_TIMESTAMP)
    ];
    
    // Insere na fila
    $resultado = $crud->inserir($dados);
    
    if ($resultado) {
        $response['status'] = 'success';
        $response['message'] = 'Check-in realizado com sucesso!';
        $response['id_fila'] = $resultado; // ID gerado na fila
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Erro ao inserir paciente na fila. Tente novamente.';
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Erro no servidor: ' . $e->getMessage();
}

echo json_encode($response);
?>
