<?php

// Ejercicio 4: formulario y guardado de una reserva.
require "config/sesion.php";
require "config/conexion.php";


// Si llega POST, se guarda la reserva.
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $tipo = $_POST["tipo"];
    $id = (int)$_POST["id"];
    $id_cliente = (int)$_POST["id_cliente"];
    $fecha_reserva = date("Y-m-d");
    $campo_reserva = $tipo == "pelicula" ? "Id_pelicula" : "Id_libro";

    // Comprueba si el elemento sigue libre.
    $consulta = "SELECT COUNT(*) AS total
                 FROM reservas
                 WHERE $campo_reserva = ?
                 AND Devuelto = 0";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id);
    $sentencia->execute();
    $ocupado = $sentencia->get_result()->fetch_assoc();

    // Solo guarda si no hay una reserva activa.
    if($ocupado["total"] == 0){
        $resultado_id = $conexion->query("SELECT COALESCE(MAX(Id), 0) + 1 AS siguiente FROM reservas");
        $fila_id = $resultado_id->fetch_assoc();
        $id_reserva = (int)$fila_id["siguiente"];

        if($tipo == "pelicula"){
            $consulta = "INSERT INTO reservas (Id, Id_libro, Id_pelicula, Id_cliente, Fecha_reserva, Devuelto)
                         VALUES (?, NULL, ?, ?, ?, 0)";
        } else {
            $consulta = "INSERT INTO reservas (Id, Id_libro, Id_pelicula, Id_cliente, Fecha_reserva, Devuelto)
                         VALUES (?, ?, NULL, ?, ?, 0)";
        }

        $sentencia = $conexion->prepare($consulta);
        $sentencia->bind_param("iiis", $id_reserva, $id, $id_cliente, $fecha_reserva);
        $sentencia->execute();
    }

    header("Location: /biblioteca/reservas.php");
    exit;
}

// Si llega GET, mostramos el formulario de reserva.
$tipo = $_GET["tipo"] ?? "";
$id = (int)($_GET["id"] ?? 0);
$tabla = $tipo == "pelicula" ? "peliculas" : "libros";
$campo_id = $tipo == "pelicula" ? "ID" : "Id";

// Saca el titulo del libro o pelicula elegido.
$consulta = "SELECT Titulo FROM $tabla WHERE $campo_id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();
$elemento = $sentencia->get_result()->fetch_assoc();

// Saca la lista de clientes para el desplegable.
$resultado_clientes = $conexion->query("SELECT * FROM clientes ORDER BY Nombre, Apellidos");
$clientes = [];
while($cliente = $resultado_clientes->fetch_assoc()){
    $clientes[] = $cliente;
}

?>

<html>
    <body>
        <h2>Nueva reserva</h2>

        <p>
            <!-- Muestra el elemento que se va a reservar -->
            Elemento: <?php echo $elemento["Titulo"]; ?>
        </p>

        <!-- Formulario que envia los datos de la nueva reserva -->
        <form action="/biblioteca/reservar.php" method="POST">
            <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label>Cliente</label>
            <select name="id_cliente" required>
                <?php foreach($clientes as $cliente): ?>
                    <option value="<?php echo $cliente["Id"]; ?>">
                        <?php echo $cliente["Nombre"] . " " . $cliente["Apellidos"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Confirmar reserva">
        </form>

        <p><a href="/biblioteca/catalogo.php">Volver al catalogo</a></p>
    </body>
</html>