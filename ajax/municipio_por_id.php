<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../class/Conexao.php';
use BioUBS\Conexao;
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if(!preg_match('/^\d{1,7}$/', $id)) { echo json_encode([]); exit; }
try {
    $pdo = Conexao::getConn();
    $stmt = $pdo->prepare('SELECT CD_MUNICIPIO AS id, MUNICIPIO AS nome FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){ echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); } else { echo json_encode([]); }
} catch(Exception $e){ error_log('Erro municipio_por_id: '.$e->getMessage()); echo json_encode([]); }
