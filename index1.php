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

    // Récupérer la liste des coiffeurs disponibles
    $stmt = $pdo->prepare("SELECT id, nom_coiffeur FROM coiffeurs ORDER BY nom_coiffeur ASC");
    $stmt->execute();
    $coiffeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon de Coiffure - Formulaire</title>
    <!-- Lien Bootstrap pour un style moderne -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="index1.css">
</head>

<body class="bg-light">

<center>

<div class="container-fluid mt-2">

  
<h2 class="text-center mb-4">Formulaire de commande</h2>
    

    <form action="index2.php" method="POST" class="forms">


    <div class="row">
<div class="col-sm-12 col-md-12 col-md-12 ">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom du client</label>
        <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom du client" required>
    </div>

</div>
<div class="col-sm-12 col-md-12 col-md-12">
    <div class="mb-3">
        <label for="telephone" class="form-label">Numéro de téléphone du client</label>
        <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="Exemple : 0123456789" required pattern="^\d{10}$">
        <div class="form-text">Le numéro doit comporter 10 chiffres.</div>
    </div>

</div>
    </div>
    
    <!-- Nom du client -->

        <!-- Numéro de téléphone -->

        <!-- Type de coiffure -->
        <div class="mb-3">
            <label for="type_coiffure" class="form-label">Type de coiffure</label>
            <input type="text" name="type_coiffure" id="type_coiffure" class="form-control" placeholder="Exemple : Tresses, Coupe, etc." required>
        </div>

        <!-- Prix de la coiffure -->
        <div class="mb-3">
            <label for="prix" class="form-label">Prix de la coiffure (FCFA)</label>
            <input type="number" name="prix" id="prix" class="form-control" placeholder="Entrez le prix en FCFA" required min="1">
        </div>


<div class="mb-3">
            <label class="form-label">Choisir un coiffeur</label>
            <select name="id_coiffeur" class="form-control" required>
        <?php foreach ($coiffeurs as $coiffeur): ?>
            <option value="<?= $coiffeur['id'] ?>"><?= htmlspecialchars($coiffeur['nom_coiffeur']) ?></option>
        <?php endforeach; ?>
    </select>
</div>


        <!-- Heure de départ -->
        <div class="mb-3">
            <label for="heure_depart" class="form-label">Heure de départ</label>
            <input type="time" name="heure_depart" id="heure_depart" class="form-control" required>
        </div>

        <!-- Heure de fin -->
        <div class="mb-3">
            <label for="heure_fin" class="form-label">Heure de fin</label>
            <input type="time" name="heure_fin" id="heure_fin" class="form-control" required>
        </div>




        <!-- Boutons de soumission et réinitialisation -->
        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
        <button type="reset" class="btn btn-secondary w-100 mt-3">Réinitialiser</button>



<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Voir Menu</button>

<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasTopLabel">Top bouton</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

<div class="text-center mt-1">
    <button type="submit" class="btn btn-cmd-1" style="">
       <a href="index3.php">Voir commande en cours</a>
    </button>
    
    <button type="submit" class="btn btn-cmd-2" style="">  
     <a href="index5.php">Voir commande terminées</a>
    </button>
  
      <button type="submit" class="btn btn-tb" style="">
         <a href="index6.php">Tableau de bord</a>
      </button>
      

      <a href="index9.php" class="btn btn-dark">liste des clients</a>


      <button type="submit" class="btn btn-cmd-2" style="">  
      <a href="index9.php" class="btn btn-dark">liste des clients</a>
      </button>

  
      <button type="submit" class="btn btn-cmd-2" style="">  
       <a href="index-s.php">Retour au poste de pilotage</a>
      </button>
</div>





    </form>
</div>

    
</center>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
