<?php

// Ejercicio 5: guardar cliente.
require "config/sesion.php";
require "config/conexion.php";

$resultado = $conexion->query("SELECT COALESCE(MAX(Id), 0) + 1 AS siguiente FROM clientes");
$fila = $resultado->fetch_assoc();
$id = (int)$fila["siguiente"];

$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$localidad = $_POST["localidad"];

$consulta = "INSERT INTO clientes (Id, Nombre, Apellidos, Fecha_nacimiento, Localidad)
             VALUES (?, ?, ?, ?, ?)";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("issss", $id, $nombre, $apellidos, $fecha_nacimiento, $localidad);
$sentencia->execute();

header("Location: /biblioteca/clientes.php");
exit;

?>
