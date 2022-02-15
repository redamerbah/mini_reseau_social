<?php
    session_start();
    if(isset($_SESSION['login']) || (isset($_COOKIE['login']) && isset($_COOKIE['remember']) && $_COOKIE['remember'] == 'True'))
    {
        if(isset($_COOKIE['login']) && isset($_COOKIE['remember']) && $_COOKIE['remember'] == 'True')
        {
            if(isset($_SESSION['login']))
            {
                header('Location: publications.php');
            }
            else
            {
                echo "Cookies kaynin";
                $_SESSION['login'] = $_COOKIE['login'];
                header('Location: publications.php');
            }
        }
        {
            if(isset($_SESSION['login']))
            {
                header('Location: publications.php');
            }
            else
            {
                header('Location: connexion.php');
            }
        }
    }
    else {
        header('Location: connexion.php');
    }
?>