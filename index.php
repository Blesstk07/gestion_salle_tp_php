<?php
session_start();

if (!isset($_SESSION['temp_user']) && !isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

// validation finale après biométrie
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = $_SESSION['temp_user'];
    unset($_SESSION['temp_user']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGA Dashboard</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f5f2ff;
}

/* HEADER */
header{
    background:linear-gradient(90deg,#4b1d6b,#b48a00);
    color:white;
    padding:20px;
    text-align:center;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:10px;
    padding:15px;
}

.card{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    text-align:center;
}

/* BUTTONS */
.actions{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    padding:15px;
}

.btn{
    flex:1;
    min-width:140px;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#4b1d6b;
    color:white;
    font-weight:bold;
    text-align:center;
    text-decoration:none;
}

.btn:hover{
    background:#b48a00;
}

/* MOBILE */
@media(max-width:600px){
    header{font-size:14px;}
}
</style>
</head>

<body>

<header>
    🎓 SGA Dashboard - Bienvenue <?= htmlspecialchars($_SESSION['user']) ?>
</header>

<!-- QUICK ACTIONS -->
<div class="actions">
    <a class="btn" href="ajouter_planning.php">➕ Ajouter cours</a>
    <a class="btn" href="planning.php">📅 Planning</a>
    <a class="btn" href="salles.php">🏫 Salles</a>
    <a class="btn" href="export_pdf.php">📄 Export PDF</a>
</div>

<!-- STATS -->
<div class="grid">
    <div class="card">📅 Planning</div>
    <div class="card">⚠️ Conflits</div>
    <div class="card">🏫 Salles</div>
    <div class="card">📊 Rapport</div>
</div>

</body>
</html>