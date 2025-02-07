<?php

session_start();

try {
    // Connexion à la base de données
    $clspit = new PDO("mysql:host=localhost;dbname=wh100255_users;charset=utf8", 'wh100255_users', 'JnBWzvKMydIy');
    $clspit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        header('Location: index.php'); // Correction de l'espace entre location et :
        exit;
    }




    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) $_POST['id'];

        // Requête pour mettre à jour l'état de la commande
        $stmt = $clspit->prepare("UPDATE coiffures SET etat = 'terminée' WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Redirection vers la page des commandes terminées
        header("Location: index5.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
