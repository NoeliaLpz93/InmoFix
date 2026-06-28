<?php
 $host = 'localhost'; // NOMBRE DEL HOST
 $user = 'root'; //USUARIO POR DEFECTO 
 $pass = '251293'; //CLAVE POR DEFECTO
 $bd = 'Inmofix'; //NOMBRE DE LA BD
$conn = mysqli_connect($host, $user, $pass, $bd);
 mysqli_set_charset($conn, 'utf8');
?>
