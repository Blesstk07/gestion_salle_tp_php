<?php
session_start();

/* ===============================
   🔐 PROTECTION SESSION
================================ */
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $_SESSION['role'] ?? 'user';

require_once "fonctions.php";

/* ===============================
   📁 CHARGEMENT DONNÉES
================================ */
$salles = charger_salles("data/salles.json");
$cours = charger_cours("data/cours.json");

$planning = file_exists("data/planning.json")
    ? json_decode(file_get_contents("data/planning.json"), true)
    : [];

if (!is_array($planning)) $planning = [];

/* ===============================
   ⚠️ ANALYSE
================================ */
$conflits = detecter_conflits($planning);
$rapport = generer_rapport_occupation($planning, $salles);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>SGA - Dashboard</title>

<style>

/* ================= GLOBAL ================= */
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:linear-gradient(135deg,#f7f2ff,#fffaf0);
    display:flex;
}

/* ================= SIDEBAR ================= */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#4b1d6b,#a37b00);
    color:white;
    padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:10px;
}

.user-box{
    text-align:center;
    font-size:14px;
    margin-bottom:20px;
    opacity:0.9;
}

.sidebar a{
    display:block;
    color:white;
    padding:10px;
    margin:8px 0;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.2);
    transform:translateX(5px);
}

/* ================= MAIN ================= */
.main{
    margin-left:260px;
    padding:20px;
    width:100%;
}

/* HEADER */
header{
    background:linear-gradient(90deg,#5a2a82,#b48a00);
    color:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

header h1{
    margin:0;
}

/* ================= CARDS ================= */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
    margin-top:20px;
}

.card{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    border-left:4px solid #b48a00;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 12px;
    background:#4b1d6b;
    color:white;
    border-radius:8px;
    text-decoration:none;
}

/* ================= SECTIONS ================= */
section{
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th{
    background:#4b1d6b;
    color:white;
    padding:10px;
}

td{
    text-align:center;
    padding:8px;
    border-bottom:1px solid #eee;
}

/* ================= FORM ================= */
.form-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

input,select{
    padding:10px;
    border-radius:10px;
    border:2px solid #eee;
}

.btn-submit{
    margin-top:15px;
    padding:12px;
    background:linear-gradient(90deg,#4b1d6b,#b48a00);
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

/* ================= ALERT ================= */
.alert{
    padding:12px;
    background:#fff3cd;
    border-left:5px solid #b48a00;
    border-radius:8px;
}

.success{
    background:#e8f5e9;
    border-left-color:#4caf50;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>🎓 SGA</h2>

    <div class="user-box">
        👤 <?= htmlspecialchars($user) ?><br>
        🔰 <?= htmlspecialchars($role) ?>
    </div>

    <a href="#dashboard">📊 Dashboard</a>
    <a href="#planning">📅 Planning</a>
    <a href="#ajout">➕ Ajouter</a>
    <a href="#conflits">⚠️ Conflits</a>
    <a href="#rapport">📈 Rapport</a>
    <a href="auth/logout.php">🚪 Déconnexion</a>

</div>

<!-- MAIN -->
<div class="main">

<header id="dashboard">
    <h1>🎓 Système de Gestion des Auditoires</h1>
    <p>Bienvenue dans votre espace de gestion intelligent</p>
</header>

<!-- CARDS -->
<div class="grid">

<div class="card">
    <h3>📅 Planning</h3>
    <p><?= count($planning) ?> cours</p>
</div>

<div class="card">
    <h3>⚠️ Conflits</h3>
    <p><?= count($conflits) ?></p>
</div>

<div class="card">
    <h3>🏫 Salles</h3>
    <p><?= count($salles) ?></p>
</div>

<div class="card">
    <h3>📄 Export</h3>
    <a class="btn" href="export_pdf.php">Télécharger</a>
</div>

</div>

<!-- PLANNING -->
<section id="planning">
<h2>📅 Planning</h2>

<table>
<tr>
<th>Jour</th><th>Heure</th><th>Salle</th><th>Cours</th><th>Groupe</th>
</tr>

<?php foreach ($planning as $p): ?>
<tr>
<td><?= htmlspecialchars($p['jour']) ?></td>
<td><?= htmlspecialchars($p['heure_debut']." - ".$p['heure_fin']) ?></td>
<td><?= htmlspecialchars($p['salle_id']) ?></td>
<td><?= htmlspecialchars($p['cours_id']) ?></td>
<td><?= htmlspecialchars($p['groupe']) ?></td>
</tr>
<?php endforeach; ?>

</table>
</section>

<!-- AJOUT -->
<section id="ajout">

<h2>➕ Ajouter un cours</h2>

<div class="form-card">

<form action="ajouter_planning.php" method="POST">

<div class="form-grid">

<select name="cours_id">
<?php foreach($cours as $c): ?>
<option value="<?= $c['id'] ?>"><?= $c['intitule'] ?></option>
<?php endforeach; ?>
</select>

<select name="salle_id">
<?php foreach($salles as $s): ?>
<option value="<?= $s['id'] ?>"><?= $s['designation'] ?></option>
<?php endforeach; ?>
</select>

<input name="jour" placeholder="Jour">

<input type="time" name="heure_debut">

<input type="time" name="heure_fin">

<input name="groupe" placeholder="Groupe">

</div>

<button class="btn-submit">➕ Ajouter</button>

</form>

</div>

</section>

<!-- CONFLITS -->
<section id="conflits">
<h2>⚠️ Conflits</h2>

<?php if(empty($conflits)): ?>
<div class="alert success">Aucun conflit détecté</div>
<?php else: ?>
<div class="alert"><?= count($conflits) ?> conflit(s)</div>
<?php foreach($conflits as $c): ?>
<p><?= htmlspecialchars($c) ?></p>
<?php endforeach; ?>
<?php endif; ?>

</section>

<!-- RAPPORT -->
<section id="rapport">
<h2>📊 Rapport</h2>
<pre><?= htmlspecialchars($rapport) ?></pre>
</section>

</div>

</body>
</html>