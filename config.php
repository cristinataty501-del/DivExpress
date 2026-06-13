<?php

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

if (!$host || !$user || !$db) {
    die("Variáveis de ambiente não configuradas no Render");
}

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}

?>