<?php
session_start();
include("../konekcija/konekcija.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../Log in/login.php");
    exit();
}


$email = $_SESSION['email'];

/* POVLAČENJE PODATAKA */
$sql = "
SELECT 
    c.ime,
    c.korime,
    c.email,
    f.datum_rodjenja,
    f.visina,
    f.tezina
FROM clan c
LEFT JOIN fitscore_upitnik f ON c.email = f.email
WHERE c.email = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE LIČNI PODACI (IME, KORIME, EMAIL) */
if (isset($_POST['update_clan'])) {
    $ime = $_POST['ime'];
    $korime = $_POST['korime'];
    $noviEmail = $_POST['email'];

    // Proveri da li je novi email već zauzet
    if ($noviEmail !== $email) {
        $check = $conn->prepare("SELECT email FROM clan WHERE email=?");
        $check->bind_param("s", $noviEmail);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo "<p style='color:red; text-align:center'>Ovaj email je već zauzet!</p>";
            exit;
        }
    }

    // UPDATE podataka
    $stmt = $conn->prepare("UPDATE clan SET ime=?, korime=?, email=? WHERE email=?");
    $stmt->bind_param("ssss", $ime, $korime, $noviEmail, $email);
    $stmt->execute();

    // Osveži sesiju ako je email promenjen
    $_SESSION['email'] = $noviEmail;

    header("Location: podesavanja.php");
    exit;
}

/* UPDATE LOZINKE */
if (isset($_POST['update_lozinka'])) {
    $stara = $_POST['stara'];
    $nova = $_POST['nova'];
    $potvrda = $_POST['potvrda'];

    if ($nova === $potvrda) {
        $stmt = $conn->prepare("SELECT lozinka FROM clan WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $hash = $stmt->get_result()->fetch_assoc()['lozinka'];

        if (password_verify($stara, $hash)) {
            $novaHash = password_hash($nova, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE clan SET lozinka=? WHERE email=?");
            $upd->bind_param("ss", $novaHash, $email);
            $upd->execute();
        }
    }

    header("Location: podesavanja.php");
    exit;
}

/* UPDATE PODACI O TELU */
if (isset($_POST['update_telo'])) {
    $datum = $_POST['datum_rodjenja'];
    $visina = $_POST['visina'];
    $tezina = $_POST['tezina'];

    $stmt = $conn->prepare("
        INSERT INTO fitscore_upitnik (email, datum_rodjenja, visina, tezina)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            datum_rodjenja=VALUES(datum_rodjenja),
            visina=VALUES(visina),
            tezina=VALUES(tezina)
    ");
    $stmt->bind_param("ssii", $email, $datum, $visina, $tezina);
    $stmt->execute();

    header("Location: podesavanja.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Podešavanja</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --radius-big: 3rem;
    --radius-mid: 2rem;
    --gray-light: #f2f2f2;
    --gray-mid: #cfcfcf;
    --gray-dark: #d9d9d9;
    --red: #d9534f;
}

/* RESET */
* {
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
}

/* BODY */
body {
    background: #ffffff;
    color: #333;
}

header {
  background: #fff;
  border-bottom: 2px solid #e63946;
  padding: 15px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 100;
}

/* NAV RIGHT — linkovi i profil */
.nav-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

/* Osnovni stil linkova */
.nav-right a {
  position: relative;
  color: #e63946;
  text-decoration: none;
  font-weight: 500;
}

/* Underline efekat */
.nav-right a::after {
  content: "";
  position: absolute;
  bottom: -3px;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #e63946;
  transition: width 0.3s ease;
}

/* Hover i active efekti */
.nav-right a:hover::after,
.nav-right a.active::after {
  width: 100%;
}

.nav-right a.active {
  font-weight: 600;
}

/* PROFIL SLIKA */
.nav-right img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 50%;
  cursor: pointer;
}

/* HAMBURGER TOGGLE */
.nav-toggle {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 28px;
  height: 21px;
  cursor: pointer;
}

.nav-toggle span {
  display: block;
  height: 3px;
  width: 100%;
  background: #e63946;
  border-radius: 2px;
  transition: all 0.3s;
}

/* Animacija hamburger ikone kada je aktivan */
.nav-toggle.active span:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.nav-toggle.active span:nth-child(2) {
  opacity: 0;
}

.nav-toggle.active span:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -5px);
}

/* OVERLAY iza menija */
.nav-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.3);
  z-index: 998;
  transition: opacity 0.3s ease;
}

/* Kada je meni otvoren */
.nav-overlay.show {
  display: block;
  opacity: 1;
}

/* CONTAINER */
.container {
    width: 85%;
    max-width: 1200px;
    margin: 40px auto;
}

/* HEADER PODEŠAVANJA */
.header-settings {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 40px;
}

/* SECTION */
.section {
    width: 100%;
    background: var(--gray-mid);
    border-radius: var(--radius-big);
    display: flex;
    overflow: hidden;
    margin-bottom: 5vh;
}

/* LEFT */
.section-left {
    width: 40%;
    background: var(--gray-light);
    padding: 4vh 3vw;
}

.section-left h2 {
    font-size: 2.2rem;
    margin-bottom: 2vh;
}

.section-left p {
    font-size: 1.2rem;
    line-height: 1.6;
}

/* RIGHT */
.section-right {
    width: 60%;
    padding: 4vh 3vw;
    display: flex;
    flex-direction: column;
    gap: 2vh;
}

/* LABEL */
label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5vh;
}

/* INPUT */
input, select {
    width: 100%;
    padding: 1.4vh 1vw;
    font-size: 1.4rem;
    border-radius: 1rem;
    border: none;
    background: #e6e6e6;
    margin-bottom: 2vh;
}

/* ROW */
.row {
    display: flex;
    gap: 3vw;
}
.row div {
    width: 100%;
}

/* BUTTON */
button {
    width: 100%;
    padding: 1.8vh;
    font-size: 1.5rem;
    border: none;
    border-radius: 1rem;
    background: var(--red);
    color: white;
    cursor: pointer;
}

button:hover {
    background: #c9302c;
}

/* FOOTER */
footer {
  margin-top: 60px;
  background: #111;
  color: #ccc;
  padding: 30px;
  text-align: center;
}

/* RESPONSIVE MOBILE */
@media (max-width: 900px) {
    .section {
        flex-direction: column;
    }

    .section-left,
    .section-right {
        width: 100%;
    }

    .row {
        flex-direction: column;
        gap: 1.5vh;
    }

    .header-settings {
        font-size: 2rem;
        text-align: center;
    }
}

.profile-dropdown {
  position: absolute;
  top: 50px;
  right: 0;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  width: 160px;
  display: none;
  flex-direction: column;
  overflow: hidden;
  z-index: 100;
}

.profile-dropdown a {
  padding: 12px 15px;
  text-decoration: none;
  color: #333;
  font-size: 14px;
  transition: background 0.2s;
}

.profile-dropdown a:hover {
  background: #f2f2f2;
}

.profile-dropdown .logout {
  color: #e63946;
  font-weight: 600;
}

/* RESPONSIVNOST */
@media (max-width: 900px) {
  .nav-right {
    position: fixed;       /* fiksiran meni */
    top: 0;
    right: -250px;         /* sakriven van ekrana desno */
    width: 220px;
    height: 100vh;          /* puni ekran visine */
    background: #fff;
    flex-direction: column;
    align-items: flex-start;
    padding: 80px 15px 15px 15px; /* top padding da se ne preklapa sa headerom */
    gap: 20px;
    transition: right 0.3s ease;
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
    border-radius: 10px 0 0 10px;
    z-index: 999;
  }

  .nav-right.show {
    right: 0;  /* pojavljuje se sa desne strane */
  }

  /* Prikaži hamburger dugme */
  .nav-toggle {
    display: flex;
    margin-left: auto;
  }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<header>
  <img src="../images/image.png" height="50px" alt="Logo">
  <nav class="nav-right">
    <a href="../pocetna/pocetna.php">Početna</a>
    <a href="../podesavanj/podesavanja.php">Podešavanja</a>
    <div class="profile-wrapper">
    <img src="../images/ikona.png" alt="Profil" id="profileBtn">

    <div class="profile-dropdown" id="profileDropdown">
      <a href="../Log in/login.php?action=switch">Promeni nalog</a>
      <a href="../logout/logout.php?action=logout" class="logout">Odjava</a>
    </div>
  </div>
</nav>
  </nav>
</header>

<div class="container">
<div class="header-settings">⚙️ PODEŠAVANJA</div>

<!-- LIČNI PODACI -->
<div class="section">
<div class="section-left">
<h2>VAŠI LIČNI PODACI</h2>
<p>U ovoj sekciji možete ažurirati vaše lične podatke, uključujući ime i korisničko ime. Ovi podaci pomažu da vaš profil bude tačno prikazan u sistemu i da se personalizuju vaše aktivnosti i obaveštenja. Email je prikazan, ali se ne može menjati.</p>
</div>
<div class="section-right">
<form method="POST">
    <label for="ime">Ime:</label>
    <input type="text" id="ime" name="ime" value="<?= htmlspecialchars($user['ime']) ?>" required>

    <label for="korime">Korisničko ime:</label>
    <input type="text" id="korime" name="korime" value="<?= htmlspecialchars($user['korime']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <button name="update_clan">Sačuvaj promene</button>
</form>
</div>
</div>

<!-- LOZINKA -->
<div class="section">
<div class="section-left">
<h2>LOZINKA</h2>
<p>Ova sekcija vam omogućava da promenite lozinku vašeg naloga. Za sigurnost vaših podataka preporučujemo da redovno ažurirate lozinku, koristite kombinaciju slova, brojeva i specijalnih karaktera, i ne delite je sa drugima.</p>
</div>
<div class="section-right">
<form method="POST">
    <label for="stara">Trenutna lozinka:</label>
    <input type="password" id="stara" name="stara" placeholder="Trenutna lozinka" required>

    <label for="nova">Nova lozinka:</label>
    <input type="password" id="nova" name="nova" placeholder="Nova lozinka" required>

    <label for="potvrda">Potvrda nove lozinke:</label>
    <input type="password" id="potvrda" name="potvrda" placeholder="Potvrda nove lozinke" required>

    <button name="update_lozinka">Sačuvaj promene</button>
</form>
</div>
</div>

<!-- PODACI O TELU -->
<div class="section">
<div class="section-left">
<h2>PODACI O TELU</h2>
<p>U ovoj sekciji možete uneti ili ažurirati vaše fizičke podatke – datum rođenja, visinu i težinu. Ovi podaci se koriste za izračunavanje FitScore rezultata, praćenje vašeg napretka i personalizaciju treninga. Unosite tačne podatke kako bi rezultati i preporuke bili precizni.</p>
</div>
<div class="section-right">
<form method="POST">
    <label for="datum_rodjenja">Datum rođenja:</label>
    <input type="date" id="datum_rodjenja" name="datum_rodjenja" value="<?= $user['datum_rodjenja'] ?>">

    <div class="row">
        <div>
            <label for="visina">Visina (cm):</label>
            <input type="number" id="visina" name="visina" value="<?= $user['visina'] ?>" placeholder="Visina">
        </div>
        <div>
            <label for="tezina">Težina (kg):</label>
            <input type="number" id="tezina" name="tezina" value="<?= $user['tezina'] ?>" placeholder="Težina">
        </div>
    </div>

    <button name="update_telo">Sačuvaj promene</button>
</form>
</div>
</div>

</div>

<footer>© 2026 FitScore</footer>

</body>
<script>
    const profileBtn = document.getElementById("profileBtn");
const dropdown = document.getElementById("profileDropdown");

if (profileBtn && dropdown) {
  profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdown.style.display =
      dropdown.style.display === "flex" ? "none" : "flex";
  });

  document.addEventListener("click", () => {
    dropdown.style.display = "none";
  });
}
const toggleBtn = document.createElement('div');
toggleBtn.classList.add('nav-toggle');
toggleBtn.innerHTML = '<span></span><span></span><span></span>';
document.querySelector('header').appendChild(toggleBtn);

// Izaberi meni i dodaj overlay
const navRight = document.querySelector('.nav-right');
const overlay = document.createElement('div');
overlay.classList.add('nav-overlay');
document.body.appendChild(overlay);

// Funkcija otvaranja i zatvaranja
function toggleMenu() {
  navRight.classList.toggle('show');
  toggleBtn.classList.toggle('active');
  overlay.classList.toggle('show');
}

// Klik na hamburger dugme
toggleBtn.addEventListener('click', toggleMenu);

// Klik na overlay da zatvori meni
overlay.addEventListener('click', toggleMenu);
</script>
</html>
