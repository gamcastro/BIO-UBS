<?php 

session_start();
require_once __DIR__ . '/vendor/autoload.php';

$db = db_conn();

// Se não houver sessão, tenta autenticar via remember-me
if (empty($_SESSION['user_id'])) {
    $user = try_remember_login($db);
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    } else {
        header("Location: login.php");
        exit;
    }
}

$tituloDaPagina = "BIO-UBS" ;
include_once('includes/header.php');
?>





 

<center>
<h1 style="color:#CCCCCC">PAGINA INICIAL</h1>
</center>




<?php 
include_once('includes/footer.php');
?>

