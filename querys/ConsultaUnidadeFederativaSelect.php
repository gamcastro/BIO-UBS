<?php
require_once __DIR__ .'/../vendor/autoload.php';
use BioUBS\Conexao ;

// Permite passar uma variável $selectedUf do arquivo que inclui este script.
// Exemplo antes do require: $selectedUf = '21';
$selectedUf = isset($selectedUf) ? (string)$selectedUf : '';

$sqlUf = "SELECT * FROM ibge_ufs ORDER BY DS_UF_SIGLA";
$buscaUf = Conexao::getConn()->prepare($sqlUf);
$buscaUf->execute();
?>

	<?php
	while($rowsUf = $buscaUf->fetch(PDO::FETCH_ASSOC)){
	            $uf = $rowsUf['DS_UF_SIGLA'];
	            $cd_uf = $rowsUf['CD_UF'];
	            $ds_uf_nome = $rowsUf['DS_UF_NOME'];
	?>
    
	<?php $isSel = ((string)$cd_uf === (string)$selectedUf) ? ' selected' : ''; ?>
	<option value="<?=$cd_uf?>"<?=$isSel?>><?=$uf . " - " . $ds_uf_nome?></option>

	<?php 
	}
	?>  
	          
