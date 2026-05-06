<?php
$server   = 'localhost';
$username = 'root';
$password = '';
$database = 'tenis_klub';

$db = mysqli_connect($server, $username, $password, $database);

if (!$db) {
    die("<p style='font-family:sans-serif;color:red;padding:20px;'>
         Greška pri spajanju na bazu: " . mysqli_connect_error() . "</p>");
}

mysqli_set_charset($db, 'utf8mb4');
?>
