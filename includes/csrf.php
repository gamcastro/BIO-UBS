<?php
/**
 * CSRF Protection - versão aprimorada
 * ------------------------------------
 * Esta versão permite múltiplos tokens simultâneos,
 * um para cada formulário identificado por um nome (ex: 'login', 'reset', etc.).
 *
 * Uso:
 *  - Gerar token:   $csrf = generate_csrf('reset');
 *  - Verificar:     verify_csrf($_POST['csrf_token'] ?? '', 'reset')
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * ----------------------Gera um token CSRF exclusivo por formulário.
 * @param string $form_name Nome do formulário (ex: 'login', 'reset')
 * @param int $duration Tempo de validade em segundos (padrão: 1800s = 30 min)
 * @return string Token gerado
 */
function generate_csrf(string $form_name = 'default', int $duration = 1800): string {
    //--------------Cria array se ainda não existir
    if (empty($_SESSION['csrf_tokens'])) {
        $_SESSION['csrf_tokens'] = [];
    }

    //---------------Gera um novo token apenas se ainda não existir ou tiver expirado
    if (
        empty($_SESSION['csrf_tokens'][$form_name]['token'])
        || (time() - ($_SESSION['csrf_tokens'][$form_name]['time'] ?? 0)) > $duration
    ) {
        $_SESSION['csrf_tokens'][$form_name] = [
            'token' => bin2hex(random_bytes(32)),
            'time'  => time()
        ];
    }

    return $_SESSION['csrf_tokens'][$form_name]['token'];
}

/**
 * ---------Verifica se o token informado é válido para o formulário.
 * @param string $token Token recebido do POST
 * @param string $form_name Nome do formulário
 * @param int $duration Tempo de validade (padrão: 1800s)
 * @return bool
 */
function verify_csrf(string $token, string $form_name = 'default', int $duration = 1800): bool {
    if (empty($_SESSION['csrf_tokens'][$form_name]['token'])) {
        return false;
    }

    $stored = $_SESSION['csrf_tokens'][$form_name];

    return hash_equals($stored['token'], $token)
        && (time() - $stored['time'] < $duration);
}

/**
 * Remove tokens expirados para evitar acúmulo na sessão.
 * Pode ser chamado em qualquer ponto da aplicação.
 */
function cleanup_expired_csrf(int $duration = 1800): void {
    if (!empty($_SESSION['csrf_tokens'])) {
        foreach ($_SESSION['csrf_tokens'] as $key => $data) {
            if ((time() - ($data['time'] ?? 0)) > $duration) {
                unset($_SESSION['csrf_tokens'][$key]);
            }
        }
    }
}
