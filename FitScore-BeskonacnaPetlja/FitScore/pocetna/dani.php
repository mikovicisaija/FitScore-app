<?php
session_start();
include("../konekcija/konekcija.php");

if(isset($_GET['year']) && isset($_GET['month'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];
    $email = $_SESSION['email'];

    $sql = "SELECT datum, voda, san, aktivnost_vreme 
            FROM fitscore_aktivnosti 
            WHERE email = ? 
              AND YEAR(datum) = ? 
              AND MONTH(datum) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $email, $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    $days = [];
    while($row = $result->fetch_assoc()) {
        // === RAČUNANJE POENA ===
        $vodaPoeni = min(($row['voda'] / 2.5) * 30, 30); // 2,5 L = 30 poena
        $sanPoeni = min(($row['san'] / 8) * 30, 30);     // 8h = 30 poena
        $aktivnostPoeni = min(($row['aktivnost_vreme'] / 60) * 40, 40); // 60min = 40 poena

        $ukupnoPoeni = $vodaPoeni + $sanPoeni + $aktivnostPoeni;

        // Odredi status
        if($ukupnoPoeni >= 70) {
            $status = "good";
        } elseif($ukupnoPoeni >= 40) {
            $status = "partial";
        } else {
            $status = "bad";
        }

        $days[$row['datum']] = $status;
    }

    echo json_encode($days);
}
?>