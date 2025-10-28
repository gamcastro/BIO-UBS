<?php
/**
 * includes/auth_helper.php
 * - Controle de tentativas (anti brute-force)
 * - Lembrar-me com selector/validator (hash no banco, cookie httpOnly)
 * - Logout com limpeza do token
 */

require_once('../../ConexaoUbs.php');
require_once __DIR__ . '/functions.php';

$db = Conexao::getConn();

function increment_attempt(PDO $db, string $ip): void {
    $stmt = $db->prepare("INSERT INTO login_attempts (ip, attempts, last_attempt)
                          VALUES (:ip, 1, NOW())
                          ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()");
    $stmt->execute([':ip' => $ip]);
}

function clear_attempts(PDO $db, string $ip): void {
    $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip = :ip");
    $stmt->execute([':ip' => $ip]);
}

function is_locked(PDO $db, string $ip, int $limit = 5, int $minutes = 15): bool {
    $stmt = $db->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip = :ip");
    $stmt->execute([':ip' => $ip]);
    $row = $stmt->fetch();
    if (!$row) return false;

    $attempts = (int)$row['attempts'];
    $last = strtotime($row['last_attempt'] ?? '1970-01-01');

    return ($attempts >= $limit) and ((time() - $last) < ($minutes * 60));
}

function create_remember_token(PDO $db, int $user_id): void {
    $selector  = bin2hex(random_bytes(9));   //--------------salvo em claro
    $validator = bin2hex(random_bytes(32));  //--------------salvo somente hash
    $hash = hash('sha256', $validator);
    $expires = (new DateTime('+30 days'))->format('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO remember_tokens (user_id, selector, token_hash, expires)
                          VALUES (:u, :s, :h, :e)");
    $stmt->execute([':u' => $user_id, ':s' => $selector, ':h' => $hash, ':e' => $expires]);

    $cookie_value = $selector . ':' . $validator;
    setcookie('remember_me', $cookie_value, [
        'expires'  => time() + 60*60*24*30,
        'path'     => '/',
        'secure'   => true,   //--------------exige HTTPS em produção
        'httponly' => true,   //--------------protege contra XSS
        'samesite' => 'Lax'
    ]);
}

function try_remember_login(PDO $db): ?array {
    if (empty($_COOKIE['remember_me'])) return null;
    $parts = explode(':', $_COOKIE['remember_me'], 2);
    if (count($parts) !== 2) return null;

    [$selector, $validator] = $parts;
    $stmt = $db->prepare("SELECT * FROM remember_tokens WHERE selector = :s");
    $stmt->execute([':s' => $selector]);
    $token = $stmt->fetch();

    if ($token && hash_equals($token['token_hash'], hash('sha256', $validator))) {
        if (new DateTime() < new DateTime($token['expires'])) {
            $u = $db->prepare("SELECT id, username FROM usuarios WHERE id = :id");
            $u->execute([':id' => $token['user_id']]);
            return $u->fetch();
        }
    }
    return null;
}

function logout_user(PDO $db, bool $clearCookie = true): void {
    if (!empty($_COOKIE['remember_me'])) {
        [$selector] = explode(':', $_COOKIE['remember_me']);
        $del = $db->prepare("DELETE FROM remember_tokens WHERE selector = :s");
        $del->execute([':s' => $selector]);
        if ($clearCookie) {
            setcookie('remember_me', '', time() - 3600, '/');
        }
    }
    session_unset();
    session_destroy();
}
