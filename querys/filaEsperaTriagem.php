<?php
/**
 * querys/filaEsperaTriagem.php
 * Retorna a lista de pacientes aguardando triagem na unidade
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
    $response['message'] = 'Sessão expirada.';
    echo json_encode($response);
    exit;
}

$id_unidade = $_SESSION['ubs_id'] ?? null;

// Fallback para recuperar unidade se não estiver na sessão
if (!$id_unidade) {
    try {
        $pdoTmp = \BioUBS\Conexao::getConn();
        if (!empty($_SESSION['ubs_nome'])) {
            $stmt = $pdoTmp->prepare("SELECT ID FROM cadastro_unidade WHERE NOME = :nome LIMIT 1");
            $stmt->execute([':nome' => $_SESSION['ubs_nome']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['ID'])) {
                $id_unidade = (int)$row['ID'];
                $_SESSION['ubs_id'] = $id_unidade;
            }
        }
        if (!$id_unidade) {
            $stmt = $pdoTmp->query("SELECT ID, NOME FROM cadastro_unidade ORDER BY ID ASC LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['ID'])) {
                $id_unidade = (int)$row['ID'];
                $_SESSION['ubs_id'] = $id_unidade;
                if (empty($_SESSION['ubs_nome'])) {
                    $_SESSION['ubs_nome'] = $row['NOME'];
                }
            }
        }
    } catch (Exception $e) {
        // Ignora erro e segue para resposta padrão
    }
}

if (!$id_unidade) {
    $response['status'] = 'error';
    $response['message'] = 'Unidade não identificada.';
    echo json_encode($response);
    exit;
}

try {
    $crud = new UbsCrudAll('fila_atendimento');
    
    // Busca todos os pacientes aguardando triagem, ordenados por hora de chegada
    $condicao = "WHERE fila_atendimento.ID_UNIDADE = :id_unidade 
                 AND fila_atendimento.STATUS = 'AGUARDANDO_TRIAGEM'
                 ORDER BY fila_atendimento.DATA_HORA_CHEGADA ASC";
    
    $parametros = [':id_unidade' => $id_unidade];
    
    // Faz JOIN com a tabela de pacientes para pegar o nome
    $sql = "SELECT 
                fila_atendimento.ID,
                fila_atendimento.ID_PACIENTE,
                fila_atendimento.QUEIXA_PRINCIPAL,
                fila_atendimento.DATA_HORA_CHEGADA,
                cadastro_paciente.NOME as NOME_PACIENTE
            FROM fila_atendimento
            INNER JOIN cadastro_paciente ON fila_atendimento.ID_PACIENTE = cadastro_paciente.ID
            WHERE fila_atendimento.ID_UNIDADE = :id_unidade 
            AND fila_atendimento.STATUS = 'AGUARDANDO_TRIAGEM'
            ORDER BY fila_atendimento.DATA_HORA_CHEGADA ASC";
    
    // Executa query customizada
    $pdo = \BioUBS\Conexao::getConn();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formata os resultados
    $fila = [];
    foreach ($resultados as $item) {
        $fila[] = [
            'id' => $item['ID'],
            'id_paciente' => $item['ID_PACIENTE'],
            'nome_paciente' => $item['NOME_PACIENTE'],
            'queixa_principal' => $item['QUEIXA_PRINCIPAL'],
            'data_hora_chegada' => $item['DATA_HORA_CHEGADA']
        ];
    }
    
    $response['status'] = 'success';
    $response['fila'] = $fila;
    $response['total'] = count($fila);
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Erro no servidor: ' . $e->getMessage();
}

echo json_encode($response);
?>
