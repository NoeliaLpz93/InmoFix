<?php
require "conection.php"; // porque tu conexion.php está dentro de Admin

$id = $_GET["id"]; // viene desde contratos.php

$sql = "SELECT c.*, 
               u.Nombre AS NombreInquilino,
               u.Apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM contratos c
        INNER JOIN usuarios u ON c.IdInquilino = u.IdUsuario
        INNER JOIN inmuebles i ON c.IdInmueble = i.IdInmueble
        WHERE c.IdContrato = $id";

$contrato = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del contrato</title>
</head>
<body>

    <h1>Detalle del contrato</h1>

    <!-- Mostramos la información del contrato -->
    <p><strong>Id Contrato:</strong> #<?php echo $contrato['id']; ?></p>
    <p><strong>Inquilino:</strong> <?php echo $contrato['inquilino']; ?></p>
    <p><strong>Inmueble:</strong> <?php echo $contrato['inmueble']; ?></p>
    <p><strong>Estado:</strong> <?php echo $contrato['estado']; ?></p>
    <p><strong>Vencimiento:</strong> <?php echo $contrato['vencimiento']; ?></p>

    <br>

    <button onclick="window.location.href='contratos.php'">Volver</button>

</body>
</html>
