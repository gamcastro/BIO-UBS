<?php
/**
 * logout.php
 * Destroi a sessão e remove (e invalida) o cookie remember-me no banco.
 */
session_start();
require_once('../../ConexaoUbs.php');
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_helper.php';

// $db = db_conn();
$db = Conexao::getConn();
logout_user($db); //---------------remove token do banco e cookie
header('Location: login.php');
exit;
