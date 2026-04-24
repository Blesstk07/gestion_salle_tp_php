<?php
// ===============================
// 🎓 SGA - INDEX PRINCIPAL
// ===============================

require_once "fonctions.php";

// ===============================
// 📁 CHARGEMENT DES DONNÉES
// ===============================

$salles = charger_salles("data/salles.json");
$cours = charger_cours("data/cours.json");
$planning = json_decode(file_get_contents("data/planning.json"), true);

// ===============================
// 🎨 STYLE CSS DE BASE
// ===============================
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SGA - Planning</title>

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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .badge {
            padding: 4px 8px;
            background: #b30000;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🎓 Système de Gestion des Auditoires</h1>

    <!-- ===================== -->
    <!-- 📅 PLANNING -->
    <!-- ===================== -->

    <h2>📅 Planning Hebdomadaire</h2>

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
                // trouver nom du cours
                $nom_cours = "";
                foreach ($cours as $c) {
                    if ($c['id'] == $p['cours_id']) {
                        $nom_cours = $c['intitule'];
                    }
                }

                // trouver salle
                $nom_salle = "";
                foreach ($salles as $s) {
                    if ($s['id'] == $p['salle_id']) {
                        $nom_salle = $s['designation'];
                    }
                }
            ?>

            <tr>
                <td><?= $p['jour'] ?></td>
                <td><?= $p['heure_debut'] . " - " . $p['heure_fin'] ?></td>
                <td><span class="badge"><?= $nom_salle ?></span></td>
                <td><?= $nom_cours ?></td>
                <td><?= $p['groupe'] ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

    <!-- ===================== -->
    <!-- 🏫 SALLES -->
    <!-- ===================== -->

    <h2>🏫 Salles disponibles</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Capacité</th>
        </tr>

        <?php foreach ($salles as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= $s['designation'] ?></td>
                <td><?= $s['capacite'] ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>