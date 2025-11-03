<?php
require_once  __DIR__ . '/../vendor/autoload.php'; // Autoloader
use BioUBS\Conexao;

$sqlId = "SELECT $camposIdBio FROM $tabelaIdBio WHERE ID = :idb";
$buscaId = Conexao::getConn()->prepare($sqlId);
$buscaId->bindParam(":idb", $id);
$buscaId->execute();