<?php

$id = $_GET['id'];

$planning = json_decode(file_get_contents("data/planning.json"), true);

if ($planning === null) $planning = [];

// supprimer index
unset($planning[$id]);

// réindexer tableau
$planning = array_values($planning);

file_put_contents("data/planning.json", json_encode($planning, JSON_PRETTY_PRINT));

header("Location: index.php");
exit;
?>