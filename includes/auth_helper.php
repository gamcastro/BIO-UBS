<?php
/**
 * includes/auth_helper.php
 * - Controle de tentativas (anti brute-force)
 * - Lembrar-me com selector/validator (hash no banco, cookie httpOnly)
 * - Logout com limpeza do token
 */

use BioUBS\Conexao;

// NÃO execute getConn() aqui globalmente. Obtenha a conexão DENTRO das funções
// ou passe-a como parâmetro. O script auth.php já obtém e passa $db.
// $db = Conexao::getConn(); // REMOVER ESTA LINHA GLOBAL

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
    
    // fetch() retorna um objeto (devido ao FETCH_OBJ na Conexao.php) ou false
    $row = $stmt->fetch(); 
    
    if (!$row) return false; // Se não houver registo, não está bloqueado

    // *** CORREÇÃO LINHA 31 ***
    // Usar sintaxe de objeto ->chave em vez de array ['chave']
    $attempts = (int)$row->attempts; 
    $last = strtotime($row->last_attempt ?? '1970-01-01');

    return ($attempts >= $limit) and ((time() - $last) < ($minutes * 60));
}

function create_remember_token(PDO $db, int $user_id): void {
    $selector  = bin2hex(random_bytes(9));
    $validator = bin2hex(random_bytes(32));
    $hash = hash('sha256', $validator);
    $expires = (new DateTime('+30 days'))->format('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO remember_tokens (user_id, selector, token_hash, expires)
                         VALUES (:u, :s, :h, :e)");
    $stmt->execute([':u' => $user_id, ':s' => $selector, ':h' => $hash, ':e' => $expires]);

    $cookie_value = $selector . ':' . $validator;
    setcookie('remember_me', $cookie_value, [
        'expires'  => time() + 60*60*24*30,
        'path'     => '/',
        'secure'   => true,  // Em produção, certifique-se de que o site usa HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

function try_remember_login(PDO $db): ?array { // Esta função é chamada pelo index.php
    if (empty($_COOKIE['remember_me'])) return null;
    $parts = explode(':', $_COOKIE['remember_me'], 2);
    if (count($parts) !== 2) return null;

    [$selector, $validator] = $parts;
    $stmt = $db->prepare("SELECT * FROM remember_tokens WHERE selector = :s");
    $stmt->execute([':s' => $selector]);
    
    // $token será um objeto
    $token = $stmt->fetch(); 

    // *** CORREÇÃO AQUI *** (Usar ->chave)
    if ($token && hash_equals($token->token_hash, hash('sha256', $validator))) {
        if (new DateTime() < new DateTime($token->expires)) {
            
            // *** CORREÇÃO NA CONSULTA: Buscar em cadastro_profissional ***
            // E retornar como ARRAY (FETCH_ASSOC) para ser compatível com o index.php
            $u = $db->prepare("SELECT ID as id, matricula as username 
                              FROM cadastro_profissional 
                              WHERE ID = :id AND is_active = 1"); // Usar ID, não id
            $u->execute([':id' => $token->user_id]); // Usar ->user_id
            
            // Forçar o retorno como array associativo aqui, pois o index.php espera um array
            return $u->fetch(PDO::FETCH_ASSOC); 
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