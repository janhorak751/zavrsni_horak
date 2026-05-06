<?php
require 'db_connection.php';

$ime     = trim($_POST['ime']     ?? '');
$prezime = trim($_POST['prezime'] ?? '');
$email   = trim($_POST['email']   ?? '');
$telefon = trim($_POST['telefon'] ?? '');
$datum   = trim($_POST['datum_upisa'] ?? '');
$god_clan = isset($_POST['godisnja_clanarina']) ? 1 : 0;

if (!$ime || !$prezime || !$email || !$telefon || !$datum) {
    header('Location: dodaj_clana.php?err=Sva+polja+su+obavezna!');
    exit;
}

// Provjera duplikata emaila
$e = mysqli_real_escape_string($db, $email);
$check = mysqli_query($db, "SELECT ID_Clana FROM clan WHERE Email='$e'");
if (mysqli_num_rows($check) > 0) {
    header('Location: dodaj_clana.php?err=Clan+s+tim+emailom+vec+postoji!');
    exit;
}

$ime     = mysqli_real_escape_string($db, $ime);
$prezime = mysqli_real_escape_string($db, $prezime);
$telefon = mysqli_real_escape_string($db, $telefon);
$datum   = mysqli_real_escape_string($db, $datum);

$sql = "INSERT INTO clan (Ime, Prezime, Email, Telefon, Godisnja_clanarina, Datum_upisa)
        VALUES ('$ime','$prezime','$e','$telefon',$god_clan,'$datum')";

if (mysqli_query($db, $sql)) {
    header('Location: index.php?ok=Clan+je+uspjesno+dodan!');
} else {
    header('Location: dodaj_clana.php?err=' . urlencode(mysqli_error($db)));
}
exit;
?>
