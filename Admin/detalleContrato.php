<?php
// Luego esto viene de MySQL
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
    <title>Detalle del contrato</title>
</head>
<body>

    <h1>Detalle del contrato</h1>

    <p><strong>Id Contrato:</strong> #<?php echo $contrato['id']; ?></p>
    <p><strong>Inquilino:</strong> <?php echo $contrato['inquilino']; ?></p>
    <p><strong>Inmueble:</strong> <?php echo $contrato['inmueble']; ?></p>
    <p><strong>Estado:</strong> <?php echo $contrato['estado']; ?></p>
    <p><strong>Vencimiento:</strong> <?php echo $contrato['vencimiento']; ?></p>

    <br>

    <button onclick="window.location.href='contratos.php'">Volver</button>

</body>
</html>
