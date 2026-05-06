<?php

// Ejercicio 5: borrar cliente.
require "config/sesion.php";
require "config/conexion.php";

$id = $_GET["id"];

$consulta = "DELETE FROM clientes WHERE Id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();

header("Location: /biblioteca/clientes.php");
exit;

?>
