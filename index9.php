<?php
session_start();

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=wh100255_users;charset=utf8", 'wh100255_users', 'JnBWzvKMydIy');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }

    // Requête pour obtenir tous les clients avec le nombre de coiffures
    $stmt = $pdo->prepare("
        SELECT c.id, c.nom_client, c.telephone, 
               COUNT(h.id) AS nb_coiffures 
        FROM clients c
        LEFT JOIN coiffures h ON c.id = h.client_id
        GROUP BY c.id, c.nom_client, c.telephone
        ORDER BY c.nom_client ASC
    ");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Stocker les clients fidèles dans un tableau
    $clientsFideles = [];
    foreach ($clients as $client) {
        if ($client['nb_coiffures'] >= 10) {
            $clientsFideles[] = $client;
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index9.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-3">Liste des Clients</h2>

    <!-- Barre de recherche -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un client..." onkeyup="searchClients()">

    <div class="row">
    <?php if (empty($clients)): ?>
        <p class="text-center text-muted">Aucun client trouvé.</p>
    <?php else: ?>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Numéro de téléphone</th>
                        <th>Nombre de coiffures</th>
                        <th>Client Fidèle</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= htmlspecialchars($client['nom_client']) ?></td>
                            <td><?= htmlspecialchars($client['telephone']) ?></td>
                            <td><?= htmlspecialchars($client['nb_coiffures']) ?></td>
                            <td><?= ($client['nb_coiffures'] >= 10) ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>





<center>
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
        <a href="index6.php" class="btn btn-dark">Tableau de bord</a>
        <a href="index10.php" class="btn btn-dark">Réservation des clients</a>
        <a href="index-s.php" class="btn btn-dark">Retour au poste de pilotage</a>
   
        
    </div>


</center>







<!-- Script de recherche -->
<script>
function searchClients() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        let name = row.getElementsByTagName("td")[0].textContent.toLowerCase();
        if (name.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
</script>









<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
