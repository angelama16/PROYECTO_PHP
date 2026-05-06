<?php

// Ejercicio 5: actualizar cliente.
require "config/sesion.php";
require "config/conexion.php";

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$localidad = $_POST["localidad"];

$consulta = "UPDATE clientes
             SET Nombre = ?, Apellidos = ?, Fecha_nacimiento = ?, Localidad = ?
             WHERE Id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("ssssi", $nombre, $apellidos, $fecha_nacimiento, $localidad, $id);
$sentencia->execute();

header("Location: /biblioteca/clientes.php");
exit;

?>
