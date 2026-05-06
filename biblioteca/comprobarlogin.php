<?php

// Ejercicio 2: login por BBDD y apertura de sesion.
session_start();

require "config/conexion.php";
require "clases/tablas.php";

$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];

$consulta = "SELECT * FROM usuarios WHERE Usuario = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("s", $usuario);
$sentencia->execute();

// Se lee como array para evitar problemas.
$resultado = $sentencia->get_result();
$datos_usuario = $resultado->fetch_assoc();

// Compara el hash guardado con el hash escrito.
if($datos_usuario != null && hash("sha256", $contrasena) == $datos_usuario["Contrasena"]){
    $_SESSION["usuario"] = $datos_usuario["Usuario"];
    $_SESSION["id_usuario"] = $datos_usuario["Id"];
    header("Location: /biblioteca/inicio.php");
    exit;
}

header("Location: /biblioteca/login.php");
exit;

?>