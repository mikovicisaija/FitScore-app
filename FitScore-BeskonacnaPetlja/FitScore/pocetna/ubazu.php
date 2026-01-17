<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../konekcija/konekcija.php");

if (!isset($_SESSION['email'])) {
    die("Morate biti ulogovani.");
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voda = isset($_POST['water']) ? floatval($_POST['water']) : 0;
    $san = isset($_POST['sleep']) ? floatval($_POST['sleep']) : 0;
    $aktivnost_opis = isset($_POST['activity']) ? trim($_POST['activity']) : '';
    $aktivnost_vreme = isset($_POST['activity_time']) ? intval($_POST['activity_time']) : 0;
    $datum = isset($_POST['datum']) ? $_POST['datum'] : date('Y-m-d');

    if ($aktivnost_opis === '') {
        die("Morate izabrati aktivnost.");
    }

    $stmt = $conn->prepare("
        INSERT INTO fitscore_aktivnosti 
        (email, datum, voda, san, aktivnost_opis, aktivnost_vreme) 
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        voda=VALUES(voda), san=VALUES(san), aktivnost_opis=VALUES(aktivnost_opis), aktivnost_vreme=VALUES(aktivnost_vreme)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssddsi", $email, $datum, $voda, $san, $aktivnost_opis, $aktivnost_vreme);

    if ($stmt->execute()) {
        header("Location: pocetna.php");
    } else {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
