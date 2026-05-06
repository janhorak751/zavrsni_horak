<?php
require 'db_connection.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php?err=Nevaljan+zahtjev'); exit; }

mysqli_query($db, "DELETE FROM rezervacija WHERE ID_Rezervacije=$id")
    ? header('Location: index.php?ok=Rezervacija+je+obrisana!')
    : header('Location: index.php?err=' . urlencode(mysqli_error($db)));
exit;
?>
