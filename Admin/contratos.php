<?php
require "conection.php"; // porque tu conexion.php está dentro de Admin

// Consulta de contratos + datos del inquilino + datos del inmueble
$sql = "SELECT c.*, 
               u.Nombre AS NombreInquilino,
               u.Apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM contratos c
        INNER JOIN usuarios u ON c.IdInquilino = u.IdUsuario
        INNER JOIN inmuebles i ON c.IdInmueble = i.IdInmueble
        ORDER BY c.IdContrato DESC";

$contratos = $conn->query($sql);
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
