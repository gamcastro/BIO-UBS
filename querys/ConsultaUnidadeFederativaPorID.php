<?php
require_once '../vendor/autoload.php'; // Autoloader
use BioUBS\Conexao;

$sqlUfId = "SELECT * FROM ibge_ufs WHERE CD_UF = :cd_uf_rgb";
$buscaUfId = Conexao::getConn()->prepare($sqlUfId);
$buscaUfId->bindParam(":cd_uf_rgb", $cd_uf_rg);
$buscaUfId->execute();
          
