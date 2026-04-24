<?php

$data = json_decode(file_get_contents("data/planning.json"), true);

if ($data === null) $data = [];

$new = [
    "cours_id" => $_POST['cours_id'],
    "salle_id" => $_POST['salle_id'],
    "jour" => $_POST['jour'],
    "heure_debut" => $_POST['heure_debut'],
    "heure_fin" => $_POST['heure_fin'],
    "groupe" => $_POST['groupe']
];

$data[] = $new;

file_put_contents("data/planning.json", json_encode($data, JSON_PRETTY_PRINT));

header("Location: index.php");
exit;
?>