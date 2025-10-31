<?php
use BioUBS\Conexao ;

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

		<option value="<?=$cd_uf?>"><?=$uf . " - " . $ds_uf_nome?></option>

	<?php 
	}
	?>  
	          
