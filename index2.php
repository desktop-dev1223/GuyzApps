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

    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire avec sécurisation
        $nom = htmlspecialchars(trim($_POST['nom']));
        $telephone = htmlspecialchars(trim($_POST['telephone']));
        $type_coiffure = htmlspecialchars(trim($_POST['type_coiffure']));
        $prix = (float) $_POST['prix'];
        $nom_coiffeur = htmlspecialchars(trim($_POST['nom_coiffeur']));

        // Ajouter la date actuelle et convertir les heures en format DATETIME
        $date_aujourdhui = date("Y-m-d");
        $heure_depart = date("Y-m-d H:i:s", strtotime("$date_aujourdhui {$_POST['heure_depart']}:00"));
        $heure_fin = date("Y-m-d H:i:s", strtotime("$date_aujourdhui {$_POST['heure_fin']}:00"));

        // Vérifier si le client existe déjà dans la base de données
        $stmt = $clspit->prepare("SELECT id FROM clients WHERE telephone = :telephone");
        $stmt->execute([':telephone' => $telephone]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si le client n'existe pas, l'ajouter à la table clients
        if (!$client) {
            $stmt = $clspit->prepare("INSERT INTO clients (nom_client, telephone) VALUES (:nom_client, :telephone)");
            $stmt->execute([
                ':nom_client' => $nom,
                ':telephone' => $telephone
            ]);
            // Récupérer l'ID du client ajouté
            $client_id = $clspit->lastInsertId();
        } else {
            // Si le client existe déjà, utiliser son ID
            $client_id = $client['id'];
        }

        // Vérifier si le coiffeur existe déjà
        $stmt = $clspit->prepare("SELECT id FROM coiffeurs WHERE nom_coiffeur = :nom_coiffeur");
        $stmt->execute([':nom_coiffeur' => $nom_coiffeur]);
        $coiffeur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si le coiffeur n'existe pas, l'ajouter à la table coiffeurs
        if (!$coiffeur) {
            $stmt = $clspit->prepare("INSERT INTO coiffeurs(nom_coiffeur) VALUES (:nom_coiffeur)");
            $stmt->execute([ ':nom_coiffeur' => $nom_coiffeur ]);
            // Récupérer l'ID du coiffeur ajouté
            $coiffeur_id = $clspit->lastInsertId();
        } else {
            // Si le coiffeur existe déjà, utiliser son ID
            $coiffeur_id = $coiffeur['id'];
        }

        // Ajouter la commande du client avec les informations liées
        $stmt = $clspit->prepare("INSERT INTO coiffures (client_id, nom_client, telephone, type_coiffure, prix, coiffeur_id, nom_coiffeur, heure_depart, heure_fin, etat) 
                                  VALUES (:client_id, :nom_client, :telephone, :type_coiffure, :prix, :coiffeur_id, :nom_coiffeur, :heure_depart, :heure_fin, 'en cours')");
        $stmt->execute([
            ':client_id' => $client_id,          // Utilisation de l'ID du client
            ':nom_client' => $nom,              // Nom du client
            ':telephone' => $telephone,         // Téléphone du client
            ':type_coiffure' => $type_coiffure, // Type de coiffure demandé
            ':prix' => $prix,                   // Prix de la coiffure
            ':coiffeur_id' => $coiffeur_id,     // ID du coiffeur
            ':nom_coiffeur' => $nom_coiffeur,   // Nom du coiffeur
            ':heure_depart' => $heure_depart,   // Heure de début de la prestation
            ':heure_fin' => $heure_fin,         // Heure de fin de la prestation
        ]);

        // Redirection vers la page des commandes en cours
        header("Location: index3.php");
        exit;
    }

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
