<?php

require "verificar_admin.php";
require "conection.php";

// Obtenemos el ID del reclamo que se quiere eliminar
$id = (int) $_GET["id"];

// Eliminamos el reclamo de la base de datos
$sql = "DELETE FROM Reclamos WHERE IdReclamo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

// Volvemos a la lista de reclamos
header("Location: reclamos.php");
exit();