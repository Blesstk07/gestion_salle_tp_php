<?php
require_once "fonctions.php";

$planning = file_exists("data/planning.json")
    ? json_decode(file_get_contents("data/planning.json"), true)
    : [];

if ($planning === null) $planning = [];

// nouveau cours
$new = [
    "cours_id" => $_POST['cours_id'],
    "salle_id" => $_POST['salle_id'],
    "jour" => $_POST['jour'],
    "heure_debut" => $_POST['heure_debut'],
    "heure_fin" => $_POST['heure_fin'],
    "groupe" => $_POST['groupe']
];

// 🔍 vérification conflits
foreach ($planning as $p) {

    // même salle + même jour + overlap horaire
    if (
        $p['salle_id'] == $new['salle_id'] &&
        $p['jour'] == $new['jour'] &&
        !($new['heure_fin'] <= $p['heure_debut'] || $new['heure_debut'] >= $p['heure_fin'])
    ) {
        die("❌ Conflit détecté : salle déjà occupée !");
    }

    // même groupe clash
    if (
        $p['groupe'] == $new['groupe'] &&
        $p['jour'] == $new['jour'] &&
        !($new['heure_fin'] <= $p['heure_debut'] || $new['heure_debut'] >= $p['heure_fin'])
    ) {
        die("❌ Conflit détecté : groupe déjà en cours !");
    }
}

$planning[] = $new;

file_put_contents("data/planning.json", json_encode($planning, JSON_PRETTY_PRINT));

header("Location: index.php");
exit;
?>