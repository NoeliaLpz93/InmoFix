<?php

require "verificar_admin.php";
require "conection.php";

// Obtenemos el ID del reclamo desde la URL
$id = (int) $_GET["id"];

// Buscamos el estado actual del reclamo
$sql = "SELECT Estado FROM Reclamos WHERE IdReclamo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$reclamo = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Si no existe el reclamo, volvemos al listado
if (!$reclamo) {
    header("Location: reclamos.php");
    exit();
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nuevoEstado = $_POST["estado"];

    // Actualizamos el estado del reclamo
    $update = "UPDATE Reclamos SET Estado = ? WHERE IdReclamo = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("si", $nuevoEstado, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: reclamos_detalle.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cambiar Estado</title>
</head>
<body>

<h2>Cambiar Estado del Reclamo</h2>

<!-- Formulario para cambiar el estado -->
<form method="POST">

    <label>Estado:</label>
 <!-- Lista de estados disponibles -->    
    <select name="estado">
        <option value="Pendiente" <?= $reclamo['Estado']=="Pendiente" ? "selected" : "" ?>>Pendiente</option>
        <option value="En Proceso" <?= $reclamo['Estado']=="En Proceso" ? "selected" : "" ?>>En Proceso</option>
        <option value="Resuelto" <?= $reclamo['Estado']=="Resuelto" ? "selected" : "" ?>>Resuelto</option>
    </select>

    <br><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
