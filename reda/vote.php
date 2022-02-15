<?php
session_start();
class Utilisateur
{
    private $id;
    private $pseudo;
    private $naissance;

    public function get_infos()
    {
        return array(
            "id" => $this->id,
            "pseudo" => $this->pseudo,
            "naissance" => $this->naissance
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
            echo "Cookies kaynin";
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

if (!empty($_POST)) {
    if (isset($_POST['from'])) {
        $conn = new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
        $conn->innet();

        $tabPubs = $conn::$Bd->query('SELECT publications.id AS id FROM publications')->fetchAll(PDO::FETCH_CLASS, "Publication");
        $usr = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE id=' . $_SESSION['login'] . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0];
        foreach ($tabPubs as $pub)
        {
            if (isset($_POST['like'.$pub->id]))
            {
                $conn::$Bd->query('INSERT INTO votes(utilisateur, publication) VALUES('.$usr->get_infos()['id'].', '.$pub->id.')');
                unset($_POST['like'.$pub->id]);
                if($_POST['from'] == 'category')
                {
                    if(isset($_POST['cat_id']))
                    {
                        header('Location: publications_categorie.php?cat_id='.$_POST['cat_id'].'');
                    }
                    else
                    {
                        header('Location: index.php');
                    }
                }
                elseif($_POST['from'] == 'profile')
                {
                    if(isset($_POST['uid']))
                    {
                        header('Location: profile.php?uid='.$_POST['uid'].'');
                    }
                    else
                    {
                        header('Location: index.php');
                    }
                }
                elseif($_POST['from'] == 'posts')
                {
                    header('Location: publications.php');
                }
                else
                {
                    header('Location: index.php');
                }
            }
        }
    } else {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}