<?php
// Luego esto vendrá de MySQL
$contrato = [
    'id' => 56,
    'inquilino' => 'Juan Perez',
    'inmueble' => 'San Martin 123',
    'estado' => 'Activo',
    'vencimiento' => '1/02/2028'
];
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
