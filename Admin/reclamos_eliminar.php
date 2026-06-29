<?php
require "conection.php";
// Obtenemos el ID del reclamo que se quiere eliminar
$id = $_GET["id"];
// Eliminamos el reclamo de la base de datos
$sql = "DELETE FROM Reclamos WHERE IdReclamo = $id";
$conn->query($sql);
// Volvemos a la lista de reclamos
header("Location: reclamos.php");
exit;
