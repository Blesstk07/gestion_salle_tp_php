<?php
require_once "fonctions.php";

$salles = charger_salles("data/salles.json");
$cours = charger_cours("data/cours.json");

$planning = [];

if (file_exists("data/planning.json")) {
    $planning = json_decode(file_get_contents("data/planning.json"), true);
    if ($planning === null) $planning = [];
}

$conflits = detecter_conflits($planning);
$rapport = generer_rapport_occupation($planning, $salles);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>SGA Dashboard</title>

<style>
body {
    margin:0;
    font-family: "Segoe UI", sans-serif;
    background: linear-gradient(135deg, #f7f2ff, #fffaf0);
    color:#3b2c4a;
}

/* HEADER */
header {
    background: linear-gradient(90deg, #5a2a82, #a37b00);
    color:white;
    padding:25px;
    text-align:center;
    border-bottom:4px solid #d4af37;
}

header h1 {
    margin:0;
    font-size:28px;
}

header p {
    margin-top:8px;
    font-size:14px;
    opacity:0.9;
}

/* DASHBOARD */
.container {
    max-width:1200px;
    margin:auto;
    padding:20px;
}

.grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap:20px;
    margin-top:20px;
}

/* CARDS */
.card {
    background:white;
    border-radius:12px;
    padding:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    border-left:5px solid #a37b00;
}

.card h3 {
    margin-top:0;
    color:#5a2a82;
}

/* BUTTONS */
.btn {
    display:inline-block;
    margin-top:10px;
    padding:10px 15px;
    background:#5a2a82;
    color:white;
    border-radius:8px;
    text-decoration:none;
    transition:0.3s;
}

.btn:hover {
    background:#a37b00;
}

/* BADGES */
.badge {
    background:#a37b00;
    color:white;
    padding:4px 8px;
    border-radius:5px;
}

/* ALERT */
.alert {
    padding:15px;
    border-radius:10px;
    margin-top:15px;
    background:#fff3cd;
    border:1px solid #d4af37;
}

.success {
    background:#e8f5e9;
    border-color:#4caf50;
}

/* TABLE */
table {
    width:100%;
    margin-top:15px;
    border-collapse:collapse;
}

th {
    background:#5a2a82;
    color:white;
    padding:10px;
}

td {
    border:1px solid #ddd;
    padding:8px;
    text-align:center;
}

tr:nth-child(even){
    background:#f8f4ff;
}
</style>

</head>

<body>

<header>
    <h1>🎓 Système de Gestion des Auditoires</h1>
    <p>Bienvenue dans votre tableau de bord universitaire intelligent</p>
</header>

<div class="container">

<!-- ================= DASHBOARD CARDS ================= -->
<div class="grid">

    <div class="card">
        <h3>📅 Planning</h3>
        <p>Consulter l'emploi du temps</p>
        <a class="btn" href="#planning">Voir</a>
    </div>

    <div class="card">
        <h3>⚠️ Conflits</h3>
        <p><?= count($conflits) ?> conflit(s) détecté(s)</p>
        <a class="btn" href="#conflits">Analyser</a>
    </div>

    <div class="card">
        <h3>📊 Rapport</h3>
        <p>Occupation des salles</p>
        <a class="btn" href="#rapport">Voir rapport</a>
    </div>

    <div class="card">
        <h3>🏫 Salles</h3>
        <p>Gestion des auditoires</p>
        <a class="btn" href="#salles">Afficher</a>
    </div>

</div>

<!-- ================= PLANNING ================= -->
<h2 id="planning">📅 Planning</h2>

<?php if (empty($planning)): ?>
<div class="alert">Aucun planning disponible</div>
<?php else: ?>

<table>
<tr>
    <th>Jour</th><th>Heure</th><th>Salle</th><th>Cours</th><th>Groupe</th>
</tr>

<?php foreach ($planning as $p): ?>
<tr>
    <td><?= $p['jour'] ?></td>
    <td><?= $p['heure_debut']." - ".$p['heure_fin'] ?></td>
    <td><span class="badge"><?= $p['salle_id'] ?></span></td>
    <td><?= $p['cours_id'] ?></td>
    <td><?= $p['groupe'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

<!-- ================= CONFLITS ================= -->
<h2 id="conflits">⚠️ Conflits</h2>

<?php if (empty($conflits)): ?>
<div class="alert success">✔ Aucun conflit détecté</div>
<?php else: ?>
<div class="alert">❌ <?= count($conflits) ?> conflit(s)</div>
<ul>
<?php foreach ($conflits as $c): ?>
<li><?= $c ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<!-- ================= RAPPORT ================= -->
<h2 id="rapport">📊 Rapport d'occupation</h2>
<pre><?= $rapport ?></pre>

<!-- ================= SALLES ================= -->
<h2 id="salles">🏫 Salles</h2>

<table>
<tr><th>ID</th><th>Nom</th><th>Capacité</th></tr>

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