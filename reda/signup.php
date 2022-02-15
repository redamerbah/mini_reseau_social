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
    if(isset($_POST['newpseudo']) && isset($_POST['birthyear']))
    {
        echo "pseudo : ".$_POST['newpseudo']."------------ birth : ".$_POST['birthyear'];
        $test = True;
        foreach($tabUsers as $usr)
        {
            if($usr->get_infos()['pseudo'] == $_POST['newpseudo'])
            {
                $test = False;
                break;
            }
        }
        if($test)
        {
            $conn::$Bd->query('INSERT INTO utilisateurs(pseudo, naissance) VALUES("'.$_POST['newpseudo'].'", '.$_POST['birthyear'].');');
            $usrID = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE pseudo="'.$_POST['newpseudo'].'"')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0]->get_infos()['id'];
            $_SESSION['login'] = $usrID;
            header('Location: publications.php');
        }
        else {
            header('Location: connexion.php?error=true&motif=signup');
        }
    }
    else {
        header('Location: connexion.php?error=true&motif=signup');
    }
}
else {
    header('Location: connexion.php?error=true&motif=signup');
}
?>