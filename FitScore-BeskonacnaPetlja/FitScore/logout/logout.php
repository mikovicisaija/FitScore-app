<?php
session_start();
include "../Konekcija/konekcija.php";

// Obrisi remember me ako postoji
if (isset($_COOKIE['remember_me']) && isset($_SESSION['email'])) {
    $stmt = $conn->prepare(
        "UPDATE clan SET remember_token = NULL WHERE email = ?"
    );
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();

    setcookie("remember_me", "", time() - 3600, "/");
}

// Uništi sesiju
session_unset();
session_destroy();

// Odredi gde ide korisnik
$action = $_GET['action'] ?? 'logout';

if ($action === 'switch') {
    header("Location: ../Log in/login.php");
} else {
    header("Location: ../index.html");
}
exit;