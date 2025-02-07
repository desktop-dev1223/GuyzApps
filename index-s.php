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
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon de coiffure | Guyzo</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">

            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                
                <div class="sidebar-logo">
                    <a href="#">Guyz<span>Apps</span></a>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="index1.php" class="sidebar-link">
                        <i class="fi fi-br-shopping-bag"></i>
                        <span>Passer une commande</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="index3.php" class="sidebar-link">
                        <i class="fi fi-br-time-quarter-past"></i>
                        <span>Commande en cours</span>
                    </a>
                </li>


             <li class="sidebar-item">
                    <a href="index5.php" class="sidebar-link">
                        <i class="fi fi-br-check"></i>
                        <span>Commande terminées</span>
                    </a>
                </li>




             <li class="sidebar-item">
                    <a href="index6.php" class="sidebar-link">
                        <i class="fi fi-br-dashboard"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>


            <li class="sidebar-item">
                    <a href="index7.php" class="sidebar-link">
                         <i class="fi fi-br-stats"></i>
                        <span>Performance des coiffeurs</span>
                    </a>
                </li>

            <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                       <i class="fi fi-br-user"></i>
                        <span>Réservation des clients</span>
                    </a>
                </li>


            <div class="sidebar-footer">
                <a href="index8.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Deconnexion</span>
                </a>
            </div>



        </aside>


        <div class="main p-3">

            <div class="text-center">
                <h1>
                  Bienvenue dans le poste de pilotage de GuyzApps
                </h1>
            </div>

    
    <div class="container mt-5">
    
<h2 class="text-center">Bilan du jour</h2>

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

<div class="center">

    <div class="container mt-4">
    
        <?php if (!empty($coiffeurs)): ?>
          <h1 class="h1-table">LISTE DES COIFFEURS ET LEUR SCORE DE LA JOURNEE</h1>
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
    </div>

</div>





    <div class="row">
    
    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
     <a href="index1.php" class="sidebar-main">
            <i class="fi fi-br-shopping-bag"></i>
            <span style="color: black; padding: 10px;">Passer une commande</span>
         </a>
    
    </div>
    
    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index3.php" class="sidebar-main">
            <i class="fi fi-br-time-quarter-past"></i>
            <span style="color: black; padding: 10px;">Commande en cours</span>
         </a>
    </div>
    
    
    
    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index5.php" class="sidebar-main">
        <i class="fi fi-br-check"></i>
         <span style="color: black; padding: 10px;">Commande terminées</span>
        </a>
    </div>
    
    
    
    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index6.php" class="sidebar-main">
         <i class="fi fi-br-dashboard"></i>
         <span style="color: black; padding: 10px;">Tableau de bord</span>
        </a>
    </div>
    



    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index7.php" class="sidebar-main">
         <i class="fi fi-br-stats"></i>
         <span style="color: black; padding: 10px;">Performance des coiffeurs</span>
        </a>
    </div>



    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index9.php" class="sidebar-main">
       <i class="fi fi-br-user"></i>
         <span style="color: black; padding: 10px;">Liste des clients</span>
        </a>
    </div>


    <div class="col-sm-6 col-md-6 col-lg-6 g-5">
         <a href="index10.php" class="sidebar-main">
       <i class="fi fi-br-user"></i>
         <span style="color: black; padding: 10px;">Réservation des clients</span>
        </a>
    </div>
    
    
    </div>
    
    
    </div>



        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <script src="script.js"></script>
</body>

</html>