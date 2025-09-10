<?php
$sql = "SELECT $camposBio FROM $tabelaBio $criteriosBio";
$busca = Conexao::getConn()->prepare($sql);
$busca->execute();