<!DOCTYPE html>
<html>

<head lang="fr-fr">
    <meta charset="utf-8">
    <title>Authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    body {
        background-image: url("img/BG.jpg");
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
    }
    </style>
</head>

<body>

    <?php
    session_start();
if(isset($_GET['error']) && isset($_GET['motif']))
{
    if($_GET['motif'] == "login")
    {
        if($_GET['error'] == 'false')
        {
            echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Succès!</strong> Vous vous etes bien connectés.
            </div>';
        }
        else {
            echo '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Erreur!</strong> Pseudo ou ID non convenables.
            </div>';
        }
    }
    elseif($_GET['motif'] == "signup")
    {
        if($_GET['error'] == 'false')
        {
            echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Succès!</strong> Vous vous etes bien inscrit Connectez vous dans la section Login.
            </div>';
        }
        else {
            echo '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Erreur!</strong> Erreur lors de l\'inscription.
            </div>';
        }
    }
    else
    {
        echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Alerte!</strong> une erreur est survenue.
            </div>';
    }
}
?>
    
    <div class="container-fluid bg-light text-dark">
        <h1>Authentification</h1>
    </div>
    <div class="container-md">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark text-light">
                    <div class="card-header font-weight-bold">Login <i class="fa fa-key"></i></div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <form action="login.php" method="post">
                                        <div class="form-group">
                                            <label for="pseudo">pseudo</label>
                                            <input type="text" class="form-control" placeholder="Entrez pseudo"
                                                id="pseudo" name="pseudo" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id">ID</label>
                                            <input type="text" class="form-control" placeholder="Entrez id" id="id"
                                                name="id" required>
                                        </div>
                                        <div class="form-group form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="remember"
                                                    name="remember_me"> Remember me
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-block">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 p-2 justify-content-center"
                style="display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 3em">
                <span>OU</span>
            </div>
            <div class="col-md-6">
                <div class="card bg-dark text-light">
                    <div class="card-header font-weight-bold">Sign Up <i class="fa fa-address-card"></i></div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <form action="signup.php" method="post">
                                        <div class="form-group">
                                            <label for="newpseudo">pseudo</label>
                                            <input type="text" class="form-control" placeholder="Entrez pseudo"
                                                id="newpseudo" name="newpseudo" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="birthyear">Année de naissance</label>
                                            <input type="text" class="form-control"
                                                placeholder="Entrez L'année de naissance" id="birthyear"
                                                name="birthyear" required>
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-block">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>