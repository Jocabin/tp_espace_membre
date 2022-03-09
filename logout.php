<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Vous êtes déconnecté !</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <meta name="description" content="Page de login">
    <noscript>Votre navigateur ne supporte pas JavaScript !</noscript>
</head>

<body>
    <?php
    include("bdd.php");
    include("auth.php");

    unset($_SESSION['erreur']);

    if ($_SESSION['is_logged']) {
        unset($_SESSION['authUser']);
        unset($_SESSION['authPassword']);
        $_SESSION = array();
        $_SESSION['is_logged'] = false;
    ?>
        <h1>Vous êtes bien déconnecté !</h1>
        <a href="login.php" class="link-primary">Page de connexion</a>
    <?php }

    if (isset($_SESSION['edited']) && $_SESSION['edited']) { ?>
        <h1>Votre mot de passe à bien été modifié !</h1>
        <a href="login.php" class="link-primary">Page de connexion</a>
    <?php } ?>
</body>

</html>