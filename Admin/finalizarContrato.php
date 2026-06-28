<?php
require "conection.php"; // porque todo está dentro de /Admin

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
    <title>Finalizar contrato</title>
</head>
<body>

    <h1>Finalizar contrato</h1>

    <p>¿Estás segura de que querés finalizar este contrato?</p>

    <p><strong>Id Contrato:</strong> #<?php echo $contrato['id']; ?></p>
    <p><strong>Inquilino:</strong> <?php echo $contrato['inquilino']; ?></p>
    <p><strong>Inmueble:</strong> <?php echo $contrato['inmueble']; ?></p>

    <br>

    <button>Confirmar</button>
    <button onclick="window.location.href='contratos.php'">Cancelar</button>

</body>
</html>
