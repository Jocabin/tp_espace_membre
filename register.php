<?php
include('bdd.php');
include('auth.php');

if ($_SESSION['is_logged']) {
    header('Location: index.php');
    exit;
}

foreach ($_POST as $key => $value) {
    $$key = $value;
    $_SESSION[$key] = $$key;
}

if (isset($_SESSION['erreur'])) { ?>
    <p class="error">Le formulaire contient des erreurs</p>
<?php }

unset($_SESSION['erreur']); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Page d'inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <style>
        form {
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        body,
        html {
            width: 100%;
            height: 100%;
            display: flex;
        }

        * {
            box-sizing: border-box;
        }
    </style>

    <meta name="description" content="Page de login">
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>
</head>

<body>
    <form class="bg-primary text-white p-5 rounded" action="#" method="post">
        <?php if (isset($_SESSION['error_fields']) && isset($_SESSION['error_string']['user']) && in_array('user', $_SESSION['error_fields']) && isset($_SESSION['erreur'])) echo $_SESSION['error_string']['user'] ?>
        <div class="mb-3">
            <label for="user" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="user" class="form-control" id="user" placeholder="GTJesraD">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" placeholder="exemple@mail.fr" class="form-control" id="email" rows="3"></input>
        </div>

        <?php if (isset($_SESSION['error_fields']) && isset($_SESSION['error_string']['password']) && in_array('user', $_SESSION['error_fields']) && isset($_SESSION['erreur'])) echo $_SESSION['error_string']['password'] ?>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" id="password" rows="3"></input>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirmation du mot de passe</label>
            <input type="password" name="password_confirm" class="form-control" id="password_confirm" rows="3"></input>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-light">S'inscrire</button>
            <a href="login.php" class="link-light">Se connecter</a>
        </div>
    </form>

    <?php
    if (isset($_POST['user'])) {
        $erreur = false;
        $message = '';
        $error_fields = [];
        $error_string = [];

        if (strlen($user) < 1) {
            $message .= "Veuillez saisir un nom d'utilisateur<br>";
            $erreur = true;
            $error_fields[] = "user";
        } else {
            $sql = "select pseudo from user where pseudo=:pseudo";
            $pdoStatement = $connexion->prepare($sql);
            $pdoStatement->bindParam(':pseudo', $user, PDO::PARAM_STR);
            $result = $pdoStatement->execute();

            if ($result) {
                $result = $pdoStatement->fetchAll();
                if (count($result) > 0) {
                    $erreur = true;
                    $error_string['user'] = "Ce pseudo existe déjà";
                    $error_fields[] = "user";
                }
            }
        }

        if (strlen($password) < 1 && strlen($password_confirm) < 1) {
            $message .= "Veuillez saisir un mot de passe<br>";
            $erreur = true;
            $error_fields[] = "password";
            $error_fields[] = "password_confirm";
        } else if ($password != $password_confirm) {
            $message .= "Les mots de passes doivent être identiques<br>";
            $erreur = true;
            $error_fields[] = "password_confirm";
        } else {
            echo 'OKI DOKI';
        }

        if (strlen($email) < 1 && !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $message .= "Votre adresse mail est pas conforme";
            $erreur = true;
            $error_fields[] = "email";
        } else {
            echo 'email oki doki';
        }

        if ($erreur) {
            $_SESSION['erreur'] = $message;
            $_SESSION['error_fields'] = $error_fields;
            $_SESSION['error_string'] = $error_string;
            header('Location: register.php');
            exit;
        } else {
            unset($_SESSION['erreur']);
            unset($_SESSION['error_fields']);
            unset($_SESSION['error_string']);

            $sql = "insert into `user` (`pseudo`, `pass`, `email`) values (:pseudo, :pass, :email);";
            $pdoStatement = $connexion->prepare($sql);
            $pwd = password_hash($password, PASSWORD_DEFAULT);

            $pdoStatement->bindParam(':pseudo', $user, PDO::PARAM_STR);
            $pdoStatement->bindParam(':pass', $pwd, PDO::PARAM_STR);
            $pdoStatement->bindParam(':email', $email, PDO::PARAM_STR);
            $pdoStatement->execute();

            $_SESSION['authUser'] = $user;
            $_SESSION['authPassword'] = $pwd;

            $_SESSION['is_logged'] = true;

            header('Location: index.php');
        }
    }
    ?>
</body>

</html>