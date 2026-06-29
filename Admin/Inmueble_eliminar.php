<?php
require "conection.php";

$id = $_GET["id"]; //obtiene el ID del inmueble que queremos eliminar

$sql = "DELETE FROM inmuebles WHERE id = $id"; // Crea la consulta SQL para eliminar el inmueble
$conn->query($sql); // Ejecuta la consulta

header("Location: inmuebles.php"); // Redirige al listado de inmuebles
exit;
