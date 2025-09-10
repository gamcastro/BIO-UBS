<?php
$sqlId = "SELECT $camposIdBio FROM $tabelaIdBio WHERE ID = :idb";
$buscaId = Conexao::getConn()->prepare($sqlId);
$buscaId->bindParam(":idb", $id);
$buscaId->execute();