<?php
session_start();
$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
setcookie('email', '', time() - 3600);

// サーバ側の破棄
session_destroy();

header('Location: login.php'); 
exit();

?>
