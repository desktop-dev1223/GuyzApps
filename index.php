<?php
session_start(); // Démarre la session pour pouvoir manipuler $_SESSION

// Initialisation du message d'erreur
$error_message = "";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Identifiants valides (ces valeurs ne doivent pas être en dur en production)
    $connexion_user = "G*U*Y*Z*O";
    $connexion_pass = "G*U*Y*Z*O";

    // Vérification des identifiants
    if ($username === $connexion_user && $password === $connexion_pass) {
        // Connexion réussie, stocker l'utilisateur dans la session
        $_SESSION['user'] = $username;  // Stocke le nom d'utilisateur dans la session
        header('Location: index-s.php');  // Redirection après connexion
        exit;
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="index.css">

</head>
<body>

    
        <form action="" method="post">
            <h1>Connexion</h1>
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Entrer le pseudo" required>
        
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Entrer le mot de passe" required>
        
        <button type="submit" class="btn btn-primary">Se connecter</button>
    
        
        
            <?php if (!empty($error_message)) : ?>
                <div class="message-error">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
        </form>

</body>
</html>
