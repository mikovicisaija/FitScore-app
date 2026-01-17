<?php
session_start();
include("../konekcija/konekcija.php");

if(isset($_GET['datum'])) {
    $datum = $_GET['datum'];
    $email = $_SESSION['email']; // pretpostavljam da čuvaš email u sesiji

    $sql = "SELECT voda, san, aktivnost_opis, aktivnost_vreme 
            FROM fitscore_aktivnosti 
            WHERE email = ? AND datum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $datum);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }
}
?>
