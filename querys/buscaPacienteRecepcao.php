<?php
/**
 * querys/buscaPacienteRecepcao.php
 * Busca robusta de paciente por CPF, Nome ou CNS para o módulo de recepção
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';
use BioUBS\UbsCrudAll;
use BioUBS\Idade;

$response = array();

if (isset($_POST['id_paciente']) && ctype_digit((string)$_POST['id_paciente'])) {
    // Busca direta por ID (usada pelo autocomplete TomSelect)
    try {
        $crud = new UbsCrudAll('cadastro_paciente');
        $resultado = $crud->buscaLivreParams('WHERE ID = :id LIMIT 1', [':id' => (int)$_POST['id_paciente']]);
        $paciente = $resultado[0] ?? null;
        if ($paciente) {
            // Calcula idade em anos usando classe Idade
            $idade = new Idade();
            $idade->setIdade($paciente['DATA_NASCIMENTO']);
            $idadeCalculada = $idade->IdadeAnos();
            $cpf = preg_replace('/[^0-9]/','',$paciente['CPF']);
            $cpf_formatado = (strlen($cpf)===11)? substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2) : $paciente['CPF'];
            $response = [
                'status'=>'success',
                'id'=>$paciente['ID'],
                'nome'=>$paciente['NOME'],
                'idade'=>$idadeCalculada,
                'nome_mae'=>$paciente['NOME_MAE'],
                'cpf'=>$cpf,
                'cpf_formatado'=>$cpf_formatado,
                'cns'=>$paciente['CNS'],
                'data_nascimento'=>date('d/m/Y', strtotime($paciente['DATA_NASCIMENTO']))
            ];
        } else {
            $response = ['status'=>'error','message'=>'Paciente não encontrado.'];
        }
    } catch (Exception $e) {
        $response = ['status'=>'error','message'=>'Erro no servidor: '.$e->getMessage()];
    }
} elseif (isset($_POST['termo_busca'])) {
    
    $termo_busca = trim($_POST['termo_busca']);
    
    if (empty($termo_busca)) {
        $response['status'] = 'error';
        $response['message'] = 'Termo de busca vazio.';
        echo json_encode($response);
        exit;
    }

    try {
        $crud = new UbsCrudAll('cadastro_paciente');
        $idade = new Idade();
        
        // Remove formatação de CPF/CNS (mantém apenas números)
        $termo_numeros = preg_replace('/[^0-9]/', '', $termo_busca);
        
        // Determina o tipo de busca
        $paciente = null;
        
        // 1. Tenta buscar por CPF (11 dígitos)
        if (strlen($termo_numeros) == 11) {
            $condicao = "WHERE REPLACE(REPLACE(CPF, '.', ''), '-', '') = :termo";
            $parametros = [':termo' => $termo_numeros];
            $resultados = $crud->buscaLivreParams($condicao, $parametros);
            
            if (!empty($resultados)) {
                $paciente = $resultados[0];
            }
        }
        
        // 2. Tenta buscar por CNS (15 dígitos)
        if (!$paciente && strlen($termo_numeros) == 15) {
            $condicao = "WHERE CNS = :termo";
            $parametros = [':termo' => $termo_numeros];
            $resultados = $crud->buscaLivreParams($condicao, $parametros);
            
            if (!empty($resultados)) {
                $paciente = $resultados[0];
            }
        }
        
        // 3. Busca por nome (se não for apenas números)
        if (!$paciente && !ctype_digit($termo_busca)) {
            // Busca com LIKE para encontrar nomes parciais
            $condicao = "WHERE NOME LIKE :termo";
            $parametros = [':termo' => '%' . $termo_busca . '%'];
            $resultados = $crud->buscaLivreParams($condicao, $parametros);
            
            if (!empty($resultados)) {
                // Se encontrar múltiplos, pega o primeiro (pode ser melhorado depois)
                $paciente = $resultados[0];
            }
        }

        // Retorna o resultado
        if ($paciente) {
            // Calcula idade em anos usando classe Idade
            $idade->setIdade($paciente['DATA_NASCIMENTO']);
            $idadeCalculada = $idade->IdadeAnos();
            
            // Formata CPF
            $cpf = $paciente['CPF'];
            $cpf_formatado = substr($cpf, 0, 3) . '.' . 
                           substr($cpf, 3, 3) . '.' . 
                           substr($cpf, 6, 3) . '-' . 
                           substr($cpf, 9, 2);
            
            $response['status'] = 'success';
            $response['id'] = $paciente['ID'];
            $response['nome'] = $paciente['NOME'];
            $response['idade'] = $idadeCalculada;
            $response['nome_mae'] = $paciente['NOME_MAE'];
            $response['cpf'] = $paciente['CPF'];
            $response['cpf_formatado'] = $cpf_formatado;
            $response['cns'] = $paciente['CNS'];
            $response['data_nascimento'] = date('d/m/Y', strtotime($paciente['DATA_NASCIMENTO']));
            
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Paciente não encontrado. Verifique os dados ou cadastre um novo paciente.';
        }

    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Erro no servidor: ' . $e->getMessage();
    }

} else {
    $response['status'] = 'error';
    $response['message'] = 'Nenhum termo de busca foi fornecido.';
}

echo json_encode($response);
?>
