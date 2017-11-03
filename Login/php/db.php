<?php
    // Simple conexión PDO (PHP Data Object) a la base de datos y el archivo php. Para posterior uso en checkUser.php

    $dbhost     = 'localhost'; // Donde se hospeda la base de datos
    $dbuser     = 'root';      // Nombre de usuario para la base de datos
    $dbpass     = '';          // Contraseña para la base de datos
    $dbname    = 'proyecto';   // Base de datos a seleccionar

    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname); // Conexión a la base de datos atentiendo a los valores definidos ^
?>