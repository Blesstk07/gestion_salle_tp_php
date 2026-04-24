<?php
// ===============================
// 🏫 Traitement d'ajout des salles
// ===============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================
    // Récupérer les données du formulaire
    // =========================

    $id = trim($_POST['id']);
    $designation = trim($_POST['designation']);
    $capacite = (int) $_POST['capacite'];

    // =========================
    // Test de validation 
    // =========================

    if (empty($id) || empty($designation) || $capacite <= 0) {
        die("❌ Erreur : données invalides");
    }

    // =========================
    // Charger les salles à partir du fichier JSON
    // =========================

    $fichier = "data/salles.json";

    if (!file_exists($fichier)) {
        $salles = [];
    } else {
        $salles = json_decode(file_get_contents($fichier), true);

        if ($salles === null) {
            die("❌ Fichier salles.json corrompu");
        }
    }

    // =========================
    // Test doublon des ID de salle
    // =========================

    foreach ($salles as $salle) {
        if ($salle['id'] === $id) {
            die("❌ Cette salle existe déjà !");
        }
    }

    // =========================
    // Ajouter la nouvelle salle au tableau
    // =========================

    $nouvelle_salle = [
        "id" => $id,
        "designation" => $designation,
        "capacite" => $capacite
    ];

    $salles[] = $nouvelle_salle;

    // =========================
    // Enregistrer le tableau mis à jour dans le fichier JSON
    // =========================

    file_put_contents(
        $fichier,
        json_encode($salles, JSON_PRETTY_PRINT)
    );

    // =========================
    // Envoyer un message de succès à l'utilisateur
    // =========================

    echo "<h2 style='color:green'>✅ Salle ajoutée avec succès !</h2>";
    echo "<a href='formulaire_salle.html'>← Retour</a>";
}

?>