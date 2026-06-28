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
    <title>Editar contrato</title>
</head>
<body>

    <h1>Editar contrato</h1>

    <form method="post">

        <label>Inquilino:</label><br>
        <input type="text" name="inquilino" value="<?php echo $contrato['inquilino']; ?>"><br><br>

        <label>Inmueble:</label><br>
        <input type="text" name="inmueble" value="<?php echo $contrato['inmueble']; ?>"><br><br>

        <label>Estado:</label><br>
        <input type="text" name="estado" value="<?php echo $contrato['estado']; ?>"><br><br>

        <label>Vencimiento:</label><br>
        <input type="text" name="vencimiento" value="<?php echo $contrato['vencimiento']; ?>"><br><br>

        <button type="submit">Guardar cambios</button>
        <button type="button" onclick="window.location.href='contratos.php'">Cancelar</button>

    </form>

</body>
</html>
