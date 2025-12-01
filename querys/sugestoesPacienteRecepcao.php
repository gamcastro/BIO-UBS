<?php
/**
 * querys/sugestoesPacienteRecepcao.php
 * Retorna até 15 sugestões de pacientes para autocomplete (TomSelect)
 * Busca por fragmento de Nome, CPF (parcial) ou CNS
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';

use BioUBS\Conexao;

// Aceita POST (principal) ou GET para facilitar teste manual no navegador
$rawTerm = $_POST['term'] ?? $_GET['term'] ?? '';
$term = trim($rawTerm);
if ($term === '' || mb_strlen($term) < 2) { // exige ao menos 2 caracteres para reduzir carga
    echo json_encode([]);
    exit;
}

try {
    $pdo = Conexao::getConn();

    // Normaliza para busca por CPF (remove formatação) e mantém original para nome/CNS
    $digits = preg_replace('/[^0-9]/','',$term);
    $like = '%' . $term . '%';
    $likeDigits = '%' . $digits . '%';

    // Construímos condições para nome / cpf / cns
     $sql = "SELECT ID, NOME, CPF, CNS, DATA_NASCIMENTO, NOME_MAE
                FROM cadastro_paciente
                WHERE (NOME LIKE :like)
                    OR (REPLACE(REPLACE(CPF,'.',''),'-','') LIKE :likeDigits)
                    OR (CNS LIKE :likeDigits)
                ORDER BY NOME ASC
                LIMIT 15";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':like', $like, PDO::PARAM_STR);
    $stmt->bindValue(':likeDigits', $likeDigits, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($rows as $r) {
        $cpfLimpo = preg_replace('/[^0-9]/','', $r['CPF']);
        // Formata CPF se completo
        $cpfFormatado = (strlen($cpfLimpo) === 11)
            ? substr($cpfLimpo,0,3).'.'.substr($cpfLimpo,3,3).'.'.substr($cpfLimpo,6,3).'-'.substr($cpfLimpo,9,2)
            : $r['CPF'];
        $labelParts = [$r['NOME']];
        if ($cpfLimpo) { $labelParts[] = $cpfFormatado; }
        if (!empty($r['CNS'])) { $labelParts[] = 'CNS: '.$r['CNS']; }
        $out[] = [
            'id'          => (int)$r['ID'],
            'nome'        => $r['NOME'],
            'cpf'         => $cpfFormatado,
            'cpf_limpo'   => $cpfLimpo,
            'cns'         => $r['CNS'],
            'nome_mae'    => $r['NOME_MAE'],
            'data_nasc'   => $r['DATA_NASCIMENTO'],
            'label'       => implode(' | ',$labelParts)
        ];
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Em caso de erro, retorna JSON vazio
    echo json_encode([]);
}
