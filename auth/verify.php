<?php
session_start();

// ===============================
// 🔹 RÉCUPÉRATION DES DONNÉES
// ===============================
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// ===============================
// 🔹 CHARGEMENT DES UTILISATEURS
// ===============================
$usersFile = "../data/users.json";

if (!file_exists($usersFile)) {
    die("❌ Fichier users.json introuvable");
}

$users = json_decode(file_get_contents($usersFile), true);

if ($users === null) {
    die("❌ Erreur de lecture du fichier users.json");
}

// ===============================
// 🔹 VÉRIFICATION DES IDENTIFIANTS
// ===============================
$userFound = null;

foreach ($users as $user) {
    if (
        $user['username'] === $username &&
        $user['password'] === $password
    ) {
        $userFound = $user;
        break;
    }
}

// ===============================
// 🔹 TRAITEMENT DU RÉSULTAT
// ===============================
if ($userFound) {

    // session temporaire avant biométrie
    $_SESSION['temp_user'] = $userFound['username'];
    $_SESSION['temp_role'] = $userFound['role'];

    // redirection vers biométrie JS
    header("Location: biometric.html");
    exit;

} else {

    echo "
    <div style='
        text-align:center;
        margin-top:100px;
        font-family:Arial;
    '>

        <h2 style='color:red;'>❌ Identifiants incorrects</h2>

        <p>Veuillez vérifier vos informations</p>

        <a href='login.php' style='
            display:inline-block;
            margin-top:15px;
            padding:10px 15px;
            background:#4b1d6b;
            color:white;
            text-decoration:none;
            border-radius:8px;
        '>
            🔙 Retour
        </a>

    </div>
    ";
}