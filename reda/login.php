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

class Connexion
{

    private  $servername="";
    private  $username=""; 
    private  $password=""; 
    public static $Bd ;

    public function __construct($servername , $username, $password ){

	    $this->servername = $servername;
	    $this->username = $username;
	    $this->password = $password;


    }

    public function innet(){
        try {

            self::$Bd = new PDO("pgsql:host=$this->servername;dbname=etd", $this->username, $this->password);
        } 
        catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

if(!empty($_POST))
{
    $conn= new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
    $conn->innet();

    $tabUsers = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs')->fetchAll(PDO::FETCH_CLASS, "Utilisateur");
    if(isset($_POST['pseudo']) && isset($_POST['id']))
    {
        $test = False;
        foreach($tabUsers as $usr)
        {
            if($usr->get_infos()['pseudo'] == $_POST['pseudo'] && $usr->get_infos()['id'] == $_POST['id'])
            {
                $test = True;
                break;
            }
        }
        if($test)
        {
            if(isset($_POST['remember_me']) && $_POST['remember_me'] == "remember")
            {
                setcookie("login", $_POST['pseudo'], time()+84600);
                setcookie("remember", "True", time()+84600);
                $_SESSION['login'] = $_POST['id'];
                header('Location: publications.php');
            }
            else {
                setcookie("login", $_POST['id'], time()+84600);
                setcookie("remember", "False");
                $_SESSION['login'] = $_POST['id'];
                header('Location: publications.php');
            }
        }
        else {
            header('Location: connexion.php?error=true&motif=login');
        }
    }
    else {
        header('Location: connexion.php?error=true&motif=login');
    }
}
else {
    header('Location: connexion.php?error=true&motif=login');
}
?>