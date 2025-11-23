<?php
header('Content-Type: application/json; charset=utf-8');

// 
require_once __DIR__ . '/../class/Conexao.php';

use BioUBS\Conexao;

$uf = isset($_GET['uf']) ? trim($_GET['uf']) : '';
$q  = isset($_GET['q']) ? trim($_GET['q']) : '';

// Validação: aceita UF como sigla (ex: MA) ou código numérico (ex: 21)
if (!preg_match('/^([A-Z]{2}|\d{1,3})$/i', $uf)) {
    echo json_encode([]);
    exit;
}

try {
    $pdo = Conexao::getConn();

    // Seleciona as colunas renomeando para 'id' e 'nome' para facilitar o JSON
    // REMOVIDO: "LIMIT 100" -> Para permitir carregar a lista completa do estado
    $sql = 'SELECT CD_MUNICIPIO AS id, MUNICIPIO AS nome 
            FROM ibge_municipios 
            WHERE CD_UF = :uf';

    // Se houver termo de busca, adiciona o filtro
    if ($q !== '') {
        $sql .= ' AND MUNICIPIO LIKE :q';
    }

    // Ordenação alfabética é essencial para a lista completa
    $sql .= ' ORDER BY MUNICIPIO ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uf', $uf);

    if ($q !== '') {
        // Alterado para "%...%" (contém) para melhorar a experiência de busca
        // Ex: digitar "santos" encontra "Todos os Santos"
        $stmt->bindValue(':q', '%' . $q . '%');
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Como já usamos "AS id" e "AS nome" no SQL, o array já vem pronto.
    // Mas vamos iterar para garantir que venha limpo, sem índices numéricos duplicados.
    $out = [];
    foreach ($rows as $r) {
        $out[] = [
            'id'   => $r['id'], 
            'nome' => $r['nome']
        ];
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    // Log de erro no servidor (boa prática: não expor o erro detalhado ao usuário)
    error_log('Erro em ajax/municipios.php: ' . $e->getMessage());
    
    // Retorna array vazio para o TomSelect não quebrar
    echo json_encode([]);
}
?>