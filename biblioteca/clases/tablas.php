<?php

// Ejercicio 3: clases con los campos reales de las tablas.
class Autor{
    public $ID;
    public $Autor;
    public $Fecha_nacimiento;
    public $Lugar_nacimiento;
    public $Lugar_defuncion;
}

class Cliente{
    public $Id;
    public $Nombre;
    public $Apellidos;
    public $Fecha_nacimiento;
    public $Localidad;
}

class Libro{
    public $Id;
    public $Titulo;
    public $Autor_id;
    public $Genero;
    public $Editorial;
    public $Paginas;
    public $Año;
    public $Precio;
}

class Pelicula{
    public $ID;
    public $Titulo;
    public $Año_estreno;
    public $Director;
    public $Actores;
    public $Genero;
    public $Tipo_adaptacion;
    public $Adaptacion_ID;
}

class Reserva{
    public $Id;
    public $Id_libro;
    public $Id_pelicula;
    public $Id_cliente;
    public $Fecha_reserva;
    public $Devuelto;
}

class Usuario{
    public $Id;
    public $Usuario;
    public $Contrasena;
    public $Email;
    public $Fecha_nacimiento;
}

?>