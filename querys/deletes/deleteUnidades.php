<?php
/**
 * deleteUnidades.php
 * * Script incluído por 'cadastroDeUnidades.php'
 * * quando o botão name="excluir" é submetido.
 */

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoloader
use BioUBS\UbsCrudAll;

// Evita que o script seja acessado diretamente
if (!isset($_POST['excluir'])) {
    die('Acesso negado.');
}

//-------ID -----------
$id = $_POST['id'];
//---------------------

//------------------apagar registro de unidade---------

$tabela = 'cadastro_unidade'; //----tabela para a query

// A classe UbsCrudAll já foi carregada pela página 'pai' (cadastroDeUnidades.php)
$objeto = new \BioUBS\UbsCrudAll($tabela);

//---funcao deletar por id (seguindo o seu padrão)
$deleteUbs = $objeto->deleteId($id); 

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
echo "<script>
    window.alert('Registro apagado com sucesso!');
    window.location='cadastroDeUnidades.php'
</script>";

//----------------------------------------------------------  

die; //----- se entrar para o código aqui
?>