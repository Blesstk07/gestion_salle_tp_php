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
<title>SGA Dashboard Pro</title>

<style>
body{
    margin:0;
    font-family: "Segoe UI", sans-serif;
    background: linear-gradient(135deg, #f8f5ff, #fffaf0);
    color:#2d1b3d;
}

/* ================= SIDEBAR ================= */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    padding:20px;
    background: linear-gradient(180deg, #4b1d6b, #8a6b00);
    color:white;
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
}

.sidebar h2{
    text-align:center;
    font-size:22px;
    letter-spacing:1px;
    margin-bottom:30px;
    color:#fff;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    transition:0.3s ease;
    font-weight:500;
}

.sidebar a:hover{
    background: rgba(255,255,255,0.15);
    transform: translateX(5px);
}

/* ================= MAIN ================= */
.main{
    margin-left:260px;
    padding:25px;
}

/* ================= HEADER ================= */
header{
    background: linear-gradient(90deg, #5a2a82, #b48a00);
    color:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

header h1{
    margin:0;
    font-size:28px;
}

header p{
    margin-top:8px;
    opacity:0.9;
}

/* ================= CARDS ================= */
.grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap:20px;
    margin-top:20px;
}

.card{
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius:15px;
    padding:20px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    border-left:5px solid #b48a00;
    transition:0.3s ease;
}

.card:hover{
    transform: translateY(-5px);
    box-shadow:0 12px 30px rgba(0,0,0,0.12);
}

.card h3{
    margin:0;
    color:#4b1d6b;
}

/* ================= BUTTONS ================= */
.btn{
    display:inline-block;
    margin-top:12px;
    padding:10px 14px;
    background: linear-gradient(90deg, #5a2a82, #b48a00);
    color:white;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
    transform: scale(1.05);
}

/* ================= SECTIONS ================= */
section{
    margin-top:35px;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    overflow:hidden;
    border-radius:10px;
}

th{
    background: linear-gradient(90deg, #5a2a82, #b48a00);
    color:white;
    padding:12px;
    font-weight:500;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:center;
}

tr:hover{
    background:#f7f0ff;
}

/* ================= ALERT ================= */
.alert{
    padding:15px;
    border-radius:12px;
    margin-top:10px;
    background:#fff3cd;
    border-left:5px solid #b48a00;
}

.success{
    background:#e8f5e9;
    border-left-color:#4caf50;
}
</style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h2>Creneau Horaire</h2>

    <a href="#dashboard">📊 Dashboard</a>
    <a href="#planning">📅 Planning</a>
    <a href="#conflits">⚠️ Conflits</a>
    <a href="#rapport">📈 Rapport</a>
    <a href="#salles">🏫 Salles</a>
</div>

<!-- ================= MAIN ================= -->
<div class="main">

<header id="dashboard">
    <h1>🎓 Tableau de bord SGA</h1>
    <p>Système intelligent de gestion des auditoires</p>
</header>

<!-- ================= CARDS ================= -->
<div class="grid">

    <div class="card">
        <h3>📅 Planning</h3>
        <p>Consulter les cours</p>
        <a class="btn" href="#planning">Ouvrir</a>
    </div>

    <div class="card">
        <h3>⚠️ Conflits</h3>
        <p><?= count($conflits) ?> détectés</p>
        <a class="btn" href="#conflits">Analyser</a>
    </div>

    <div class="card">
        <h3>📈 Rapport</h3>
        <p>Occupation des salles</p>
        <a class="btn" href="#rapport">Voir</a>
    </div>

    <div class="card">
        <h3>🏫 Salles</h3>
        <p>Gestion auditoires</p>
        <a class="btn" href="#salles">Afficher</a>
    </div>

</div>

<!-- ================= PLANNING ================= -->
<section id="planning">
<h2>📅 Planning</h2>

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
<td><?= $p['salle_id'] ?></td>
<td><?= $p['cours_id'] ?></td>
<td><?= $p['groupe'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>
</section>

<!-- ================= CONFLITS ================= -->
<section id="conflits">
<h2>⚠️ Conflits</h2>

<?php if (empty($conflits)): ?>
<div class="alert success">✔ Aucun conflit détecté</div>
<?php else: ?>
<div class="alert">❌ <?= count($conflits) ?> conflits</div>
<ul>
<?php foreach ($conflits as $c): ?>
<li><?= $c ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</section>

<!-- ================= RAPPORT ================= -->
<section id="rapport">
<h2>📈 Rapport d'occupation</h2>
<pre><?= $rapport ?></pre>
</section>

<!-- ================= SALLES ================= -->
<section id="salles">
<h2>🏫 Salles</h2>

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
</section>

</div>
</body>
</html>