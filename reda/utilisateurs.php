<?php

session_start();

class Utilisateur
{
    private $id;
    private $pseudo;
    private $naissance;
    private $nbPosts = 0;

    public function __toString__()
    {
        if($this->nbPosts > 0)
        {
            $profileStr = "<a class='blue text-decoration-none text-secondary' href='profile.php?uid=" . $this->pseudo . "'>" . $this->pseudo . "</a><small>~id : " . $this->id . "</small>";
        }
        else
        {
            $profileStr = "<span class='text-secondary'>" . $this->pseudo . "</a><small>~id : " . $this->id . "</small>";
        }

        return "<div class='col p-2'>
        <div class='card bg-light text-dark selected'>
        <div class='card-header p-1'>
        <span class='font-weight-bold'>" . $profileStr . "</span>
        </div>
        <div class='card-body'>

        <p>Né en : <span class='text-secondary'>" . $this->naissance . "<span></p>
        <p>Nombre de pubs : <span class='text-secondary'>" . $this->nbPosts . "<span></p>
        
        </div>
        </div>
        </div>";
    }

    public function get_infos()
    {
        return array(
            "id" => $this->id,
            "pseudo" => $this->pseudo,
            "naissance" => $this->naissance,
            "posts" => $this->nbPosts
        );
    }
    public function setNbPosts($nbPosts)
    {
        $this->nbPosts = $nbPosts;
    }
}

class Publication
{
    public $id;
}

class Connexion
{

    private  $servername = "";
    private  $username = "";
    private  $password = "";
    public static $Bd;

    public function __construct($servername, $username, $password)
    {

        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
    }

    public function innet()
    {
        try {

            self::$Bd = new PDO("pgsql:host=$this->servername;dbname=etd", $this->username, $this->password);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

if (isset($_SESSION['login']) || (isset($_COOKIE['login']) && isset($_COOKIE['remember']) && $_COOKIE['remember'] == 'True')) {
    if (isset($_COOKIE['login']) && isset($_COOKIE['remember']) && $_COOKIE['remember'] == 'True') {
        if (isset($_SESSION['login'])) {
        } else {
            $_SESSION['login'] = $_COOKIE['login'];
        }
    } {
        if (isset($_SESSION['login'])) {
        } else {
            header('Location: connexion.php');
        }
    }
} else {
    header('Location: connexion.php');
}

$conn = new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
$conn->innet();
$usr = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE id=' . $_SESSION['login'] . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0];
$tabUsers = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs')->fetchAll(PDO::FETCH_CLASS, "Utilisateur");

foreach ($tabUsers as $user) {
    $user->setNbPosts(count($conn::$Bd->query('SELECT id FROM publications WHERE auteur=' . $user->get_infos()['id'] . ';')->fetchAll(PDO::FETCH_CLASS, "Publication")));
}

?>

<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Utilisateurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script defer>
    $(document).ready(function() {
        $(".blue").mouseenter(
            function() {
                $(this).removeClass("text-secondary");
                $(this).addClass("text-primary");
            });
        $(".blue").mouseleave(
            function() {
                $(this).removeClass("text-primary");
                $(this).addClass("text-secondary");
            });
    });

    $(document).ready(function() {
        $(".selected").mouseenter(
            function() {
                $(this).removeClass("bg-light");
                $(this).removeClass("text-dark");
                $(this).addClass("text-light");
                $(this).addClass("bg-dark");
            });
        $(".selected").mouseleave(
            function() {
                $(this).removeClass("text-light");
                $(this).removeClass("bg-dark");
                $(this).addClass("bg-light");
                $(this).addClass("text-dark");
            });
    });
    </script>
</head>

<body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="publications.php">Publications</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="utilisateurs.php">Utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Deconnexion</a>
            </li>
        </ul>
        <div class="text-light">
            <?php
            echo "Connecté en tant que @" . $usr->get_infos()['pseudo'] . " votre id : " . $usr->get_infos()['id'];
            ?>
        </div>
    </nav>
    <div class="container-fluid bg-light text-dark">
        <h1>Utilisateurs</h1>
    </div>
    <div class="container-md">
        <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1">
            <?php
            foreach ($tabUsers as $user) {
                echo $user->__toString__();
            }
            ?>
        </div>
    </div>

</body>

</html>