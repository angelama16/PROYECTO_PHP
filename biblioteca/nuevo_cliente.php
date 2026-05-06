<?php

// Ejercicio 5: formulario de alta de clientes.
require "config/sesion.php";

?>

<html>
    <body>
        <h2>Nuevo cliente</h2>

        <form action="/biblioteca/guardar_cliente.php" method="POST">
            <label>Nombre</label>
            <input type="text" name="nombre" required>

            <label>Apellidos</label>
            <input type="text" name="apellidos" required>

            <label>Fecha nacimiento</label>
            <input type="text" name="fecha_nacimiento" placeholder="2000-01-01" required>

            <label>Localidad</label>
            <input type="text" name="localidad" required>

            <input type="submit" value="Guardar cliente">
        </form>

        <p><a href="/biblioteca/clientes.php">Volver</a></p>
    </body>
</html>
