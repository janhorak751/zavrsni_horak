<?php
require 'db_connection.php';

$id_clana   = (int)($_POST['id_clana']   ?? 0);
$id_terena  = (int)($_POST['id_terena']  ?? 0);
$id_cjenika = (int)($_POST['id_cjenika'] ?? 0);
$datum      = trim($_POST['datum']      ?? '');
$pocetak    = trim($_POST['pocetak']    ?? '');
$zavrsetak  = trim($_POST['zavrsetak']  ?? '');
$napomena   = trim($_POST['napomena']   ?? '');

if (!$id_clana || !$id_terena || !$id_cjenika || !$datum || !$pocetak || !$zavrsetak) {
    header('Location: dodaj_rezervaciju.php?err=Sva+polja+su+obavezna!');
    exit;
}

if ($pocetak >= $zavrsetak) {
    header('Location: dodaj_rezervaciju.php?err=Vrijeme+pocetka+mora+biti+prije+zavrsetka!');
    exit;
}

$datum     = mysqli_real_escape_string($db, $datum);
$pocetak   = mysqli_real_escape_string($db, $pocetak);
$zavrsetak = mysqli_real_escape_string($db, $zavrsetak);
$napomena  = mysqli_real_escape_string($db, $napomena);

$sql = "INSERT INTO rezervacija
          (ID_Clana, ID_Terena, ID_Cjenika, Datum, Vrijeme_pocetka, Vrijeme_zavrsetka, Napomena)
        VALUES
          ($id_clana, $id_terena, $id_cjenika, '$datum', '$pocetak', '$zavrsetak', '$napomena')";

if (mysqli_query($db, $sql)) {
    header('Location: index.php?ok=Rezervacija+je+uspjesno+dodana!');
} else {
    header('Location: dodaj_rezervaciju.php?err=' . urlencode(mysqli_error($db)));
}
exit;
?>
