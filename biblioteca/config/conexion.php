<?php

// Conexion a la BBDD.
$servidor = "bbdd";
$usuario = "root";
$contrasena = "bbdd";
$nombre_bbdd = "biblioteca";

$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bbdd);
$conexion->set_charset("utf8");

if($conexion->connect_error){
    echo "Error en la conexion: " . $conexion->connect_error;
    exit;
}

?>
