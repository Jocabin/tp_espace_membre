<?php
include('bdd.php');
include('auth.php');

if ($_SESSION['is_logged']) {
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['erreur'])) { ?>
    <p class="error">Le formulaire contient des erreurs</p>
<?php }

unset($_SESSION['erreur']); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Page de connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <meta name="description" content="Page de login">
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>

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
</head>

<body>
    <form class="bg-primary text-white p-5 rounded" action="#" method="post">
        <?php if (isset($_SESSION['error_fields']) && isset($_SESSION['error_string']['user']) && in_array('user', $_SESSION['error_fields']) && isset($_SESSION['erreur']))  echo $_SESSION['error_string']['user'];  ?>

        <div class="mb-3">
            <label for="user" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="user" class="form-control" id="user" placeholder="GTJesraD">
        </div>

        <?php if (isset($_SESSION['error_fields']) && isset($_SESSION['error_string']['password']) && in_array('password', $_SESSION['error_fields']) && isset($_SESSION['erreur'])) echo $_SESSION['error_string']['password'] ?>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" id="password" rows="3"></input>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-light">Se connecter</button>
            <a href="register.php" class="link-light">S'inscrire</a>
        </div>

        <div class="mb-3">
            <a href="forget.php" class="link-light">Mot de passe oublié</a>
        </div>
    </form>

    <?php
    foreach ($_POST as $key => $value) {
        $$key = $value;
        $_SESSION[$key] = $$key;
    }

    if (isset($_POST['user'])) {
        $erreur = false;
        $message = '';
        $error_fields = [];
        $error_string = [];

        if (strlen($user) < 1) {
            $message .= "Veuillez saisir un nom d'utilisateur<br>";
            $erreur = true;
            $error_fields[] = "user";
            $error_string['user'] = "Nom d'utiliseur vide";
        } else {
            $sql = "select pseudo from user where pseudo=:pseudo";
            $pdoStatement = $connexion->prepare($sql);
            $pdoStatement->bindParam(':pseudo', $user, PDO::PARAM_STR);
            $result = $pdoStatement->execute();

            if ($result) {
                $res = $pdoStatement->fetchAll();
                if (count($res) < 1) {
                    $message .= "Pseudo invalide";
                    $erreur = true;
                    $error_fields[] = "user";
                    $error_string['user'] = "Pseudo invalide";
                } else {
                    $_SESSION['authUser'] = $user;
                }
            }
        }

        if (strlen($password) < 1) {
            $message .= "Veuillez saisir un mot de passe<br>";
            $erreur = true;
            $error_fields[] = "password";
            $error_string['password'] = "Mot de passe vide";
        } else {
            $sql = "select pass from user where pseudo=:user";
            $pdoStatement = $connexion->prepare($sql);
            $pwd = password_hash($password, PASSWORD_DEFAULT);
            $pdoStatement->bindParam(':user', $user, PDO::PARAM_STR);
            $result = $pdoStatement->execute();

            if ($result) {
                $res = $pdoStatement->fetchAll();
                if (count($res) < 1) {
                    $message .= "Pseudo invalide";
                    $erreur = true;
                    $error_fields[] = "user";
                    $error_string['user'] = "Le mot de passe est invalide";
                } else {
                    if (password_verify($password, $res[0]['pass'])) {
                        $_SESSION['authPassword'] = $pwd;
                    } else {
                        $message .= "Mot de passe erronné<br>";
                        $erreur = true;
                        $error_fields[] = "password";
                        $error_string['password'] = "Mot de passe faux";
                    }
                }
            }
        }

        if ($erreur) {
            $_SESSION['erreur'] = $message;
            $_SESSION['error_fields'] = $error_fields;
            $_SESSION['error_string'] = $error_string;
            $_SESSION['is_logged'] = false;
            header('Location: login.php');
            exit;
        } else {
            unset($_SESSION['erreur']);
            unset($_SESSION['error_fields']);
            unset($_SESSION['error_string']);
            $_SESSION['is_logged'] = true;
            header('Location: index.php');
        }
    }
    ?>
</body>

</html>