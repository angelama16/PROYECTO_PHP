<?php

// Ejercicio 2: cerrar la sesion actual.
session_start();
session_unset();
session_destroy();

header("Location: /biblioteca/login.php");
exit;

?>
