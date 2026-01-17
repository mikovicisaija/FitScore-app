<?php
session_start();
include("../konekcija/konekcija.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../Log in/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>FitScore</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">


<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
}
ul{list-style-type: none;
}
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

/* ===== LAYOUT ===== */
.container {
  max-width: 1200px;
  margin: 60px auto; /* više prostora od headera */
  padding: 0 20px;
}

.welcome {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.welcome-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

/* ===== CARDS ===== */
.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.card {
  background: #F0F0F0; /* svetlo siva pozadina */
  border-radius: 14px;
  padding: 25px 30px;
  padding-bottom: 0px;
}

.card h2 {
  font-size: 20px;
  margin-bottom: 20px;
  font-weight: 600;
}

.health {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
}

.health-left {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.health-left ul li{
    padding: 5px;
}

.status-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.status-good { color: #16a34a; }
.status-partial { color: #d3ad16; }
.status-bad { color: #dc2626; }


.score-circle {
  width: 130px;
  height: 130px;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.score-circle svg {
  transform: rotate(-90deg);
  position: absolute;
  top: 0;
  left: 0;
}

.score-circle .score-value {
  font-size: 28px;
  font-weight: 700;
  color: #111;
  z-index: 1;
}

.score-circle .score-label {
  font-size: 12px;
  color: #666;
  font-weight: 500;
  margin-top: 4px;
  z-index: 1;
}

.summary-bar.good { background-color: #16a34a; }
.summary-bar.partial { background-color: #d3ad16; }
.summary-bar.bad { background-color: #dc2626; }

.score-circle .score-value {
  font-size: 24px; /* broj poena veći */
  line-height: 1;
  color:#111;
}

.score-circle .score-label {
  font-size: 12px; /* manji tekst ispod broja */
  color: #666;
  font-weight: 500;
  margin-top: 4px;
}

/* SUMMARY */
.summary-item {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.summary-top {
  font-weight: 600;
}

.summary-bottom {
  font-size: 12px;
  color: #666;
  margin: 0;
}

.summary-progress-wrapper {
  width: 100%;
  height: 12px;      /* povećano za bolju vidljivost */
  background: #d3d3d3;
  border-radius: 5px;
  overflow: hidden;
}

.summary-bar {
  height: 100%;
  border-radius: 5px;
  position: relative;
  overflow: hidden;
  transition: width 1s ease-in-out; /* dodato ease-in-out */
}

/* Animirane trake sa pojačanim kontrastom */
.summary-bar::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 200%; /* dvostruko duže da se može pomerati */
  height: 100%;
  background: linear-gradient(
    45deg,
    rgba(255,255,255,0.3) 25%,  /* svetlije trake */
    rgba(255,255,255,0.05) 50%, /* tamnije trake */
    rgba(255,255,255,0.3) 75%
  );
  background-size: 40px 100%; /* malo šire trake za bolju vidljivost */
  animation: moveStripes 1s linear infinite;
}

@keyframes moveStripes {
  from { transform: translateX(0); }
  to { transform: translateX(-40px); } /* pomeranje traka */
}


.btn {
  margin-top: 10px;
  padding: 8px 16px;
  border-radius: 20px;
  border: none;
  background: #e63946;
  color: white;
  cursor: pointer;
  font-weight: 600;
  transition: transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out, background 0.25s ease-in-out;
}

.btn:hover {
  background: #d62839; /* malo tamnija nijansa */
  transform: scale(1.05); /* blago uvećanje */
  box-shadow: 0 6px 15px rgba(230,57,70,0.4); /* lebdeća senka */
}

/* ===== CALENDAR ===== */
.calendar-wrapper {
    display: flex;
    gap: 30px;
    flex-wrap: wrap; /* sprečava da legenda izlazi iz ekrana */
    margin-top: 20px;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.calendar-header button {
  border: none;
  background: none;
  font-size: 20px;
  cursor: pointer;
}

.weekdays, .calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 85px); /* 7 kolona, po 60px */
  gap: 10px;                              /* razmak između dana */
  text-align: center;                     /* centriranje broja ili slova */
  justify-content: center;                /* centrira grid u parentu */
}

.weekdays div {
  line-height: 60px;   /* vertikalno centriranje slova u visini ćelije */
  font-weight: 600;
  color: #666;
  margin-left: -25px;
}

.day {
  width: 60px;        /* ista širina kao u grid-template-columns */
  height: 60px;       /* ista visina */
  line-height: 60px;  /* vertikalno centriranje broja */
  border-radius: 50%; /* možeš zadržati kao kružiće */
  background: #f7f7f7;
  border: 1px solid #e0e0e0;
  cursor: pointer;
  transition: 0.2s;
}

.day:hover {
  transform: scale(1.15);     /* blago uvećanje */
  box-shadow: 0 6px 15px rgba(0,0,0,0.2); /* blaga senka */
}

.empty-cell {
  visibility: hidden;
}

#calendarModal .calendar-wrapper {
  display: block; /* ne flex, da se header i weekdays postave jedan ispod drugog */
}

#calendarModal .calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

#calendarModal .weekdays {
  display: grid;
  grid-template-columns: repeat(7, 60px); /* širina identična kolonama dana */
  gap: 10px;
  text-align: center;
  margin-bottom: 10px; /* prostor između imena dana i datuma */
}

#calendarModal .weekdays div {
  line-height: 60px;   /* vertikalno centriranje */
  font-weight: 600;
  color: #666;
  margin-left: 0;       /* uklonjena negativna margin */
}

#calendarModal .calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 5px;
}

#calendarModal .day {
  width: 100%;
  aspect-ratio: 1;  /* kvadrat */
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #f7f7f7;
  border: 1px solid #e0e0e0;
  cursor: pointer;
  transition: 0.2s;
}

#calendarModal .empty-cell {
  visibility: hidden;
}

@media (max-width: 900px) {
  #calendarModal .weekdays {
    grid-template-columns: repeat(7, 1fr); /* smanjuje širinu na mobilnom */
    gap: 5px;
  }
  #calendarModal .weekdays div {
    margin-left: 0; /* uklanja marginu na malim ekranima */
  }
}

/* STATUSI */
.good { background: #1abc9c;}
.partial { background: #d3ad16;}
.bad { background: #e74c3c;}
.empty { background: #e74c3c;}

/* LEGENDA */
.legend {
  margin-top: 40px;
}

.legend div {
  margin-bottom: 8px;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 8px;
}

.green { background: #1abc9c; }
.yellow { background: #d3ad16; }
.red { background: #e74c3c; }

/* ===== POINTS ===== */
.points {
  max-width: 400px;
  margin: 30px auto;
  text-align: center;
}

/* ===== FOOTER ===== */
footer {
  margin-top: 60px;
  background: #111;
  color: #ccc;
  padding: 30px;
  text-align: center;
}

@media (max-width: 900px) {
  .grid {
    grid-template-columns: 1fr;
  }
  .calendar-wrapper {
    flex-direction: column;
  }
}
.points {
  margin-top: 25px;
  padding: 20px;
}

.points h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 15px;
}

.points-summary {
  font-size: 14px;
  color: #333;
  margin-bottom: 15px;
}

.points-summary select {
  margin-left: 5px;
  padding: 3px 6px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 14px;
}

.points-chart-wrapper {
  min-height: 300px;
  background: #f7f7f7;
  position: relative;
}
#pointsChart {
  width: 100% !important;
  height: 100% !important;
  position: relative;
  z-index: 1;
}
/* MODAL */
.modal {
  display: none; /* skriven po defaultu */
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5); /* tamna pozadina */
  transition: opacity 0.3s ease;
}

/* Sadržaj modala */
.modal-content {
  background-color: #fff;
  margin: 80px auto;
  padding: 30px 25px;
  border-radius: 20px;
  max-width: 450px;
  width: 90%;
  position: relative;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  animation: slideDown 0.3s ease;
}

/* Animacija */
@keyframes slideDown {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* Zatvaranje */
.close {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 26px;
  font-weight: bold;
  cursor: pointer;
  color: #e63946;
  transition: color 0.2s;
}

.close:hover {
  color: #d62839;
}

/* Naslov */
.modal-content h3 {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 20px;
  color: #111;
  text-align: center;
}

/* FORM ELEMENTI */
#dayForm label {
  display: block;
  font-weight: 500;
  margin-bottom: 6px;
  color: #333;
  font-size: 15px;
}

#dayForm input[type="number"],
#dayForm select {
  width: 100%;
  padding: 10px 12px;
  margin-bottom: 12px;
  border-radius: 10px;
  border: 1px solid #ccc;
  font-size: 14px;
  transition: border 0.2s, box-shadow 0.2s;
}

#dayForm input[type="number"]:focus,
#dayForm select:focus {
  border-color: #e63946;
  box-shadow: 0 0 5px rgba(230,57,70,0.3);
  outline: none;
}

/* Dugme */
#dayForm .btn {
  display: block;
  width: 100%;
  padding: 12px 0;
  background-color: #e63946;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: transform 0.25s ease-in-out, background 0.25s ease-in-out;
}

#dayForm .btn:hover {
  background-color: #d62839;
  transform: scale(1.03);
  box-shadow: 0 6px 12px rgba(230,57,70,0.3);
}

/* Greške */
.error-msg {
  color: #dc2626;
  font-size: 13px;
  margin-bottom: 8px;
  display: none;
}

/* Datum */
#selectedDate {
  font-weight: 600;
  color: #e63946;
}

/* Responsive */
@media (max-width: 500px) {
  .modal-content {
    padding: 20px 15px;
  }
  #dayForm label, #dayForm input, #dayForm select {
    font-size: 14px;
  }
  #dayForm .btn {
    font-size: 15px;
  }
}

.error-msg {
  color: red;
  font-size: 0.85rem;
  display: none;
}

.btn {
  padding: 8px 16px;
  border-radius: 20px;
  border: none;
  background: #e63946;
  color: white;
  cursor: pointer;
}
.profile-wrapper {
  position: relative;
}

#profileBtn {
  width: 40px;
  height: 40px;
  cursor: pointer;
  border-radius: 50%;
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

@media (max-width: 1200px) {
  .container {
    margin: 40px 15px;
    padding: 0 10px;
  }
  .welcome {
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }
  .welcome-left h2 {
    font-size: 20px;
  }
  .streak-wrapper {
    flex-direction: row;
    gap: 15px;
  }
  .streak-number {
    font-size: 40px;
  }
  .streak-text-wrapper {
    font-size: 16px;
  }
  .grid {
    grid-template-columns: 1fr 1fr; /* 2 kolone, ali se smanjuje prostor */
    gap: 15px;
  }
}

@media (max-width: 900px) {
  .grid {
    grid-template-columns: 1fr; /* 1 kolona na manjim ekranima */
    gap: 15px;
  }
  .welcome {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  .streak-number {
    font-size: 36px;
  }
  .streak-text-wrapper {
    font-size: 14px;
  }
  .score-circle {
    width: 110px;
    height: 110px;
  }
  .score-circle svg {
    width: 110px;
    height: 110px;
  }
  .score-circle .score-value {
    font-size: 20px;
  }
  .score-circle .score-label {
    font-size: 10px;
  }
}
@media (max-width: 900px) {
  .calendar-wrapper {
    flex-direction: column;
    gap: 15px;
  }
  .calendar-box {
    width: 100%;
    padding: 10px;
  }
  .weekdays, .calendar-days {
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
  }
  .legend {
    display: flex;
    justify-content: space-between;
    gap: 10px;
  }
  .legend div {
    flex: 1;
    font-size: 12px;
  }
}

@media (max-width: 500px) {
  .calendar-wrapper, .calendar-box, #calendarModal .calendar-wrapper {
    padding: 5px;
  }
  .weekdays div, .day {
    font-size: 12px;
  }
  .day {
    border-radius: 8px;
  }
  #calendarModal .modal-content {
    padding: 15px 10px;
  }
}
@media (max-width: 900px) {
  .summary-bar {
    height: 10px;
  }
  .summary-item .summary-top {
    font-size: 14px;
  }
  .summary-item .summary-label {
    font-size: 12px;
  }
}
@media (max-width: 500px) {
  .summary-bar {
    height: 8px;
  }
  .summary-item .summary-top, .summary-item .summary-label {
    font-size: 11px;
  }
}
@media (max-width: 500px) {
  .btn {
    padding: 6px 12px;
    font-size: 14px;
  }
  #dayForm label {
    font-size: 13px;
  }
  #dayForm input, #dayForm select {
    font-size: 13px;
    padding: 8px 10px;
  }
  .container {
    margin: 20px 5px;
    padding: 0 5px;
  }
  #calendarModal .modal-content,
  #dayModal .modal-content {
    max-width: 95%;
    padding: 10px 10px;
  }
  .summary-bar { height: 8px; }
}

@media (max-width: 600px) {
  .card h2 {
    text-align: center;
  }

  .health-left ul {
    text-align: left;
  }
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

<header>
  <div class="nav-left">
      <a href="index.html"><img class="FitScore" src="../images/image.png" height="50px"></a>
  </div>
  <img >
  <nav class="nav-right">
  <a href="../pocetna/pocetna.php">Početna</a>
  <a href="../podesavanja/podesavanja.php">Podešavanja</a>

  <div class="profile-wrapper">
    <img src="../images/ikona.png" alt="Profil" id="profileBtn">

    <div class="profile-dropdown" id="profileDropdown">
      <a href="../Log in/login.php?action=switch">Promeni nalog</a>
      <a href="../logout/logout.php?action=logout" class="logout">Odjava</a>
    </div>
  </div>
</nav>
</header>


<div class="container">

    <div class="welcome">

    <div class="welcome-left">
        <img src="../images/ikona.png" alt="Profil">
        <h2>
        Dobrodošao, <?php
          echo $_SESSION["ime"];
          ?>! 👋<br>
        <small style="color: gray; font-weight: 100; font-size: 18px;">Danas je dobar dan da odradiš nešto <span style="font-weight: 700;">za sebe!</span></small>
        </h2>
    </div>

    </div>

  <div class="grid">
  <!-- HEALTH SCORE -->
<div class="card">
  <h2>
    Health score
    <span id="healthDate" style="color: gray; font-weight: 400; font-size: 16px; margin-left: 10px;"></span>
  </h2>
  <div class="health">
    <div class="health-left">
      <!-- NUMERIČKA LISTA -->
      <ul>
        <li>💧 Voda: <b>20/30</b></li>
        <li>😴 San: <b>25/30</b></li>
        <li>🏃 Vežbe: <b>15/40</b></li>
      </ul>

      <!-- STATUSI ISPOD LISTE SA PADDINGOM -->
      <div class="status-list" style="padding-left: 5px; padding-top: 60px;">
        <span class="status-good">* <span style="text-decoration: underline;">Dobro</span></span>
        <span class="status-partial">* <span style="text-decoration: underline;">Delimično dobro</span></span>
        <span class="status-bad">* <span style="text-decoration: underline;">Loše</span></span>
      </div>
    </div>

      <div class="score-circle">
      <svg width="130" height="130">
        <!-- Tamnija pozadina -->
        <circle cx="65" cy="65" r="55" stroke="#ddd" stroke-width="10" fill="none"/>
        <!-- Crveni progress sa ID-jem za animaciju -->
        <circle id="progressCircle" cx="65" cy="65" r="55" stroke="#e63946" stroke-width="10" fill="none"
          stroke-dasharray="345.57" stroke-dashoffset="345.57" stroke-linecap="round"/>
      </svg>
      <span class="score-value">0/100</span>
      <span class="score-label">Broj poena</span>
    </div>
  </div>
</div>

<!-- DNEVNI SAŽETAK -->
<div class="card">
  <h2>Dnevni sažetak</h2>
  <div class="summary">
    <div class="summary-item" style="padding-bottom: 10px;">
      <div class="summary-top">Voda: 1.5 / 2.5 L</div>
      <div class="summary-progress-wrapper" style="margin-top: 5px;">
        <div class="summary-bar partial" style="width: 60%;"></div>
      </div>
    </div>

    <div class="summary-item" style="padding-bottom: 10px;">
      <div class="summary-top">San: 7 h</div>
      <div class="summary-progress-wrapper" style="margin-top: 5px;">
        <div class="summary-bar good" style="width: 85%;"></div>
      </div>
    </div>

    <div class="summary-item">
      <div class="summary-top">Aktivnost: 30 min</div>
      <div class="summary-progress-wrapper" style="margin-top: 5px;">
        <div class="summary-bar bad" style="width: 30%;"></div>
      </div>
    </div>

    <div class="summary-item" style="margin-top: 20px; font-weight: 600;">
      <span class="summary-label">Opis: Lagani cardio</span>
    </div>

    <div class="summary-footer" style="display: flex; justify-content: flex-end; margin-bottom: 20px; font-size: 20px; font-weight: 500px;">
      <button class="btn" id="openCalendarModal">+ Dodaj / Izmeni</button> <!-- DODAT ID -->
    </div>
  </div>
</div>
</div>

  <!-- KALENDAR -->
  <div class="card" style="margin-top:25px; padding-bottom: 25px;">
    <h3>Kalendar<small style="color:gray; font-weight: 300;"> (Klikni na dan u kalendaru da promeniš podatke)</small></h3>
    <div class="calendar-wrapper">

    <div class="calendar-box" style="background: white; padding: 15px; border-radius: 10px;">
        <div class="calendar-header">
        <button id="prev">&lt;</button>
        <h3 id="monthYear"></h3>
        <button id="next">&gt;</button>
        </div>

        <div class="weekdays">
        <div>Pon</div><div>Uto</div><div>Sre</div>
        <div>Čet</div><div>Pet</div><div>Sub</div><div>Ned</div>
        </div>

        <div class="calendar-days" id="calendarDays"></div>
    </div>

    <div class="legend" style="padding: 10px; border-radius: 10px;">
        <div><span class="dot green"></span> Dobar dan</div>
        <div><span class="dot yellow"></span> Delimično</div>
        <div><span class="dot red"></span> Loš dan</div>
    </div>

    </div>

  </div>

<!-- MODAL ZA KALENDAR -->
<div id="calendarModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
  <div style="background-color: #fff; max-width: 500px; width: 90%; margin: 5% auto; padding: 20px; border-radius: 15px; position: relative; box-shadow: 0 6px 20px rgba(0,0,0,0.3);">
    <span class="close" style="position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>

    <h3 style="text-align:center; margin-bottom: 20px;">Izaberite dan</h3>

    <div class="calendar-wrapper" style="width: 100%;">
      <div class="calendar-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <button id="modalPrev">&lt;</button> <!-- IZMENI DUGME ZA PRETHODNI MESEC -->
        <span id="modalMonthYear" style="font-weight:600;"></span>
        <button id="modalNext">&gt;</button> <!-- IZMENI DUGME ZA SLEDEĆI MESEC -->
      </div>

      <div class="weekdays" style="display:grid; grid-template-columns: repeat(7, 1fr); text-align:center; margin-bottom:5px;">
        <div>Pon</div><div>Uto</div><div>Sre</div>
        <div>Čet</div><div>Pet</div><div>Sub</div><div>Ned</div>
      </div>

      <div class="calendar-days" id="modalCalendarDays" style="display:grid; grid-template-columns: repeat(7, 1fr); gap:5px;"></div>
    </div>
  </div>
</div>

</div>
<!-- DAY MODAL -->
<div id="dayModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeDayModal">&times;</span>
    <h3>Unos aktivnosti za <span id="selectedDate"></span></h3>
    <form id="dayForm" action="ubazu.php" method="post">
      <!-- Hidden input za datum -->
      <input type="hidden" name="datum" id="datumInput">

      <label for="water">💧 Voda (L):</label><br>
      <input type="number" id="water" name="water" min="0" max="10" step="0.1">
      <div class="error-msg"></div>
      <br>

      <label for="sleep">😴 San (h):</label><br>
      <input type="number" id="sleep" name="sleep" min="0" max="24" step="0.5">
      <div class="error-msg"></div>
      <br>

      <label for="activity">🏃 Aktivnost:</label><br>
      <select id="activity" name="activity">
          <option value="">Izaberi aktivnost</option>
          <option>Trčanje</option>
          <option>Hodanje</option>
          <option>Teretana (snaga)</option>
          <option>Kardio trening</option>
          <option>Joga</option>
          <option>Pilates</option>
          <option>Plivanje</option>
          <option>Biciklizam</option>
          <option>Grupni fitness</option>
          <option>CrossFit</option>
          <option>Funkcionalni trening</option>
          <option>Borilačke veštine</option>
          <option>Sportske igre (fudbal, košarka…)</option>
          <option>Planinarenje</option>
          <option>Rehabilitacione vežbe</option>
      </select>
      <div class="error-msg"></div>
      <br>

      <label for="activity_time">⏱️ Trajanje aktivnosti (min):</label><br>
      <input type="number" id="activity_time" name="activity_time" min="0" max="1440" step="1" required>
      <div class="error-msg"></div>
      <br>

      <button type="submit" class="btn">Sačuvaj</button>
    </form>
  </div>
</div>
<footer>© 2026 FitScore</footer>

<script>
  //
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
const daysEl = document.getElementById("calendarDays");
const monthYearEl = document.getElementById("monthYear");

const months = [
  "Januar","Februar","Mart","April","Maj","Jun",
  "Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"
];

let currentDate = new Date();
let dayStatus = {}; // { "2026-01-15": "good" }

let modalDate = new Date();

// === RENDER MODAL CALENDAR ===
function renderModalCalendar() {
  const modalDaysEl = document.getElementById("modalCalendarDays");
  const modalMonthYearEl = document.getElementById("modalMonthYear");

  modalDaysEl.innerHTML = "";

  const year = modalDate.getFullYear();
  const month = modalDate.getMonth(); // 0-indexed
  modalMonthYearEl.textContent = `${months[month]} ${year}`;

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const start = firstDay === 0 ? 6 : firstDay - 1;

  const today = new Date();
  today.setHours(0,0,0,0);

  // prazne ćelije pre prvog dana meseca
  for (let i = 0; i < start; i++) {
    modalDaysEl.innerHTML += `<div class="empty-cell"></div>`;
  }

  // dani u mesecu
  for (let d = 1; d <= daysInMonth; d++) {
    const div = document.createElement("div");
    div.className = "day";
    div.textContent = d;

    const key = `${year}-${(month+1).toString().padStart(2,'0')}-${d.toString().padStart(2,'0')}`;
    if(dayStatus[key]){
      div.classList.add(dayStatus[key]);
    }

    // sivi dani u budućnosti
    const dayDate = new Date(year, month, d);
    if(dayDate > today){
      div.style.background = "#ddd";
      div.style.cursor = "not-allowed";
      div.onclick = null;
    }

    modalDaysEl.appendChild(div);
  }
}

// === RENDER MAIN CALENDAR ===
function renderCalendar() {
  daysEl.innerHTML = "";

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  monthYearEl.textContent = `${months[month]} ${year}`;

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const start = firstDay === 0 ? 6 : firstDay - 1;

  const today = new Date();
  today.setHours(0,0,0,0); // resetujemo vreme da upoređujemo samo datume

  // prazne ćelije pre prvog dana meseca
  for (let i = 0; i < start; i++) {
    daysEl.innerHTML += `<div class="empty-cell"></div>`;
  }

  // dani u mesecu
  for (let d = 1; d <= daysInMonth; d++) {
    const div = document.createElement("div");
    div.className = "day";
    div.textContent = d;

    const key = `${year}-${(month+1).toString().padStart(2,'0')}-${d.toString().padStart(2,'0')}`;

    // status iz PHP-a
    if(dayStatus[key]) {
      div.classList.add(dayStatus[key]);
    }

    // === BLOKIRAJ BUDUCE DANE ===
    const dayDate = new Date(year, month, d);
    if(dayDate > today) {
      div.style.background = "#ddd";
      div.style.cursor = "not-allowed";
      div.onclick = null;
    }
    daysEl.appendChild(div);
    
  }
}

// === NAVIGACIJA ===
document.getElementById("prev").onclick = () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  // Učitaj podatke za novi mesec i renderuj kalendar
  loadMonthData(currentDate.getFullYear(), currentDate.getMonth() + 1);
};

document.getElementById("next").onclick = () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  loadMonthData(currentDate.getFullYear(), currentDate.getMonth() + 1);
};

document.getElementById("modalPrev").onclick = () => {
  modalDate.setMonth(modalDate.getMonth() - 1);
  renderModalCalendar();
};
document.getElementById("modalNext").onclick = () => {
  modalDate.setMonth(modalDate.getMonth() + 1);
  renderModalCalendar();
};

renderCalendar();


// === MODAL HANDLING ===
const calendarModal = document.getElementById("calendarModal");
const openCalendarBtn = document.getElementById("openCalendarModal");
const closeCalendar = calendarModal.querySelector(".close");

const dayModal = document.getElementById("dayModal");
const closeDayModal = document.getElementById("closeDayModal");

// Otvaranje calendar modala
openCalendarBtn.onclick = () => {
  renderModalCalendar();
  calendarModal.style.display = "block";
};
closeCalendar.onclick = () => { calendarModal.style.display = "none"; };

// Otvaranje day modala sa validnim datumom
function openDayModal(dateStr) {
  dayModal.style.display = "block";
  document.getElementById("selectedDate").textContent = dateStr;
  document.getElementById("datumInput").value = dateStr;

  // Fetch podataka za taj datum
  fetch(`proveridan.php?datum=${dateStr}`)
  .then(res => res.json())
  .then(data => {
      if(data){
          // zapis postoji, popuni formu
          document.getElementById("water").value = data.voda ?? 0;
          document.getElementById("sleep").value = data.san ?? 0;
          document.getElementById("activity").value = data.aktivnost_opis ?? '';
          document.getElementById("activity_time").value = data.aktivnost_vreme ?? 0;
      } else {
          // nema unosa, resetuj formu
          document.getElementById("dayForm").reset();
      }
  });
}

closeDayModal.onclick = () => { dayModal.style.display = "none"; };

// Zatvaranje modala klikom van
window.onclick = (event) => {
  if (event.target === calendarModal) calendarModal.style.display = "none";
  if (event.target === dayModal) dayModal.style.display = "none";
};

// Klik na dan u modal kalendaru
document.getElementById("modalCalendarDays").addEventListener("click", (e) => {
  if (e.target.classList.contains("day") && !e.target.classList.contains("empty-cell")) {
    const day = e.target.textContent.padStart(2,'0');
    const month = (modalDate.getMonth() + 1).toString().padStart(2,'0'); // +1 i padStart
    const year = modalDate.getFullYear();
    const dateStr = `${year}-${month}-${day}`; // MySQL format YYYY-MM-DD
    openDayModal(dateStr);
  }
});
  function loadMonthData(year, month) {
    fetch(`dani.php?year=${year}&month=${month}`)
      .then(res => res.json())
      .then(data => {
        dayStatus = data;
        renderCalendar();
      });
  }


// Selektuj elemente card-a
const cardVodaTop = document.querySelector(".summary .summary-item:nth-child(1) .summary-top");
const cardVodaBar = document.querySelector(".summary .summary-item:nth-child(1) .summary-bar");

const cardSanTop = document.querySelector(".summary .summary-item:nth-child(2) .summary-top");
const cardSanBar = document.querySelector(".summary .summary-item:nth-child(2) .summary-bar");

const cardAktTop = document.querySelector(".summary .summary-item:nth-child(3) .summary-top");
const cardAktBar = document.querySelector(".summary .summary-item:nth-child(3) .summary-bar");

const cardOpis = document.querySelector(".summary .summary-item:nth-child(4) .summary-label");

// Funkcija za prikaz podataka u card-u
function prikaziPodatkeUDnevnomCardu(dateStr) {
    fetch(`proveridan.php?datum=${dateStr}`)
    .then(res => res.json())
    .then(data => {
        let voda = 0, san = 0, aktivnost = 0, opis = "Nema unosa";

        if(data){
            voda = data.voda ?? 0;
            san = data.san ?? 0;
            aktivnost = data.aktivnost_vreme ?? 0;
            opis = data.aktivnost_opis ?? "Nema unosa";
        } 

        // === UPDATE summary card ===
        cardVodaTop.textContent = `Voda: ${voda} / 2.5 L`;
        cardSanTop.textContent = `San: ${san} / 8 h`;
        cardAktTop.textContent = `Aktivnost: ${aktivnost} / 60 min`;
        cardOpis.textContent = `Opis: ${opis}`;

        // === UPDATE Health score datum ===
        const healthDateEl = document.getElementById("healthDate");
        if(healthDateEl){
            const [year, month, day] = dateStr.split("-");
            healthDateEl.textContent = `${day}.${month}.${year}`;
        }

        // === PROGRESS BAR update ===
        const vodaPct = Math.min((voda/2.5)*100, 100);
        const sanPct = Math.min((san/8)*100, 100);
        const aktPct = Math.min((aktivnost/60)*100, 100);

        cardVodaBar.style.width = `${vodaPct}%`;
        cardSanBar.style.width = `${sanPct}%`;
        cardAktBar.style.width = `${aktPct}%`;

        cardVodaBar.className = "summary-bar " + (vodaPct > 70 ? "good" : vodaPct >= 40 ? "partial" : "bad");
        cardSanBar.className = "summary-bar " + (sanPct > 70 ? "good" : sanPct >= 40 ? "partial" : "bad");
        cardAktBar.className = "summary-bar " + (aktPct > 70 ? "good" : aktPct >= 40 ? "partial" : "bad");

        // === UPDATE Health card poeni ===
        const healthItems = document.querySelectorAll(".health-left ul li");
        if(healthItems.length === 3){
            const vodaPoeni = Math.min(Math.round((voda/2.5)*30), 30);
            const sanPoeni = Math.min(Math.round((san/8)*30), 30);
            const aktivnostPoeni = Math.min(Math.round((aktivnost/60)*40), 40);

            healthItems[0].innerHTML = `💧 Voda: <b>${vodaPoeni}/30</b>`;
            healthItems[1].innerHTML = `😴 San: <b>${sanPoeni}/30</b>`;
            healthItems[2].innerHTML = `🏃 Vežbe: <b>${aktivnostPoeni}/40</b>`;
        }
    })
    .catch(err => console.error("Greška pri učitavanju podataka za dan:", err));
}
let currentOffset = null; // pamti trenutno stanje kruga
let currentScore = null;  // pamti trenutno stanje broja poena

function animateHealthCircle(animateFromCurrent = true) {
    const healthItems = document.querySelectorAll(".health-left ul li");
    const scoreCircle = document.getElementById("progressCircle");
    const scoreText = document.querySelector(".score-circle .score-value");

    // Izračun poena
    const vodaPoeni = parseInt(healthItems[0].textContent.match(/\d+/)[0]);
    const sanPoeni = parseInt(healthItems[1].textContent.match(/\d+/)[0]);
    const aktivnostPoeni = parseInt(healthItems[2].textContent.match(/\d+/)[0]);

    const ukupnoPoeni = vodaPoeni + sanPoeni + aktivnostPoeni; // max 100
    const maxPoeni = 100;

    const circumference = 2 * Math.PI * 55; // r = 55
    const offsetEnd = circumference - (ukupnoPoeni / maxPoeni) * circumference;

    let offsetStart, scoreStart;

    if (animateFromCurrent && currentOffset !== null && currentScore !== null) {
        offsetStart = currentOffset;
        scoreStart = currentScore;
    } else {
        offsetStart = circumference;
        scoreStart = 0;
    }

    // Reset circle samo ako animira od početka
    scoreCircle.style.transition = 'none';
    scoreCircle.style.strokeDashoffset = offsetStart;
    scoreText.textContent = `${scoreStart}/${maxPoeni}`;

    // Animacija uz ease-in-out
    requestAnimationFrame(() => {
        scoreCircle.style.transition = 'stroke-dashoffset 1.2s ease-in-out';
        scoreCircle.style.strokeDashoffset = offsetEnd;

        let start = null;
        function animateNumber(timestamp) {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / 1200, 1); // trajanje 1.2s
            const eased = 0.5 * (1 - Math.cos(Math.PI * progress)); // ease-in-out
            const currentAnimScore = Math.round(scoreStart + (ukupnoPoeni - scoreStart) * eased);
            scoreText.textContent = `${currentAnimScore}/${maxPoeni}`;
            if (progress < 1) requestAnimationFrame(animateNumber);
            else {
                currentOffset = offsetEnd; // sačuvaj stanje
                currentScore = ukupnoPoeni;
            }
        }
        requestAnimationFrame(animateNumber);
    });
}

// Animiraj za današnji dan kad se učita stranica
window.addEventListener('load', () => {
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2,'0')}-${today.getDate().toString().padStart(2,'0')}`;
    prikaziPodatkeUDnevnomCardu(todayStr); // popuni card
    setTimeout(animateHealthCircle, 100); // animiraj nakon popunjavanja
});

// === AUTOMATSKO POKRETANJE ZA DANASNJI DAN ===
const today = new Date();
const todayStr = `${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2,'0')}-${today.getDate().toString().padStart(2,'0')}`;



// === POKRETANJE ANIMACIJE KADA SE KLIKNIE NA DAN ===
daysEl.addEventListener("click", (e) => {
    const dayEl = e.target.closest(".day");
    if (!dayEl || dayEl.classList.contains("empty-cell")) return;

    const day = dayEl.textContent.padStart(2,'0');
    const month = (currentDate.getMonth() + 1).toString().padStart(2,'0'); 
    const year = currentDate.getFullYear();
    const dateStr = `${year}-${month}-${day}`;

    prikaziPodatkeUDnevnomCardu(dateStr);

    setTimeout(animateHealthCircle, 300); // delay da se lista update-uje prvo
});

// poziv nakon učitavanja month podataka
loadMonthData(currentDate.getFullYear(), currentDate.getMonth()+1);

// === POSTAVI DANASNJI DATUM PREDPRIKAZ ===
document.addEventListener("DOMContentLoaded", () => {
    const today = new Date();

    // Format: DD.MM.YYYY
    const todayFormatted = `${today.getDate().toString().padStart(2,'0')}.${(today.getMonth()+1).toString().padStart(2,'0')}.${today.getFullYear()}`;

    const healthDateEl = document.getElementById("healthDate");
    if (healthDateEl) {
        healthDateEl.textContent = todayFormatted;
    }
});
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
</body>
</html>
