<?php
    session_start();
    unset($_SESSION);
    setcookie('PHPSESSID', '', time()-60);
    setcookie('login', '', time()-60);
    setcookie('remember', '', time()-60);
    session_destroy();
    header('Location: index.php');
?>