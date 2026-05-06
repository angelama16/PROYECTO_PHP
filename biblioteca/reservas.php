<?php

// Ejercicio 4 y 5: listado de reservas.
require "config/sesion.php";
require "config/conexion.php";


// Filtro por cliente.
$id_cliente = $_GET["id_cliente"] ?? "";

// Esta lista se usa en el desplegable del filtro.
$resultado_clientes = $conexion->query("SELECT * FROM clientes ORDER BY Nombre, Apellidos");
$clientes = [];
while($cliente = $resultado_clientes->fetch_assoc()){
    $clientes[] = $cliente;
}

// Esta consulta une reservas, clientes, libros y peliculas.
$consulta = "SELECT r.Id,
                    CONCAT(c.Nombre, ' ', c.Apellidos) AS Cliente,
                    CASE
                        WHEN r.Id_libro IS NOT NULL THEN 'Libro'
                        ELSE 'Pelicula'
                    END AS Tipo,
                    CASE
                        WHEN r.Id_libro IS NOT NULL THEN l.Titulo
                        ELSE p.Titulo
                    END AS Elemento,
                    r.Fecha_reserva,
                    r.Devuelto
             FROM reservas r
             LEFT JOIN clientes c ON c.Id = r.Id_cliente
             LEFT JOIN libros l ON l.Id = r.Id_libro
             LEFT JOIN peliculas p ON p.ID = r.Id_pelicula
             WHERE (? = '' OR r.Id_cliente = ?)
             ORDER BY r.Id DESC";

$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("si", $id_cliente, $id_cliente);
$sentencia->execute();
$resultado = $sentencia->get_result();

// Guardamos las filas para tenerlas en la tabla.
$reservas = [];
while($reserva = $resultado->fetch_assoc()){
    $reservas[] = $reserva;
}

?>

<html>
    <body>
        <h2>Reservas</h2>

        <p>
            <a href="/biblioteca/inicio.php">Volver</a> |
            <a href="/biblioteca/catalogo.php">Ir al catalogo</a>
        </p>

        <!-- Este filtro deja ver todas las reservas o solo las de un cliente -->
        <form action="/biblioteca/reservas.php" method="GET">
            <label>Filtrar por cliente</label>
            <select name="id_cliente">
                <option value="">Todos</option>
                <?php foreach($clientes as $cliente): ?>
                    <option value="<?php echo $cliente["Id"]; ?>" <?php if($id_cliente == $cliente["Id"]){ echo "selected"; } ?>>
                        <?php echo $cliente["Nombre"] . " " . $cliente["Apellidos"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Filtrar">
        </form>

        <!-- Tabla que muestra el historico de reservas -->
        <table border="1" cellpadding="5">
            <tr>
                <th>Id</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Elemento</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>

            <?php foreach($reservas as $reserva): ?>
                <tr>
                    <td><?php echo $reserva["Id"]; ?></td>
                    <td><?php echo $reserva["Cliente"]; ?></td>
                    <td><?php echo $reserva["Tipo"]; ?></td>
                    <td><?php echo $reserva["Elemento"]; ?></td>
                    <td><?php echo $reserva["Fecha_reserva"]; ?></td>
                    <td><?php echo $reserva["Devuelto"] == 0 ? "Reservado" : "Devuelto"; ?></td>
                    <td>
                        <!-- Si sigue reservado, deja marcar la devolucion -->
                        <?php if($reserva["Devuelto"] == 0): ?>
                            <a href="/biblioteca/devolver.php?id=<?php echo $reserva["Id"]; ?>">Devolver</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>