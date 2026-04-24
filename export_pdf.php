<?php

$planning = json_decode(file_get_contents("data/planning.json"), true);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=planning.xls");

echo "Jour\tHeure\tSalle\tCours\tGroupe\n";

foreach ($planning as $p) {
    echo $p['jour']."\t".
         $p['heure_debut']."-".$p['heure_fin']."\t".
         $p['salle_id']."\t".
         $p['cours_id']."\t".
         $p['groupe']."\n";
}

exit;
?>