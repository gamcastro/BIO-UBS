<?php
/**
 * includes/functions.php
 * - Conexão PDO centralizada (utf8mb4)
 * - Descoberta de IP do cliente
 * - Cabeçalhos básicos de segurança (X-Frame, nosniff, etc.)
 */
declare(strict_types=1);

function db_conn(): PDO {
    //--------------Ajuste host/db/user/pass conforme ambiente
    $dsn = "mysql:host=localhost;dbname=bio_ubs;charset=utf8mb4";
    $user = "root";
    $pass = "";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    return new PDO($dsn, $user, $pass, $opt);
}

function get_ip(): string {
    /*----------------------Em ambiente com proxy, 
    considerar HTTP_X_FORWARDED_FOR com cautela
    */

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

}

function set_secure_headers(): void {
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    header("Permissions-Policy: geolocation=()");
}
