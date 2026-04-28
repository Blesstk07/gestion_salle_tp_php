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

header{
    background:linear-gradient(90deg,#4b1d6b,#b48a00);
    color:white;
    padding:20px;
    text-align:center;
}

.menu{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    padding:15px;
}

.menu a{
    flex:1;
    min-width:140px;
    padding:12px;
    background:#4b1d6b;
    color:white;
    text-align:center;
    border-radius:10px;
    text-decoration:none;
}

.menu a:hover{
    background:#b48a00;
}
</style>
</head>

<body>

<header>
    🎓 Bienvenue <?= htmlspecialchars($_SESSION['user']) ?>
</header>

<div class="menu">
    <a href="ajouter_planning.php">➕ Ajouter cours</a>
    <a href="planning.php">📅 Planning</a>
    <a href="salles.php">🏫 Salles</a>
    <a href="export_pdf.php">📄 Export</a>
</div>

</body>
</html>