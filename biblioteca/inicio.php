<?php

// Ejercicio 2 y 5: portada privada.
require "config/sesion.php";

?>

<html>
    <body>
        <h2>Biblioteca</h2>

        <p>Usuario: <?php echo $_SESSION["usuario"]; ?></p>

        <!-- Ejercicio 5: menu principal -->
        <ul>
            <li><a href="/biblioteca/clientes.php">Gestión de clientes</a></li>
            <li><a href="/biblioteca/catalogo.php">Catálogo</a></li>
            <li><a href="/biblioteca/reservas.php">Reservas</a></li>
            <li><a href="/biblioteca/config/cerrarsesion.php">Cerrar sesión</a></li>
        </ul>
    </body>
</html>
