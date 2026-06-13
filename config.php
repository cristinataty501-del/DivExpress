<?php

$host = getenv('Hostname');
$user = getenv('Username');
$pass = getenv('Password');
$db   = getenv('Database');

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

?>