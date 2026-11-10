<?php
require "conection.php";

// Obtener lista de inquilinos
$sqlInquilinos = "SELECT IdUsuario, Nombre, Apellido FROM Usuarios";
$inquilinos = $conn->query($sqlInquilinos);

// Obtener lista de inmuebles
$sqlInmuebles = "SELECT IdInmueble, Direccion FROM Inmuebles";
$inmuebles = $conn->query($sqlInmuebles);

// Si se envió el formulario
if ($_POST) {

    $IdInquilino = $_POST["IdInquilino"];
    $IdInmueble = $_POST["IdInmueble"];
    $Categoria = $_POST["Categoria"];
    $Descripcion = $_POST["Descripcion"];
    $Estado = "Pendiente"; // siempre inicia pendiente
    $Prioridad = $_POST["Prioridad"];
    $FechaCreacion = date("Y-m-d H:i:s");   // fecha y hora en que se crea el reclamo

    // Insertamos el nuevo reclamo en la base de datos
    $sql = "INSERT INTO Reclamos 
            (IdInquilino, IdInmueble, Categoria, Descripcion, Estado, FechaCreacion, Prioridad)
            VALUES 
            ('$IdInquilino', '$IdInmueble', '$Categoria', '$Descripcion', '$Estado', '$FechaCreacion', '$Prioridad')";

 // Ejecutamos la consulta
    $conn->query($sql);

    header("Location: Reclamos.php");
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

    <div class="form-reclamo">

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
                <option value="Alta">Alta</option>
    <option value="Media">Media</option>
    <option value="Baja">Baja</option>
</select>

<br>

<label>Detalle del reclamo:</label>

<textarea
    name="Descripcion"
    rows="5"
    required>
</textarea>

<br>

<button
    type="submit"
    class="btn-guardar-reclamo">

    Guardar Reclamo

</button>

</form>

</div>

</div>

</body>
</html>