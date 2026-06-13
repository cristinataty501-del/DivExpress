<?php

$host = "dpg-d8ml628k1i2s738sggu0-a";
$user = "divexpress_db_user";
$pass = "4Xsaz7fLa2aafUJcs9aVfwb56qfdaxol";
$db   = "divexpress_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erro: " . mysqli_connect_error());
}

?>