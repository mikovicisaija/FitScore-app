<?php
session_start();
include "../konekcija/konekcija.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ime = isset($_POST['ime']) ? trim($_POST['ime']) : '';
    $korisnicko = isset($_POST['korisnicko_ime']) ? trim($_POST['korisnicko_ime']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $lozinka = isset($_POST['lozinka']) ? $_POST['lozinka'] : '';

    // Validacija
    if (empty($ime) || empty($korisnicko) || empty($email) || empty($lozinka)) {
        echo "<script>alert('Sva polja su obavezna!'); window.history.back();</script>";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Neispravan email!'); window.history.back();</script>";
        exit;
    }
    if (strlen($lozinka) < 6) {
        echo "<script>alert('Lozinka mora imati najmanje 6 karaktera!'); window.history.back();</script>";
        exit;
    }

    // Provera da li email već postoji
    $stmt = $conn->prepare("SELECT email FROM clan WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                alert('Korisnik sa tim emailom već postoji!');
                window.location.href = '../Log in/signup.php';
              </script>";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Hash lozinke
    $hash = password_hash($lozinka, PASSWORD_DEFAULT);

    // Ubacivanje u bazu
    $sql = "INSERT INTO clan (email, korime, ime, lozinka) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $korisnicko, $ime, $hash);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Postavljanje sesije
    $_SESSION["email"] = $email;
    $_SESSION["ime"] = $ime;

    // Redirekcija nakon uspešne registracije
    header("Location: ../prvaforma/prvaforma.php");
    exit;
}
?>
