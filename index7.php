<?php
session_start();

try {
    // Connexion à la base de données
    $clspit = new PDO("mysql:host=localhost;dbname=wh100255_users;charset=utf8", 'wh100255_users', 'JnBWzvKMydIy');
    $clspit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si une date est sélectionnée, sinon prendre la date du jour
    $selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Requête SQL pour récupérer les coiffeurs distincts et compter le nombre d'occurrences pour une date donnée
    $stmt = $clspit->prepare("SELECT c.nom_coiffeur, COUNT(*) AS nombre_occurrences
                              FROM coiffures c
                              WHERE DATE(c.date_creation) = :selected_date
                              GROUP BY c.nom_coiffeur");
    $stmt->bindParam(':selected_date', $selected_date);
    $stmt->execute();
    $coiffeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Coiffeurs et Nombre d'Occurrences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Inclusion de Chart.js -->
    <link rel="stylesheet" href="index7.css">
</head>
<body>
    
    <center>
        <form method="GET" class="mb-3">
            <label for="date">Sélectionner une date :</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($selected_date) ?>">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>

    </center>


    <div class="dashboard-container">
        <h1 >LISTE DES COIFFEURS ET LEUR SCORE DE LA JOURNEE</h1>

        <?php if (!empty($coiffeurs)): ?>
            <table>
                <thead>
                    <tr>
                        <th>NOM DES COIFFEURS</th>
                        <th>SCORE DE LA JOURNEE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coiffeurs as $coiffeur): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($coiffeur['nom_coiffeur']); ?></td>
                            <td><?php echo $coiffeur['nombre_occurrences']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun coiffeur trouvé.</p>
        <?php endif; ?>

        <!-- Canvas pour afficher le graphique -->
        <h2>Graphique des Coiffeurs</h2>
        <canvas id="chartCoiffeurs" width="400" height="200"></canvas>

        <script>
            // Graphique des Coiffeurs - Linéaire (Line Chart)
            var ctxCoiffeurs = document.getElementById('chartCoiffeurs').getContext('2d');
            var chartCoiffeurs = new Chart(ctxCoiffeurs, {
                type: 'line', // Utilisation du graphique linéaire
                data: {
                    labels: <?= json_encode(array_column($coiffeurs, 'nom_coiffeur')) ?>, // Les noms des coiffeurs
                    datasets: [{
                        label: 'Nombre d\'Occurrences',
                        data: <?= json_encode(array_column($coiffeurs, 'nombre_occurrences')) ?>, // Le nombre d'occurrences pour chaque coiffeur
                        borderColor: 'rgba(54, 162, 235, 1)', // Couleur de la ligne
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Couleur de l'ombre sous la ligne
                        fill: true, // Remplir la zone sous la courbe
                        tension: 0.4, // Arrondir la courbe de la ligne (si besoin)
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true, // Commencer l'axe Y à zéro
                            title: {
                                display: true,
                                text: 'Nombre d\'Occurrences' // Titre de l'axe Y
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Nom du Coiffeur' // Titre de l'axe X
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top', // Position de la légende
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' occurrences'; // Affichage des données au survol
                                }
                            }
                        }
                    }
                }
            });
        </script>
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
        <a href="index-s.php" class="btn btn-dark">Retour au poste de pilotage</a>
   
        
    </div>


</center>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>
</html>
