<?php

// Ejercicio 2: guardar usuario con SHA-256.
require "config/conexion.php";

$usuario = $_POST["usuario"];
$contrasena = hash("sha256", $_POST["contrasena"]);

$consulta = "SELECT * FROM usuarios WHERE Usuario = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("s", $usuario);
$sentencia->execute();
$sentencia->store_result();

if($sentencia->num_rows > 0){
    echo "El usuario ya existe. <a href='/biblioteca/registro.html'>Volver</a>";
} else {
    $consulta = "INSERT INTO usuarios (Usuario, Contrasena) VALUES (?, ?)";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("ss", $usuario, $contrasena);
    $sentencia->execute();

    header("Location: /biblioteca/login.php");
    exit;
}

?>
