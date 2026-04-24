<?php

require_once "fonctions.php";

// ===============================
//  CHARGEMENT DES DONNÉES
// ===============================

$salles = charger_salles("data/salles.json");
$cours = charger_cours("data/cours.json");

$planning = [];

if (file_exists("data/planning.json")) {
    $planning = json_decode(file_get_contents("data/planning.json"), true);

    if ($planning === null) {
        $planning = [];
    }
}

// ===============================
//  ANALYSE (CONFLITS + RAPPORT)
// ===============================

$conflits = detecter_conflits($planning);
$rapport = generer_rapport_occupation($planning, $salles);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planning des Salles</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #b30000;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #b30000;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th {
            background: #b30000;
            color: white;
            padding: 10px;
        }

        td {
            border: 1px solid #b30000;
            padding: 10px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #ffe6e6;
        }

        .badge {
            padding: 4px 8px;
            background: #b30000;
            color: white;
            border-radius: 5px;
        }

        .alert {
            text-align: center;
            padding: 15px;
            background: #ffe6e6;
            border: 1px solid #b30000;
            margin-top: 20px;
        }

        .success {
            background: #e6ffe6;
            border-color: green;
            color: green;
        }

        pre {
            background: #fff0f0;
            padding: 15px;
            border: 1px solid #b30000;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🎓 Système de Gestion des Auditoires (SGA)</h1>

    <!-- =============================== -->
    <!--  PLANNING -->
    <!-- =============================== -->

    <h2> Planning Hebdomadaire</h2>

    <?php if (empty($planning)): ?>

        <div class="alert">
            Aucun planning disponible. Veuillez générer le planning.
        </div>

    <?php else: ?>

        <table>
            <tr>
                <th>Jour</th>
                <th>Heure</th>
                <th>Salle</th>
                <th>Cours</th>
                <th>Groupe</th>
            </tr>

            <?php foreach ($planning as $p): ?>

                <?php
                    // cours
                    $nom_cours = $p['cours_id'];
                    foreach ($cours as $c) {
                        if ($c['id'] === $p['cours_id']) {
                            $nom_cours = $c['intitule'];
                            break;
                        }
                    }

                    // salle
                    $nom_salle = $p['salle_id'];
                    foreach ($salles as $s) {
                        if ($s['id'] === $p['salle_id']) {
                            $nom_salle = $s['designation'];
                            break;
                        }
                    }
                ?>

                <tr>
                    <td><?= htmlspecialchars($p['jour']) ?></td>
                    <td><?= htmlspecialchars($p['heure_debut'] . " - " . $p['heure_fin']) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($nom_salle) ?></span></td>
                    <td><?= htmlspecialchars($nom_cours) ?></td>
                    <td><?= htmlspecialchars($p['groupe']) ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

    <!-- =============================== -->
    <!--  CONFLITS -->
    <!-- =============================== -->

    <h2> Analyse des conflits</h2>

    <?php if (empty($conflits)): ?>

        <div class="alert success">
            ✅ Aucun conflit détecté dans le planning
        </div>

    <?php else: ?>

        <div class="alert">
            ❌ <?= count($conflits) ?> conflit(s) détecté(s)
        </div>

        <ul>
            <?php foreach ($conflits as $c): ?>
                <li><?= $c ?></li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <!-- =============================== -->
    <!-- RAPPORT -->
    <!-- =============================== -->

    <h2> Rapport d'occupation des salles</h2>

    <pre><?= $rapport ?></pre>

    <!-- =============================== -->
    <!--  SALLES -->
    <!-- =============================== -->

    <h2> Salles disponibles</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Capacité</th>
        </tr>

        <?php foreach ($salles as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['id']) ?></td>
                <td><?= htmlspecialchars($s['designation']) ?></td>
                <td><?= htmlspecialchars($s['capacite']) ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>