<?php
session_start();
include("../konekcija/konekcija.php");

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/* =========================
   AUTO LOGIN PREKO COOKIE
   ========================= */
if (!isset($_SESSION['ime']) && isset($_COOKIE['remember_me'])) {

    $token = $_COOKIE['remember_me'];

    $stmt = $conn->prepare(
        "SELECT ime, email FROM clan WHERE remember_token = ?"
    );
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $rez = $stmt->get_result();

    if ($rez->num_rows === 1) {
        $user = $rez->fetch_assoc();
        $_SESSION['ime'] = $user['ime'];
        $_SESSION['email'] = $user['email'];
        header("Location: ../index.php");
        exit;
    }
}

/* =========================
   KLASIČNA PRIJAVA (POST)
   ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = test_input($_POST['email'] ?? '');
    $lozinka = $_POST['lozinka'] ?? '';
    $remember = isset($_POST['remember_me']);

    if (empty($email) || empty($lozinka)) {
        echo "<script>
                alert('Morate uneti email i lozinku!');
                window.location.href = '../Log in/login.php';
              </script>";
        exit;
    }

    $stmt = $conn->prepare(
        "SELECT email, ime, lozinka FROM clan WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $rez = $stmt->get_result();

    if ($rez->num_rows === 1) {

        $user = $rez->fetch_assoc();

        if (password_verify($lozinka, $user['lozinka'])) {

            // SESSION
            $_SESSION['ime'] = $user['ime'];
            $_SESSION['email'] = $user['email'];

            // REMEMBER ME
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie("remember_me", $token, time() + (60*60*24*30), "/");

                $stmt = $conn->prepare(
                    "UPDATE clan SET remember_token = ? WHERE email = ?"
                );
                $stmt->bind_param("ss", $token, $user['email']);
                $stmt->execute();
            }

            header("Location: ../pocetna/pocetna.php");
            exit;

        } else {
            echo "<script>
                    alert('Pogrešna lozinka!');
                    window.location.href = '../Log in/login.php';
                  </script>";
            exit;
        }

    } else {
        echo "<script>
                alert('Ne postoji korisnik sa tim emailom!');
                window.location.href = '../Log in/login.php';
              </script>";
        exit;
    }
}
?>
