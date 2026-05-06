<?php
require 'db_connection.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php?err=Nevaljan+zahtjev'); exit; }

// Ne dopusti brisanje ako clan ima rezervacije
$check = mysqli_query($db, "SELECT ID_Rezervacije FROM rezervacija WHERE ID_Clana=$id LIMIT 1");
if (mysqli_num_rows($check) > 0) {
    header('Location: index.php?err=Nije+moguce+obrisati+clana+koji+ima+rezervacije!');
    exit;
}

mysqli_query($db, "DELETE FROM clan WHERE ID_Clana=$id")
    ? header('Location: index.php?ok=Clan+je+obrisan!')
    : header('Location: index.php?err=' . urlencode(mysqli_error($db)));
exit;
?>
