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



    // Récupérer la date actuelle
    $dateFiltre = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Requête pour filtrer les commandes terminées par date
    $stmt = $clspit->prepare("SELECT * FROM coiffures WHERE etat = 'terminée' AND DATE(heure_depart) = :date");
    $stmt->bindParam(':date', $dateFiltre);
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
    <title>Commandes Terminées - Salon de Coiffure</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="index5.css">
</head>
<body class="bg-light">




<center>
    <form method="GET" action="" class="mb-4 mt-3">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="date" class="col-form-label">Filtrer par date :</label>
            </div>
            <div class="col-auto">
                <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($dateFiltre) ?>" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </div>
    </form>
</center>



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
                            </div>
                        </div>
                        <?php $numeroCommande++; ?>
                    <?php endforeach; ?>
                </div> <!-- Fin de la grille -->
            <?php endif; ?>
        </form>
    </div>
</div>

    
    
    <div class="text-center mt-4">
        
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Voir Menu</button>
    
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Top bouton</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    
    
    <div class="text-center mt-1">
        <a href="index.php" class="btn btn-primary">Retour aux commandes</a>
        <a href="index3.php" class="btn btn-secondary">Commandes en cours</a>
        <a href="index6.php" class="btn btn-dark">Tableau de bord</a>
        <a href="index7.php" class="btn btn-dark">Voir performance du coiffeur</a>
        <a href="index9.php" class="btn btn-dark">liste des clients</a>
       <a href="index-s.php" class="btn btn-dark">Retour au poste de pilotage</a>
     

    </div>


</center>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>
</html>
