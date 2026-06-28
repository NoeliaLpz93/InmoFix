<?php
require "conection.php";

// Obtener lista de inquilinos
$sqlInquilinos = "SELECT IdUsuario, Nombre, Apellido FROM Usuarios";
$inquilinos = $conn->query($sqlInquilinos);

// Obtener lista de inmuebles
$sqlInmuebles = "SELECT IdInmueble, Direccion FROM Inmuebles";
$inmuebles = $conn->query($sqlInmuebles);

if ($_POST) {

    $IdInquilino = $_POST["IdInquilino"];
    $IdInmueble = $_POST["IdInmueble"];
    $Categoria = $_POST["Categoria"];
    $Descripcion = $_POST["Descripcion"];
    $Estado = "Pendiente"; // siempre inicia pendiente
    $Prioridad = $_POST["Prioridad"];
    $FechaCreacion = date("Y-m-d H:i:s");

    $sql = "INSERT INTO Reclamos 
            (IdInquilino, IdInmueble, Categoria, Descripcion, Estado, FechaCreacion, Prioridad)
            VALUES 
            ('$IdInquilino', '$IdInmueble', '$Categoria', '$Descripcion', '$Estado', '$FechaCreacion', '$Prioridad')";

    $conn->query($sql);

    header("Location: reclamos.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cargar Reclamo</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>
<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Cargar Reclamo</h2>

    <form method="POST">

        <!-- INQUILINO -->
        <label>Inquilino:</label>
        <select name="IdInquilino" required>
            <option value="">Seleccionar...</option>
            <?php while ($i = $inquilinos->fetch_assoc()) { ?>
                <option value="<?= $i['IdUsuario'] ?>">
                    <?= $i['Nombre'] . " " . $i['Apellido'] ?>
                </option>
            <?php } ?>
        </select>
        <br>

        <!-- INMUEBLE -->
        <label>Inmueble:</label>
        <select name="IdInmueble" required>
            <option value="">Seleccionar...</option>
            <?php while ($m = $inmuebles->fetch_assoc()) { ?>
                <option value="<?= $m['IdInmueble'] ?>">
                    <?= $m['Direccion'] ?>
                </option>
            <?php } ?>
        </select>
        <br>

        <!-- CATEGORÍA -->
        <label>Categoría:</label>
        <select name="Categoria" required>
            <option value="Plomería">Plomería</option>
            <option value="Electricidad">Electricidad</option>
            <option value="Humedad">Humedad</option>
            <option value="Gas">Gas</option>
            <option value="Pintura">Pintura</option>
            <option value="Otros">Otros</option>
        </select>
        <br>

        <!-- PRIORIDAD -->
        <label>Prioridad:</label>
        <select name="Prioridad" required>