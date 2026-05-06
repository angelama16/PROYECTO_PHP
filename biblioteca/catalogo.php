<?php

// Ejercicio 3: catalogo de libros y peliculas.
require "config/sesion.php";
require "config/conexion.php";
require "clases/tablas.php";

// Esta parte crea los nombres Año y Año_estreno sin escribir la ñ directamente (da problemas).
$col_anio = "A" . chr(195) . chr(177) . "o";
$col_anio_pelicula = $col_anio . "_estreno";

function sacar_autor($conexion, $autor_id){
    // Busca el nombre del autor a partir del id del libro.
    $consulta = "SELECT * FROM autores WHERE ID = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $autor_id);
    $sentencia->execute();
    $autor = $sentencia->get_result()->fetch_assoc();

    if($autor == null){
        return "";
    }

    // En esta BBDD el campo puede venir como Autor o con un espacio delante.
    if(isset($autor["Autor"])){
        return $autor["Autor"];
    }

    if(isset($autor[" Autor"])){
        return $autor[" Autor"];
    }

    return "";
}

function libro_disponible($conexion, $id_libro){
    // Mira si hay una reserva activa para ese libro.
    $consulta = "SELECT COUNT(*) AS total
                 FROM reservas
                 WHERE Id_libro = ?
                 AND Devuelto = 0";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id_libro);
    $sentencia->execute();
    $fila = $sentencia->get_result()->fetch_assoc();

    return $fila["total"] == 0 ? "Disponible" : "Reservado";
}

function pelicula_disponible($conexion, $id_pelicula){
    // Mira si hay una reserva activa para esa pelicula.
    $consulta = "SELECT COUNT(*) AS total
                 FROM reservas
                 WHERE Id_pelicula = ?
                 AND Devuelto = 0";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id_pelicula);
    $sentencia->execute();
    $fila = $sentencia->get_result()->fetch_assoc();

    return $fila["total"] == 0 ? "Disponible" : "Reservado";
}

// 1. Recoge lo escrito en los filtros.
$titulo = $_GET["titulo"] ?? "";
$genero = $_GET["genero"] ?? "";
$autor = $_GET["autor"] ?? "";
$director = $_GET["director"] ?? "";
$anio = $_GET["anio"] ?? "";

// 2. Prepara los textos para usarlos con LIKE en SQL.
$titulo_like = "%" . $titulo . "%";
$genero_like = "%" . $genero . "%";
$director_like = "%" . $director . "%";
$anio_like = "%" . $anio . "%";

// 3. Consulta los libros por titulo, genero y año.
$consulta_libros = "SELECT l.*
                    FROM libros l
                    WHERE l.Titulo LIKE ?
                    AND l.Genero LIKE ?
                    AND l.`$col_anio` LIKE ?
                    ORDER BY l.Id";

$sentencia_libros = $conexion->prepare($consulta_libros);
$sentencia_libros->bind_param("sss", $titulo_like, $genero_like, $anio_like);
$sentencia_libros->execute();
$resultado_libros = $sentencia_libros->get_result();

// 4. A cada libro le añade el autor y el estado.
$libros = [];
while($libro = $resultado_libros->fetch_assoc()){
    $libro["Autor"] = sacar_autor($conexion, $libro["Autor_id"]);

    // El filtro de autor se hace aqui porque el nombre del campo viene raro en la BBDD.
    if($autor !== "" && stripos($libro["Autor"], $autor) === false){
        continue;
    }

    $libro["Disponible"] = libro_disponible($conexion, $libro["Id"]);
    $libros[] = $libro;
}

// 5. Consulta las peliculas por titulo, genero, director y año.
$consulta_peliculas = "SELECT p.ID AS Id,
                              p.Titulo,
                              p.Director,
                              p.Genero,
                              p.`$col_anio_pelicula` AS Anio
                       FROM peliculas p
                       WHERE p.Titulo LIKE ?
                       AND p.Genero LIKE ?
                       AND p.Director LIKE ?
                       AND p.`$col_anio_pelicula` LIKE ?
                       ORDER BY p.ID";

$sentencia_peliculas = $conexion->prepare($consulta_peliculas);
$sentencia_peliculas->bind_param("ssss", $titulo_like, $genero_like, $director_like, $anio_like);
$sentencia_peliculas->execute();
$resultado_peliculas = $sentencia_peliculas->get_result();

// 6. A cada pelicula le añade el estado.
$peliculas = [];
while($pelicula = $resultado_peliculas->fetch_assoc()){
    $pelicula["Disponible"] = pelicula_disponible($conexion, $pelicula["Id"]);
    $peliculas[] = $pelicula;
}

?>

<html>
    <body>
        <h2>Catalogo</h2>

        <p>
            <a href="/biblioteca/inicio.php">Volver</a> |
            <a href="/biblioteca/reservas.php">Ver reservas</a>
        </p>

        <!-- 7. Formulario de filtros del catalogo -->
        <form action="/biblioteca/catalogo.php" method="GET">
            <label>Titulo</label>
            <input type="text" name="titulo" value="<?php echo $titulo; ?>">

            <label>Genero</label>
            <input type="text" name="genero" value="<?php echo $genero; ?>">

            <label>Autor</label>
            <input type="text" name="autor" value="<?php echo $autor; ?>">

            <label>Director</label>
            <input type="text" name="director" value="<?php echo $director; ?>">

            <label>Anio</label>
            <input type="text" name="anio" value="<?php echo $anio; ?>">

            <input type="submit" value="Filtrar">
        </form>

        <h3>Libros</h3>
        <!-- 8. Tabla de libros -->
        <table border="1" cellpadding="5">
            <tr>
                <th>Titulo</th>
                <th>Autor</th>
                <th>Genero</th>
                <th>Anio</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>

            <?php foreach($libros as $libro): ?>
                <tr>
                    <td><?php echo $libro["Titulo"]; ?></td>
                    <td><?php echo $libro["Autor"]; ?></td>
                    <td><?php echo $libro["Genero"]; ?></td>
                    <td><?php echo $libro[$col_anio]; ?></td>
                    <td><?php echo $libro["Disponible"]; ?></td>
                    <td>
                        <!-- Si el libro esta libre, deja reservarlo -->
                        <?php if($libro["Disponible"] == "Disponible"): ?>
                            <a href="/biblioteca/reservar.php?tipo=libro&id=<?php echo $libro["Id"]; ?>">Reservar</a>
                        <?php else: ?>
                            No disponible
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Peliculas</h3>
        <!-- 9. Tabla de peliculas -->
        <table border="1" cellpadding="5">
            <tr>
                <th>Titulo</th>
                <th>Director</th>
                <th>Genero</th>
                <th>Anio</th>
                <th>Estado</th>
                <th>Accion</th>
            </tr>

            <?php foreach($peliculas as $pelicula): ?>
                <tr>
                    <td><?php echo $pelicula["Titulo"]; ?></td>
                    <td><?php echo $pelicula["Director"]; ?></td>
                    <td><?php echo $pelicula["Genero"]; ?></td>
                    <td><?php echo $pelicula["Anio"]; ?></td>
                    <td><?php echo $pelicula["Disponible"]; ?></td>
                    <td>
                        <!-- Si la pelicula esta libre, deja reservarla -->
                        <?php if($pelicula["Disponible"] == "Disponible"): ?>
                            <a href="/biblioteca/reservar.php?tipo=pelicula&id=<?php echo $pelicula["Id"]; ?>">Reservar</a>
                        <?php else: ?>
                            No disponible
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>