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

    $date_filtre = isset($_GET['date_filtre']) ? $_GET['date_filtre'] : "";

    // Requête pour récupérer les réservations avec filtre de date
    $query = "
        SELECT r.id, r.nom_client, r.telephone, r.date_reservation, r.heure_reservation, r.etat, 
               c.nom_coiffeur 
        FROM reservations r
        LEFT JOIN coiffeurs c ON r.id_coiffeur = c.id
    ";

    if (!empty($date_filtre)) {
        $query .= " WHERE r.date_reservation = :date_filtre ";
    }

    $query .= " ORDER BY r.date_reservation DESC, r.heure_reservation ASC";
    $stmt = $pdo->prepare($query);

    if (!empty($date_filtre)) {
        $stmt->bindParam(':date_filtre', $date_filtre);
    }

    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-3">Liste des Réservations</h2>

    <!-- Formulaire de filtre -->
    <form method="GET" class="d-flex justify-content-center mb-3">
        <input type="date" name="date_filtre" class="form-control w-auto me-2" value="<?= htmlspecialchars($date_filtre) ?>">
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="index11.php" class="btn btn-secondary ms-2">Réinitialiser</a>
    </form>

    <div class="row">
    <?php if (empty($reservations)): ?>
        <p class="text-center text-muted">Aucune réservation trouvée.</p>
    <?php else: ?>
        <div class="col-12">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Nom du Client</th>
                        <th>Téléphone</th>
                        <th>Coiffeur</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['nom_client']) ?></td>
                            <td><?= htmlspecialchars($reservation['telephone']) ?></td>
                            <td><?= htmlspecialchars($reservation['nom_coiffeur']) ?></td>
                            <td><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                            <td><?= htmlspecialchars($reservation['heure_reservation']) ?></td>
                            <td>
                                <select class="form-control status-change" data-id="<?= $reservation['id'] ?>">
                                    <option value="En attente" <?= $reservation['etat'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="Confirmée" <?= $reservation['etat'] == 'Confirmée' ? 'selected' : '' ?>>Confirmée</option>
                                    <option value="Annulée" <?= $reservation['etat'] == 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</div>

<script>
$(document).ready(function() {
    $(".status-change").change(function() {
        var reservationId = $(this).data("id");
        var newStatus = $(this).val();

        $.ajax({
            url: "index12.php",
            type: "POST",
            data: { id: reservationId, etat: newStatus },
            success: function(response) {
                alert("Statut mis à jour avec succès !");
            },
            error: function() {
                alert("Erreur lors de la mise à jour du statut.");
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
