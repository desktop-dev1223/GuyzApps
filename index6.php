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



    // Initialiser la variable pour la date
    $dateFiltre = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Récupérer le nombre de commandes en cours pour la date sélectionnée
    $stmtEnCours = $clspit->prepare("SELECT COUNT(*) FROM coiffures WHERE etat = 'en cours' AND DATE(heure_depart) = :date");
    $stmtEnCours->execute(['date' => $dateFiltre]);
    $commandesEnCours = $stmtEnCours->fetchColumn();

    // Récupérer le nombre de commandes terminées pour la date sélectionnée
    $stmtTerminees = $clspit->prepare("SELECT COUNT(*) FROM coiffures WHERE etat = 'terminée' AND DATE(heure_depart) = :date");
    $stmtTerminees->execute(['date' => $dateFiltre]);
    $commandesTerminees = $stmtTerminees->fetchColumn();

    // Récupérer le total des revenus des commandes terminées pour la date sélectionnée
    $stmtRevenus = $clspit->prepare("SELECT SUM(prix) FROM coiffures WHERE etat = 'terminée' AND DATE(heure_depart) = :date");
    $stmtRevenus->execute(['date' => $dateFiltre]);
    $totalRevenus = $stmtRevenus->fetchColumn();

    // Récupérer les types de coiffures les plus populaires pour la date sélectionnée
    $stmtTypesCoiffures = $clspit->prepare("SELECT type_coiffure, COUNT(*) AS total FROM coiffures WHERE DATE(heure_depart) = :date GROUP BY type_coiffure ORDER BY total DESC");
    $stmtTypesCoiffures->execute(['date' => $dateFiltre]);
    $typesCoiffures = $stmtTypesCoiffures->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les horaires les plus populaires pour la date sélectionnée
    $stmtHoraires = $clspit->prepare("SELECT heure_depart, COUNT(*) AS total FROM coiffures WHERE DATE(heure_depart) = :date GROUP BY heure_depart ORDER BY total DESC");
    $stmtHoraires->execute(['date' => $dateFiltre]);
    $horaires = $stmtHoraires->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Salon de Coiffure</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="index6.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Tableau de Bord du Salon de Coiffure</h2>

    <!-- Formulaire de filtre par date -->
    <form method="GET" class="mb-4 text-center">
        <label for="date" class="form-label">Sélectionnez une date :</label>
        <input type="date" id="date" name="date" class="form-control d-inline-block w-auto" value="<?= htmlspecialchars($dateFiltre) ?>">
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    <!-- Résumé des informations -->
    <div class="row mb-4">
        <div class="col-md-4 g-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Commandes en Cours</h5>
                    <p class="card-text"><?= $commandesEnCours ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 g-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Commandes Terminées</h5>
                    <p class="card-text"><?= $commandesTerminees ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 g-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Revenus Totaux (FCFA)</h5>
                    <p class="card-text"><?= number_format($totalRevenus, 2, ',', ' ') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des types de coiffures -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Répartition des Types de Coiffures</h5>
            <canvas id="chartTypesCoiffures"></canvas>
        </div>
    </div>

    <!-- Graphique des horaires -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Répartition des Horaires</h5>
            <canvas id="chartHoraires"></canvas>
        </div>
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
        <a href="index5.php" class="btn btn-dark">Commande terminées</a>
        <a href="index7.php" class="btn btn-dark">Voir performance du coiffeur</a>
        <a href="index9.php" class="btn btn-dark">liste des clients</a>
        <a href="index10.php" class="btn btn-dark">Réservation des clients</a>
       <a href="index-s.php" class="btn btn-dark">Retour au poste de pilotage</a>
   
        
    </div>


</center>






<script>
    // Graphique des Types de Coiffures
    var ctxTypesCoiffures = document.getElementById('chartTypesCoiffures').getContext('2d');
    var chartTypesCoiffures = new Chart(ctxTypesCoiffures, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($typesCoiffures, 'type_coiffure')) ?>,
            datasets: [{
                label: 'Nombre de Commandes',
                data: <?= json_encode(array_column($typesCoiffures, 'total')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des Horaires
    var ctxHoraires = document.getElementById('chartHoraires').getContext('2d');
    var chartHoraires = new Chart(ctxHoraires, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($horaires, 'heure_depart')) ?>,
            datasets: [{
                label: 'Nombre de Commandes',
                data: <?= json_encode(array_column($horaires, 'total')) ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0','#858AD6','#1F235C','#95758A','#91CA92'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>
</html>
