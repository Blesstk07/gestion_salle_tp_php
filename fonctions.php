<?php
// ===============================
// BACKEND
// ===============================

// Lire les fichiers json du dossier data et les convertir en tableaux associatifs PHP.//
function lire_json($chemin_fichier) {

    if (!file_exists($chemin_fichier)) {
        die(" Fichier introuvable : " . $chemin_fichier);
    }

    $contenu = file_get_contents($chemin_fichier);
    $data = json_decode($contenu, true);

    if ($data === null) {
        die(" JSON invalide : " . $chemin_fichier);
    }

    return $data;
}
// Chargement des données avec des fonctions de direction //

function charger_salles($chemin) {
    return lire_json($chemin);
}

function charger_promotions($chemin) {
    return lire_json($chemin);
}

function charger_cours($chemin) {
    return lire_json($chemin);
}

function charger_options($chemin) {
    return lire_json($chemin);
}

?>