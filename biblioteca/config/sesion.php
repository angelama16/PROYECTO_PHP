<?php

// Ejercicio 2: bloquear paginas sin login.
session_start();

if(!isset($_SESSION["usuario"])){
    header("Location: /biblioteca/login.php");
    exit;
}

?>
