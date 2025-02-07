<?php

session_start();

try {
    // Connexion à la base de données
    $clspit = new PDO("mysql:host=localhost;dbname=wh100255_users;charset=utf8", 'wh100255_users', 'JnBWzvKMydIy');
    $clspit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }

    // Requête pour obtenir toutes les commandes en cours
    $stmt = $clspit->prepare("SELECT *, nom_coiffeur FROM coiffures WHERE etat = 'en cours'");
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes en Cours - Salon de Coiffure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index3.css">
</head>


<body class="bg-light">


<div class="container mt-4">
    <h2 class="text-center mb-3">Commandes en Cours</h2>

    <div class="commandes-container">
        <form action="index4.php" method="POST">
            <?php if (empty($commandes)): ?>
                <p class="text-center text-muted">Aucune commande en cours.</p>
            <?php else: ?>
                <div class="row"> <!-- Début de la grille -->
                    <?php $numeroCommande = 1; ?>
                    <?php foreach ($commandes as $commande): ?>
                        <div class="col-12 col-md-6 col-lg-4"> <!-- Responsive: 1 colonne mobile, 2 sur tablette, 3 sur PC -->
                            <div class="commande-card p-3 border rounded shadow bg-white text-center">
                                <h5 class="text-primary">Commande <?= $numeroCommande ?></h5>
                                <p><strong>Client :</strong> <?= htmlspecialchars($commande['nom_client']) ?></p>
                                <p><strong>Téléphone :</strong> <?= htmlspecialchars($commande['telephone']) ?></p>
                                <p><strong>Coiffure :</strong> <?= htmlspecialchars($commande['type_coiffure']) ?></p>
                                <p><strong>Prix :</strong> <?= number_format($commande['prix'], 2, ',', ' ') ?> FCFA</p>
                                <p><strong>Coiffeur :</strong> <?= htmlspecialchars($commande['nom_coiffeur']) ?></p>
                                <p><strong>Départ :</strong> <?= $commande['heure_depart'] ?></p>
                                <p><strong>Fin :</strong> <?= $commande['heure_fin'] ?></p>
                                <p><strong>État :</strong> <?= $commande['etat'] ?></p>

                                <input type="hidden" name="id" value="<?= $commande['id'] ?>">
                                <button type="submit" class="btn btn-danger w-100">Terminer</button>
                            </div>
                        </div>
                        <?php $numeroCommande++; ?>
                    <?php endforeach; ?>
                </div> <!-- Fin de la grille -->
            <?php endif; ?>
        </form>
    </div>
</div>

    
    <div class="text-center mt-1">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Voir Menu</button>
    
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Top bouton</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    
    
<div class="text-center mt-4">
     <a href="index.php" class="btn btn-primary">Retour aux commandes</a>
     <a href="index5.php" class="btn btn-secondary">Commandes terminées</a>
     <a href="index6.php" class="btn btn-dark">Tableau de bord</a>
     <a href="index7.php" class="btn btn-dark">Voir performance du coiffeur</a>
     <a href="index9.php" class="btn btn-dark">liste des clients</a>
     <a href="index-s.php" class="btn btn-dark" >Retour au poste de pilotage</a>
 
</div>


</center>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
