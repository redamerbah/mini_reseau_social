<?php
session_start();

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
  private $author;
  private $content;
  private $category;
  private $nbVotes = 0;
  private $likers;
  //TODO Affichage des publications
  public function __toString__($pseudoUsr)
  {
    str_replace("\\n", "<br>", $this->content);
    if (count($this->likers) > 0) {
      $strLikers = "";
      foreach ($this->likers as $liker) {
        $strLikers = $strLikers . "<li class='list-group-item bg-dark text-light p-2'><span class='font-weight-bold'><a class='text-decoration-none text-light' href='profile.php?uid=" . $liker->get_infos()['pseudo'] . "'>" . $liker->get_infos()['pseudo'] . "</a></span></li>";
      }
    } else {
      $strLikers = "<li class='list-group-item bg-dark text-light'>Liste Vide</li>";
    }
    $test = False;
    foreach ($this->likers as $liker) {
      if ($liker->get_infos()['pseudo'] == $pseudoUsr) {
        $test = True;
      }
    }
    if ($pseudoUsr == $this->author) {
      $strDelete = "<span class='float-right'>
      <form method='POST' action='delete_post.php'>
        <input class='d-none' type='text' name='from' value='posts'>
        <input class='d-none' type='text' name='posts' value='posts'>
        <button type='submit' name='kill" . $this->id . "' class='btn'>
          <i class='fa fa-trash-alt text-danger' aria-hidden='true'></i>
        </button>
      </form>
      </span>";
      $strModify = "<span class='float-right'>
        <button type='button' class='btn' data-toggle='modal' data-target='#edit".$this->id."'>
          <i class='fa fa-edit text-dark' aria-hidden='true'></i>
        </button>
      </span>
      <div class='modal fade' id='edit".$this->id."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered' role='document'>
                <div class='modal-content'>
                    <div class='modal-header border-bottom-0'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class='form-title text-center'>
                            <h4>Modifier Publication</h4>
                        </div>
                        <div class='d-flex flex-column text-center'>
                            <form method='post' action='edit_post.php'>
                                <div class='form-group'>
                                    <label for='newcontent'>Modifiez votre publication ici</label>
                                    <textarea class='form-control' rows='5' id='newcontent' name='newcontent'
                                        required>".$this->content."</textarea>
                                </div>
                                <div class='form-group'>
                                  <input class='d-none' type='text' name='id_pub' value='".$this->id."'>
                                  <input class='d-none' type='text' name='from' value='posts'>
                                  <input class='d-none' type='text' name='pub' value='pub'>
                                </div>
                                <button type='submit' class='btn btn-dark btn-block'>Modifier</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    } else {
      $strDelete = "";
      $strModify = "";
    }

    if ($test) {
      return "<li class='zoom list-group-item my-2 border'>
        <div class='clearfix'>
          <span class='float-left'>
            <h4>
              <span><a class='text-decoration-none text-dark' href='profile.php?uid=" . $this->author . "'>@" . $this->author . "</a></span>
              <small><a class='text-decoration-none text-dark' href='publications_categorie.php?cat_id=" . $this->category . "'> ~" . $this->category . "</a></small>
            </h4>
          </span>"
          . $strDelete .
          ""
          . $strModify .
          "<span class='float-right'>
            <form method='POST' action='unvote.php'>
              <input class='d-none' type='text' name='from' value='posts'>
              <button type='submit' name='like" . $this->id . "' class='btn'><i class='fa fa-thumbs-up text-primary' aria-hidden='true'></i></button>
            </form>
          </span>
        </div>
        <div class='p-3 border bg-light text-dark'>
          <p>" . $this->content . "</p>
        </div>
        <small class='font-weight-bold'>
          <a class='text-decoration-none text-dark' href='#id" . $this->id . "' data-toggle='collapse'>" . $this->nbVotes . " Personnes ont Voté.</a>
        </small>
        <div id='id" . $this->id . "' class='text-light collapse'>
          <ul class='list-group'>" . $strLikers . "</ul>
        </div>
        </li>";
    } else {
      return "<li class='zoom list-group-item my-2 border'>
      <div class='clearfix'>
        <span class='float-left'>
          <h4>
            <span><a class='text-decoration-none text-dark' href='profile.php?uid=" . $this->author . "'>@" . $this->author . "</a></span>
            <small><a class='text-decoration-none text-dark' href='publications_categorie.php?cat_id=" . $this->category . "'> ~" . $this->category . "</a></small>
          </h4>
        </span>"
        . $strDelete .
        ""
        . $strModify .
        "<span class='float-right'>
          <form method='POST' action='vote.php'>
            <input class='d-none' type='text' name='from' value='posts'>
            <button type='submit' name='like" . $this->id . "' class='btn'><i class='fa fa-thumbs-up text-secondary' aria-hidden='true'></i></button>
          </form>
        </span>
      </div>
      <div class='p-3 border bg-light text-dark'>
        <p>" . $this->content . "</p>
      </div>
      <small class='font-weight-bold'>
        <a class='text-decoration-none text-dark' href='#id" . $this->id . "' data-toggle='collapse'>" . $this->nbVotes . " Personnes ont Voté.</a>
      </small>
      <div id='id" . $this->id . "' class='text-light collapse'>
        <ul class='list-group'>" . $strLikers . "</ul>
      </div>
      </li>";
    }
  }
  //TODO fin
  public function setLikes($likes)
  {
    $this->nbVotes = $likes;
  }
  public function setLikers($likers)
  {
    $this->likers = $likers;
  }
  public function get_infos()
  {
    return array(
      "id" => $this->id,
      "author" => $this->author,
      "content" => $this->content
    );
  }
}

class Vote
{
  private $id;
  private $user;
  private $post;

  public function get_infos()
  {
    return array(
      "id" => $this->id,
      "user" => $this->user,
      "post" => $this->post
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

$conn = new Connexion('pedago01c.univ-avignon.fr', 'uapv2101044', 'sdnFPp');
$conn->innet();
$resultat = $conn::$Bd->query('SELECT publications.id AS id, publications.contenu AS content, categories.categorie AS category, utilisateurs.pseudo AS author FROM publications JOIN categories ON publications.categorie = categories.id JOIN utilisateurs ON utilisateurs.id = publications.auteur;');

$tabPubs = $resultat->fetchAll(PDO::FETCH_CLASS, "Publication");

$resultat = $conn::$Bd->query('SELECT votes.id AS id, votes.publication AS post, votes.utilisateur AS user FROM votes');
$tabVotes = $resultat->fetchAll(PDO::FETCH_CLASS, "Vote");

$resultat = $conn::$Bd->query('SELECT id, categorie FROM categories');
$tabCategories = $resultat->fetchAll(PDO::FETCH_CLASS, "Categorie");

$usr = $conn::$Bd->query('SELECT id, pseudo, naissance FROM utilisateurs WHERE id=' . $_SESSION['login'] . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur")[0];

foreach ($tabPubs as $pub) {
  $likes = 0;
  foreach ($tabVotes as $vote) {
    if ($pub->id == $vote->get_infos()['post']) {
      $likes += 1;
    }
  }
  $likers = $conn::$Bd->query('SELECT id, pseudo, naissance from utilisateurs INNER JOIN (SELECT publications.id as publication ,contenu,utilisateur as VotéePar FROM publications INNER JOIN votes ON votes.publication=publications.id) AS R ON utilisateurs.id=R.VotéePar WHERE publication=' . $pub->id . ';')->fetchAll(PDO::FETCH_CLASS, "Utilisateur");
  $pub->setLikes($likes);
  $pub->setLikers($likers);
}
?>

<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <meta charset="utf-8">
    <title>Publications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script defer>
    $(document).ready(function() {
        $(".zoom").mouseenter(
            function() {
                $(this).animate({
                    fontSize: "150%"
                }, 200);
            });
        $(".zoom").mouseleave(
            function() {
                $(this).animate({
                    fontSize: "100%"
                }, 200);
            });
    });
    </script>
    <style>
    .container {
        padding: 2rem 0rem;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 400px;
        }

        .modal-content {
            padding: 1rem;
        }
    }

    .modal-header .close {
        margin-top: -1.5rem;
    }

    .form-title {
        margin: -2rem 0rem 2rem;
    }

    .btn-round {
        border-radius: 3rem;
    }

    .delimiter {
        padding: 1rem;
    }

    .signup-section {
        padding: 0.3rem 0rem;
    }
    </style>
</head>

<body>
    <?php
  if (isset($_GET['error'])) {
    if ($_GET['error'] == 'false') {
      echo '<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succès!</strong> Votre publication a bien été créée.
    </div>';
    } else {
      echo '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Erreur!</strong> Il semble y avoir un problème durant la création de la publication.
    </div>';
    }
  }
  ?>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="publications.php">Publications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="utilisateurs.php">Utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="statUtilisateurs.php">StatUtilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Deconnexion</a>
            </li>
        </ul>
        <div class="text-light">
            <?php
      echo "Connecté en tant que @" . $usr->get_infos()['pseudo'] . " -- id : " . $usr->get_infos()['id'];
      ?>
        </div>
    </nav>
    <div class="container-fluid bg-light text-dark">
        <h1>Publications</h1>
    </div>
    <div class="container-md">
        <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#loginModal">+ Ajouter
            Publication</button>

        <ul class="list-group">

            <?php

      foreach ($tabPubs as $pub) {
        echo $pub->__toString__($usr->get_infos()['pseudo']);
      }

      ?>
        </ul>
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-title text-center">
                            <h4>Nouvelle Publication</h4>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <form method="post" action="new_post.php">
                                <div class="form-group">
                                    <label for="sel1">Sélectionnez la catégorie:</label>
                                    <select class="form-control" id="sel1" name="category" required>
                                        <?php
                    foreach ($tabCategories as $cat) {
                    ?>
                                        <option value="<?php echo $cat->get_infos()['id']; ?>">
                                            <?php echo $cat->get_infos()['categorie']; ?></option>
                                        <?php
                    }
                    ?>
                                        <option value="other">Autre..</option>
                                    </select>
                                </div>
                                <script defer>
                                $("#sel1").change(function() {
                                    if ($(this).val() == "other") {
                                        $("#other-zone").append(
                                            '<div id="other-container"><label for="other">Saisir la catégorie</label><input id="other" class="form-control" name="newcategory" required></div>'
                                        );
                                    } else {
                                        $('#other-container').remove();
                                    }
                                });
                                </script>
                                <div class="form-group" id="other-zone">
                                </div>
                                <div class="form-group">
                                    <label for="content">Redigez votre publication ici</label>
                                    <textarea class="form-control" rows="5" id="content" name="content"
                                        required></textarea>
                                </div>
                                <div class="form-group">
                                    <?php
                                      echo "<small>Vous redigez en tant que @" . $usr->get_infos()['pseudo'] . "</small>";
                                      echo '<input class="d-none form-control" type="text" name="author" value="' . $usr->get_infos()["id"] . '">';
                                    ?>

                                </div>
                                <button type="submit" class="btn btn-dark btn-block">Poster</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>