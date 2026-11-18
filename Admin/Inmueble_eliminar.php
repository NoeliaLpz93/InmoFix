<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) $_GET["id"];

// ELIMINAR INMUEBLE
$sql = "DELETE FROM inmuebles WHERE IdInmueble = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: inmuebles.php");
    exit();
} else {
    echo "Error al eliminar el inmueble: " . $stmt->error;
}

$stmt->close();

?>