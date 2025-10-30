<?php
/**
 * logout.php
 * Destroi a sessão e remove (e invalida) o cookie remember-me no banco.
 */
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use BioUBS\Conexao ;

// $db = db_conn();
$db = Conexao::getConn();
logout_user($db); //---------------remove token do banco e cookie
header('Location: login.php');
exit;
