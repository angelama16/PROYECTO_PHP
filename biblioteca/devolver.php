<?php

// Ejercicio 4: marcar devolucion.
require "config/sesion.php";
require "config/conexion.php";

$id = $_GET["id"];

$consulta = "UPDATE reservas SET Devuelto = 1 WHERE Id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();

header("Location: /biblioteca/reservas.php");
exit;

?>
