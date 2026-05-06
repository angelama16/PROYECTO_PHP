<?php

// Ejercicio 5: editar cliente.
require "config/sesion.php";
require "config/conexion.php";

$id = $_GET["id"];

$consulta = "SELECT * FROM clientes WHERE Id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();
$cliente = $sentencia->get_result()->fetch_assoc();

?>

<html>
    <body>
        <h2>Editar cliente</h2>

        <form action="/biblioteca/actualizar_cliente.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $cliente["Id"]; ?>">

            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $cliente["Nombre"]; ?>" required>

            <label>Apellidos</label>
            <input type="text" name="apellidos" value="<?php echo $cliente["Apellidos"]; ?>" required>

            <label>Fecha nacimiento</label>
            <input type="text" name="fecha_nacimiento" value="<?php echo $cliente["Fecha_nacimiento"]; ?>" required>

            <label>Localidad</label>
            <input type="text" name="localidad" value="<?php echo $cliente["Localidad"]; ?>" required>

            <input type="submit" value="Actualizar cliente">
        </form>

        <p><a href="/biblioteca/clientes.php">Volver</a></p>
    </body>
</html>
