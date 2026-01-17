<?php
$servername = "localhost";
$username = "root";
$password = "";
$baza = "fitscore";

$conn = new mysqli($servername, $username, $password, $baza);

$conn->connect_error;
?>