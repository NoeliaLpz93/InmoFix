<?php
require "../conection.php";

$id = $_GET["id"];

$sql = "SELECT Estado FROM Reclamos WHERE IdReclamo = $id";
$reclamo = $conn->query($sql)->fetch_assoc();

if ($_POST) {
    $nuevoEstado = $_POST["estado"];

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

<form method="POST">

    <label>Estado:</label>
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
