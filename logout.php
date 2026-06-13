<?php
session_start();

session_unset();
session_destroy();

header("Location: pag_inicial.php");
exit();
?>
