<?php
require "conection.php";

// Obtenemos el ID del reclamo desde la URL
$id = $_GET["id"];

// Buscamos el estado actual del reclamo
$sql = "SELECT Estado FROM Reclamos WHERE IdReclamo = $id";
$reclamo = $conn->query($sql)->fetch_assoc();

// Si se envió el formulario
if ($_POST) {
    $nuevoEstado = $_POST["estado"];
  // Actualizamos el estado del reclamo en la base de datos
    $update = "UPDATE Reclamos SET Estado='$nuevoEstado' WHERE IdReclamo=$id";
    $conn->query($update);

    header("Location: reclamo_detalles.php?id=$id");
    exit;
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
