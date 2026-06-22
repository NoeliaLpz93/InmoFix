<?php
// Luego esto vendrá de MySQL
$contratos = [
    [56, "Juan Perez", "San Martin 123", "Activo", "1/02/2028"],
    [57, "Ana López", "Córdoba 312", "Por vencer", "15/06/2026"],
    [58, "Sergio Rodriguez", "Colon 123", "Finalizado", "2/04/2026"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>InmoFix - Contratos</title>
</head>
<body>

    <!-- NAV EXACTO -->
    <nav>
        <span>Home</span>
        <span>Inmuebles</span>
        <span>Contratos</span>
        <span>Reclamos</span>
        <span>Pagos</span>
    </nav>

    <h1>Contratos</h1>

    <!-- BUSCADOR EXACTO -->
    <div>
        <input type="text" placeholder="Buscar por DNI o nombre">
        <button>Agregar contrato</button>
    </div>

    <!-- TABLA EXACTA -->
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Id Contrato</th>
                <th>Inquilino</th>
                <th>Inmueble</th>
                <th>Estado</th>
                <th>Vencimiento</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($contratos as $c): ?>
                <tr>
                    <td>#<?php echo $c[0]; ?></td>
                    <td><?php echo $c[1]; ?></td>
                    <td><?php echo $c[2]; ?></td>
                    <td><?php echo $c[3]; ?></td>
                    <td><?php echo $c[4]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- BOTONES EXACTAMENTE ABAJO DE LA TABLA -->
 <div style="margin-top: 15px;">
    <button onclick="window.location.href='detalleContrato.php?id=1'">Detalle</button>
    <button onclick="window.location.href='editarContrato.php?id=1'">Editar</button>
    <button onclick="window.location.href='finalizarContrato.php?id=1'">Finalizar</button>
</div>


</body>
</html>
