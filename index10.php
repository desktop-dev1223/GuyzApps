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

    // Récupérer la liste de tous les coiffeurs
    $stmt = $pdo->prepare("SELECT id, nom_coiffeur FROM coiffeurs ORDER BY nom_coiffeur ASC");
    $stmt->execute();
    $coiffeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $message = "";

    // Gérer la soumission du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom_client = htmlspecialchars($_POST['nom_client']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $id_coiffeur = (int) $_POST['id_coiffeur'];
        $date_reservation = $_POST['date_reservation'];
        $heure_reservation = $_POST['heure_reservation'];

        // Insérer la réservation
        $stmt = $pdo->prepare("INSERT INTO reservations (nom_client, telephone, id_coiffeur, date_reservation, heure_reservation, etat)
                              VALUES (?, ?, ?, ?, ?, 'En attente')");
        $stmt->execute([$nom_client, $telephone, $id_coiffeur, $date_reservation, $heure_reservation]);

        $message = "Votre réservation a été enregistrée avec succès !";
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
    <title>Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-3">Réserver une Coiffure</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <form action="index11.php" method="POST" class="p-4 border rounded shadow bg-white">
        <div class="mb-3">
            <label class="form-label">Nom du Client</label>
            <input type="text" name="nom_client" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Choisir un Coiffeur</label>
            <select name="id_coiffeur" class="form-control" required>
                <?php foreach ($coiffeurs as $coiffeur): ?>
                    <option value="<?= $coiffeur['id'] ?>"><?= htmlspecialchars($coiffeur['nom_coiffeur']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de Réservation</label>
            <input type="date" name="date_reservation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Heure de Réservation</label>
            <input type="time" name="heure_reservation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Réserver</button>
    </form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
