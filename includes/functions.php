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

/**
 * Normaliza nomes de pessoas mantendo preposições em minúsculo.
 * Preposições tratadas: da, das, de, do, dos, e
 * - Remove espaços duplicados
 * - Converte demais partes para Title Case
 */
function normalize_nome(?string $nome): string {
    $nome = trim(preg_replace('/\s+/u', ' ', mb_strtolower((string)$nome, 'UTF-8')));
    if ($nome === '') { return ''; }
    $preps = ['da','das','de','do','dos','e'];
    $parts = preg_split('/\s+/u', $nome) ?: [];
    foreach ($parts as $i => $p) {
        if ($i === 0 || !in_array($p, $preps, true)) {
            // Capitaliza primeiro caractere, mantém resto minúsculo
            $parts[$i] = mb_convert_case($p, MB_CASE_TITLE, 'UTF-8');
        } else {
            // Preposição permanece minúscula
            $parts[$i] = $p;
        }
    }
    return implode(' ', $parts);
}

/**
 * Formata telefones brasileiros (apenas dígitos) para:
 * 10 dígitos: (XX) XXXX-XXXX
 * 11 dígitos: (XX) XXXXX-XXXX
 */
function format_telefone(?string $fone): string {
    $digits = preg_replace('/\D+/', '', (string)($fone ?? '')) ?? '';
    if (strlen($digits) === 10) {
        // (DD)DDDD-DDDD
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1)$2-$3', $digits) ?? (string)$fone;
    }
    if (strlen($digits) === 11) {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $digits) ?? (string)$fone;
    }
    return (string)$fone; // retorna original se tamanho inesperado
}
