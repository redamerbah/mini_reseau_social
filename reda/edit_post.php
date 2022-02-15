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
    public $author;
    public $category;
    public $content;

    public function get_infos()
    {
        return array(
            "id" => $this->id,
            "author" => $this->author,
            "category" => $this->category,
            "content" => $this->content
        );
    }
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
    if (isset($_POST['id_pub']) && isset($_POST['newcontent'])) {
        $conn = new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
        $conn->innet();

        $pub = $conn::$Bd->query('SELECT id AS id, auteur AS author, contenu AS content, categorie AS category FROM publications WHERE id=' . $_POST['id_pub'] . ';')->fetchAll(PDO::FETCH_CLASS, "Publication")[0];
        $usr = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE id=' . $_SESSION['login'] . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0];
        if ($usr->get_infos()['id'] == $pub->get_infos()['author']) {
            $conn::$Bd->query('UPDATE publications SET contenu="' . $_POST['newcontent'] . '" WHERE id='. $pub->get_infos()['id'] .';');
            if ($_POST['from'] == 'category') {
                if (isset($_POST['cat_id'])) {
                    header('Location: publications_categorie.php?cat_id=' . $_POST['cat_id'] . '');
                } else {
                    header('Location: index.php');
                }
            } elseif ($_POST['from'] == 'profile') {
                if (isset($_POST['uid'])) {
                    header('Location: profile.php?uid=' . $_POST['uid'] . '');
                } else {
                    header('Location: index.php');
                }
            } elseif ($_POST['from'] == 'posts') {
                header('Location: publications.php');
            } else {
                header('Location: index.php');
            }
        } else {
            header('Location: index.php');
        }
    } else {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}
