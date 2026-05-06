<?php

// Ejercicio 5: lista de clientes.
require "config/sesion.php";
require "config/conexion.php";

$resultado = $conexion->query("SELECT * FROM clientes ORDER BY Id");
$clientes = [];

while($cliente = $resultado->fetch_assoc()){
    $clientes[] = $cliente;
}

?>

<html>
    <body>
        <h2>Clientes</h2>

        <p>
            <a href="/biblioteca/inicio.php">Volver</a> |
            <a href="/biblioteca/nuevo_cliente.php">Nuevo cliente</a>
        </p>

        <table border="1" cellpadding="5">
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha nacimiento</th>
                <th>Localidad</th>
                <th>Acciones</th>
            </tr>

            <?php foreach($clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente["Id"]; ?></td>
                    <td><?php echo $cliente["Nombre"]; ?></td>
                    <td><?php echo $cliente["Apellidos"]; ?></td>
                    <td><?php echo $cliente["Fecha_nacimiento"]; ?></td>
                    <td><?php echo $cliente["Localidad"]; ?></td>
                    <td>
                        <a href="/biblioteca/editar_cliente.php?id=<?php echo $cliente["Id"]; ?>">Editar</a>
                        <a href="/biblioteca/borrar_cliente.php?id=<?php echo $cliente["Id"]; ?>">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
