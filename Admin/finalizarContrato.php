<?php
// Luego esto vendrá de MySQL
$contrato = [
    'id' => 56,
    'inquilino' => 'Juan Perez',
    'inmueble' => 'San Martin 123'
];
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
