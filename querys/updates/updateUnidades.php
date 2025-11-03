<?php
/**
 * updateUnidades.php
 * * Este script é incluído por 'cadastroDeUnidades.php' quando o formulário
 * de edição (name="editar") é submetido.
 * * Ele usa a classe UbsCrudAll (já carregada pelo 'cadastroDeUnidades.php')
 * para atualizar os dados da unidade no banco.
 */

require_once  __DIR__ . '/../../vendor/autoload.php'; // Autoloader
use BioUBS\UbsCrudAll ;

// Evita que o script seja acessado diretamente
if (!isset($_POST['editar'])) {
    die('Acesso negado.');
}

//-------ID -----------
$id = $_POST['id']; 
//---------------------


/*-------campos do formulário via post -------*/
$nome = $_POST['nome'];
$cnes = $_POST['cnes'];
$cnpj = $_POST['cnpj'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$complemento = $_POST['complemento'];
$municipio = $_POST['municipio'];
$estado_endereco = $_POST['estado_endereco']; // Este é o ID do estado (ex: 21)


//------------------alterando os dados da unidade---------
$tabela = 'cadastro_unidade'; //--- nome da tabela

// Lista de colunas permitidas para o update
// (Baseado nos campos do modal e no seu BD)
$colunasPermitidas = [
    'NOME',
    'CNES',
    'CNPJ',
    'TELEFONE',
    'CEP',
    'LOGRADOURO',
    'NUMERO',
    'BAIRRO',
    'COMPLEMENTO',
    'MUNICIPIO',
    'ESTADO' // No BD, a coluna se chama ESTADO
];

// A classe UbsCrudAll já foi instanciada em cadastroDeUnidades.php
// mas se o seu padrão for instanciar aqui, garanta que o autoloader
// foi chamado antes (o que 'cadastroDeUnidades.php' já faz).
// Para manter o padrão do seu template, vamos instanciar aqui.


$objeto = new UbsCrudAll($tabela, $colunasPermitidas);

// Mapeia os dados do $_POST para os nomes das colunas do BD
$dados = [
    'NOME'        => $nome,
    'CNES'        => $cnes,
    'CNPJ'        => $cnpj,
    'TELEFONE'    => $telefone,
    'CEP'         => $cep,
    'LOGRADOURO'  => $logradouro,
    'NUMERO'      => $numero,
    'BAIRRO'      => $bairro,
    'COMPLEMENTO' => $complemento,
    'MUNICIPIO'   => $municipio,
    'ESTADO'      => $estado_endereco // Mapeia o 'estado_endereco' do form para a coluna 'ESTADO'
];


$updateUbs = $objeto->atualizar($id, $dados); //---funcao atualizar com 2 parametros

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
// Este script recarrega a página de listagem, exibindo os dados atualizados
echo "<script>
    window.alert('Cadastro da Unidade alterado com sucesso!');
    window.location='cadastroDeUnidades.php'
</script>";

//----------------------------------------------------------  

die; //----- Para o script aqui após a lógica de update
?>