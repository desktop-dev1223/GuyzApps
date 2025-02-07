<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['etat'])) {
    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=localhost;dbname=wh100255_users;charset=utf8", 'wh100255_users', 'JnBWzvKMydIy');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = (int)$_POST['id'];
        $etat = $_POST['etat'];

        // Mettre à jour le statut
        $stmt = $pdo->prepare("UPDATE reservations SET etat = ? WHERE id = ?");
        $stmt->execute([$etat, $id]);

        echo "Statut mis à jour avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Données invalides.";
}
?>
