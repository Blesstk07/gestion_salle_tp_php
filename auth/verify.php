<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$users = [
    "admin" => "1234",
    "apparitorat" => "0000"
];

if (isset($users[$username]) && $users[$username] === $password) {

    // étape 1 validée
    $_SESSION['temp_user'] = $username;

    header("Location: biometric.php");
    exit;

} else {
    echo "
    <div style='text-align:center;font-family:Arial;margin-top:100px;'>
        <h2 style='color:red;'>❌ Identifiants incorrects</h2>
        <a href='login.php'>Retour</a>
    </div>";
}
?>