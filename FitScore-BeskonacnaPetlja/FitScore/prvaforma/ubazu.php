<?php
session_start();
include "../konekcija/konekcija.php";

if (!isset($_SESSION['email'])) {
    die("Niste ulogovani");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_SESSION['email'];

    $ciljevi = isset($_POST['ciljevi'])
        ? implode(",", $_POST['ciljevi'])
        : "";

    $aktivnost = $_POST['aktivnost'] ?? "";
    $iskustvo  = $_POST['iskustvo'] ?? "";
    $datum     = $_POST['datum_rodjenja'] ?? "";
    $visina    = (int)$_POST['visina'];
    $tezina    = (float)$_POST['tezina'];
    $pol       = $_POST['pol'] ?? "";

    $stmt = $conn->prepare("
        INSERT INTO fitscore_upitnik
        (email, ciljevi, aktivnost, iskustvo, datum_rodjenja, visina, tezina, pol)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            ciljevi = VALUES(ciljevi),
            aktivnost = VALUES(aktivnost),
            iskustvo = VALUES(iskustvo),
            datum_rodjenja = VALUES(datum_rodjenja),
            visina = VALUES(visina),
            tezina = VALUES(tezina),
            pol = VALUES(pol)
    ");

    $stmt->bind_param(
        "sssssdds",
        $email,
        $ciljevi,
        $aktivnost,
        $iskustvo,
        $datum,
        $visina,
        $tezina,
        $pol
    );

    if ($stmt->execute()) {
        header("Location: ../pocetna/pocetna.php");
        exit;
    } else {
        echo "Greška pri snimanju.";
    }
}
?>
