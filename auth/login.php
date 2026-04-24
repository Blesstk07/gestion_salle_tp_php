<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion SGA</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:linear-gradient(135deg,#4b1d6b,#b48a00);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:white;
    padding:30px;
    border-radius:15px;
    width:320px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

h2{
    color:#4b1d6b;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
}

button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:linear-gradient(90deg,#4b1d6b,#b48a00);
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}
</style>

</head>

<body>

<div class="card">

<h2>🔐 Connexion SGA</h2>

<form method="POST" action="verify.php">

<input type="text" name="username" placeholder="Nom d'utilisateur" required>

<input type="password" name="password" placeholder="Mot de passe" required>

<button type="submit">Se connecter</button>

</form>

</div>

</body>
</html>