<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>En cours de traitement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="index.php">Publications</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
  </ul>
</nav>
<div style="height: 80vh; width: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: auto">
<small class="text-secondary">Veuillez patienter</small>
<div class="spinner-border text-secondary"></div>
</div>
<?php
session_start();
class Categorie
{
  private $id;
  private $categorie;

  public function get_infos()
  {
    return array(
      "id" => $this->id,
      "categorie" => $this->categorie
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

$conn= new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
$conn->innet();
$test = True;
$result = $conn::$Bd->query('SELECT id, categorie FROM categories')->fetchAll(PDO::FETCH_CLASS, "Categorie");

if(!empty($_POST))
{
  if(isset($_POST['category']) && isset($_POST['content']) && isset($_POST['author']))
  {
    if(isset($_POST['newcategory']) && !empty($_POST['newcategory']))
    {
      foreach($result as $cat)
      {
        if($cat->get_infos()['categorie'] == $_POST['newcategory'])
        {
          $test = False;
        }
      }

      if($test)
      {
        $conn::$Bd->query('INSERT INTO categories(categorie) VALUES("'.$_POST["newcategory"].'")');
        $id = $conn::$Bd->query('SELECT id, categorie FROM categories WHERE categorie="'.$_POST["newcategory"].'"')->fetchAll(PDO::FETCH_CLASS, "Categorie")[0]->get_infos()['id'];
        $conn::$Bd->query('INSERT INTO publications(contenu, auteur, categorie) VALUES("'.$_POST["content"].'",'.$_POST['author'].','.$id.');');
        header('location: publications.php?error=false');
      }
      else {
        header('location: publications.php?error=true');
      }
    }
    else {
      $conn::$Bd->query('INSERT INTO publications(contenu, auteur, categorie) VALUES("'.$_POST["content"].'",'.$_POST['author'].','.$_POST["category"].')');
      header('location: publications.php?error=false');
    }
  }
  else {
    header('location: publications.php?error=true');
  }
}
else {
  header('location: publications.php?error=true');
}

?>