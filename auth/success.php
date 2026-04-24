<?php
session_start();

if (isset($_SESSION['temp_user'])) {
    $_SESSION['user'] = $_SESSION['temp_user'];
    unset($_SESSION['temp_user']);
}

header("Location: ../index.php");
exit;