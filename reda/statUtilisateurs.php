<?php
session_start();

class Utilisateur
{
    private $id;
    private $pseudo;
    private $nbrVotesTot;

    public function __toString__()
    {
        return "<li class='list-group-item p-2'>
            <div class='container'>
                <div class='row'>
                    <h4 class='font-weight-bold'>".$this->pseudo." <small>~Nombre de votes total : ".$this->nbrVotesTot."</small></h4>
                </div>
                <div class='row'>
                    <button class='btn btn-block btn-secondary' type='button'>Afficher categorie principale</button>
                    <div class='hide hidden'></div>
                </div>
            </div>
        </li>";
    }

    public function get_infos()
    {
        return array(
            "id" => $this->id,
            "pseudo" => $this->pseudo,
            "nbrVotesTot" => $this->nbrVotesTot
        );
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
    } else {
        if (isset($_SESSION['login'])) {
        } else {
            header('Location: connexion.php');
        }
    }
} else {
    header('Location: /miniprojet_reda_merbah/connexion.php');
}

$conn = new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
$conn->innet();
$usr = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE id=' . $_SESSION['login'] . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0];
$tabUsers_notnull = $conn::$Bd->query('SELECT pseudo,SUM(nbrvotes) as nbrVotesTot from  (select count(publication) as nbrVotes, publication,pseudo,auteur from votes INNER JOIN publications ON publications.id=votes.publication INNER JOIN utilisateurs ON utilisateurs.id=publications.auteur group by publication,auteur,pseudo) AS hamid group by auteur,pseudo order by nbrVotesTot desc')->fetchAll(PDO::FETCH_CLASS, 'Utilisateur');
$tabUsers_null = $conn::$Bd->query('SELECT pseudo, 0 AS nbrVotesTot FROM utilisateurs WHERE utilisateurs.pseudo NOT IN (select pseudo from  (select count(publication) as nbrVotes, publication,pseudo,auteur from votes INNER JOIN publications ON publications.id=votes.publication INNER JOIN utilisateurs ON utilisateurs.id=publications.auteur group by publication,auteur,pseudo) AS hamid group by auteur,pseudo)')->fetchAll(PDO::FETCH_CLASS, 'Utilisateur');
$tabUsers_final = array_merge($tabUsers_notnull, $tabUsers_null);

?>

<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <meta charset="utf-8">
    <title>StatUtilisateurs</title>
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
    $(document).ready(function(){
    //Dès qu'on clique sur #b1, on applique hide() au titre
    $("#cache").click(function(){
        $(".hide").hide();
    });
    $("#cache1").click(function(){
        $(".hide").show();
    });
    });
    </script>
</head>

<body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/miniprojet_reda_merbah/publications.php">Publications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/miniprojet_reda_merbah/utilisateurs.php">Utilisateurs</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="/miniprojet_reda_merbah/statUtilisateurs.php">Stats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/miniprojet_reda_merbah/logout.php">Deconnexion</a>
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
        <ul class="list-group">
            <?php
            foreach ($tabUsers_final as $user) {
                echo $user->__toString__();
            }
            ?>
        </ul>
    </div>

</body>

</html>