<?php

// Ejercicio 2: si ya hay sesion, entra al inicio.
session_start();

if(isset($_SESSION["usuario"])){
    header("Location: /biblioteca/inicio.php");
    exit;
}

?>

<html>
    <body>
        <h2>Login</h2>

        <!-- Ejercicio 2: formulario de login -->
        <form action="/biblioteca/comprobarlogin.php" method="POST">
            <label>Usuario</label>
            <input type="text" name="usuario" required>

            <label>Contrasena</label>
            <input type="password" name="contrasena" required>

            <input type="submit" value="Iniciar sesion">
        </form>

        <p><a href="/biblioteca/registro.html">Registrarse</a></p>
    </body>
</html>
