<?php
echo "<h1>Verificação do Ambiente do Servidor Web (XAMPP)</h1>";

echo "<b>Versão do PHP (Servidor Web):</b> " . PHP_VERSION . "<br>";

if (defined('PASSWORD_ARGON2ID')) {
    echo "<b>Suporte a Argon2id:</b> <span style='color:green; font-weight:bold;'>SIM, INSTALADO</span> (PHP 7.3+)<br>";
} else {
    echo "<b>Suporte a Argon2id:</b> <span style='color:red; font-weight:bold;'>NÃO, FALTA SUPORTE!</span> (Provavelmente PHP < 7.3)<br>";
}

echo "<hr><h3>Opções de Hash Disponíveis:</h3>";
echo "<pre>";
print_r(password_algos());
echo "</pre>";
?>