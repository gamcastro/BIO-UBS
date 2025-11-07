<?php
require_once  __DIR__ . '/../vendor/autoload.php'; // Autoloader
use BioUBS\Conexao;

$sql = "SELECT $camposBio FROM $tabelaBio $criteriosBio";
$busca = Conexao::getConn()->prepare($sql);
$busca->execute();