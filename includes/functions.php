<?php
/**
 * includes/functions.php
 * - Conexão PDO centralizada (utf8mb4)
 * - Descoberta de IP do cliente
 * - Cabeçalhos básicos de segurança (X-Frame, nosniff, etc.)
 */
declare(strict_types=1);

function db_conn(): PDO {
    $config = require __DIR__ . '/../config.php';
    $dsn = "mysql:host={$config['local']};dbname={$config['banco']};charset=utf8mb4";
    $user = $config['usuario'];
    $pass = $config['senha'];
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

/**
 * Sanitiza CPF: mantém apenas dígitos (11 números)
 */
function sanitize_cpf(?string $cpf): string {
    $cpf = (string)($cpf ?? '');
    return preg_replace('/\D+/', '', $cpf) ?? '';
}

/**
 * Formata CPF para 000.000.000-00 se tiver 11 dígitos; caso contrário retorna o original
 */
function format_cpf(?string $cpf): string {
    $digits = sanitize_cpf($cpf);
    if (strlen($digits) !== 11) {
        return (string)$cpf;
    }
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/','${1}.${2}.${3}-${4}', $digits) ?? (string)$cpf;
}
