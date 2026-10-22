<?php
require "conection.php";

// CONSULTA DE PAGOS
$sql = "SELECT IdPago, IdContrato, Estado, Monto, FechaPago
        FROM pagos
        ORDER BY IdPago DESC";

$resultado = $conn->query($sql);

// CONSULTA DE CONTRATOS PARA EL FORMULARIO
$sqlContratos = "SELECT IdContrato FROM contratos ORDER BY IdContrato DESC";
$resultadoContratos = $conn->query($sqlContratos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pagos</title>

    <!-- Tipografía -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Estilos generales de InmoFix -->
    <link rel="stylesheet" href="../css/estilo.css?v=1">

    <!-- Iconos -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >
</head>

<body>

    <!-- Barra de navegación existente -->
    <?php include "nav.php"; ?>

    <div class="contenedor">

        <h2>Pagos</h2>

        <!-- BOTÓN AGREGAR -->
        <div class="acciones-superior">

            <button
                type="button"
                class="btn"
                id="btn-agregar-pago"
            >
                <i class="fa-solid fa-plus"></i>
                Agregar Pago
            </button>

        </div>

        <!-- TABLA DE PAGOS -->
        <div class="tabla-contenedor">

            <table class="tabla-inquilinos">

                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Fecha</th> <!--Acá hay que modificar la base de datos para que esto sea la fecha de vencimiento-->
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha de pago</th> 
                    </tr>
                </thead>

                <tbody>

                    <?php if ($resultado && $resultado->num_rows > 0) { ?>

                        <?php while ($fila = $resultado->fetch_assoc()) { ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($fila["IdPago"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($fila["FechaPago"]) ?>
                                </td>

                                <td>
                                    $<?= number_format(
                                        (float) $fila["Monto"],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($fila["Estado"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($fila["FechaPago"]) ?>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="5">
                                No hay pagos registrados.
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- MODAL AGREGAR PAGO -->
    <div class="modal-fondo" id="modal-agregar-pago">

        <div class="modal-inmueble">

            <!-- ENCABEZADO -->
            <div class="modal-header">

                <h2>
                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </span>
                    Agregar pago
                </h2>

                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-pago"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

            <!-- FORMULARIO -->
            <form
                action="pago_agregar.php"
                method="POST"
                class="formulario-inquilino"
            >

                <div class="campos-inquilino">

                    <!-- CONTRATO -->
                    <div class="campo-inquilino campo-inquilino-completo">

                        <label for="IdContrato">Contrato</label>

                        <select
                            id="IdContrato"
                            name="IdContrato"
                            required
                        >
                            <option value="">Seleccionar contrato</option>

                            <?php if ($resultadoContratos) { ?>

                                <?php while (
                                    $contrato = $resultadoContratos->fetch_assoc()
                                ) { ?>

                                    <option value="<?= (int) $contrato["IdContrato"] ?>">
                                        Contrato #<?= (int) $contrato["IdContrato"] ?>
                                    </option>

                                <?php } ?>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- MONTO -->
                    <div class="campo-inquilino">

                        <label for="Monto">Monto</label>

                        <input
                            type="number"
                            id="Monto"
                            name="Monto"
                            min="0.01"
                            step="0.01"
                            placeholder="0,00"
                            required
                        >

                    </div>

                    <!-- ESTADO -->
                    <div class="campo-inquilino">

                        <label for="Estado">Estado</label>

                        <select id="Estado" name="Estado" required>
                            <option value="">Seleccionar estado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Pagado">Pagado</option>
                            <option value="Vencido">Vencido</option>
                        </select>

                    </div>

                    <!-- FECHA DE PAGO -->
                    <div class="campo-inquilino campo-inquilino-completo">

                        <label for="FechaPago">Fecha de pago</label>

                        <input
                            type="date"
                            id="FechaPago"
                            name="FechaPago"
                            required
                        >

                    </div>

                </div>

                <!-- GUARDAR -->
                <div class="modal-footer">

                    <button type="submit" class="btn modal-guardar">
                        <i class="fa-solid fa-check"></i>
                        Guardar pago
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- JAVASCRIPT DEL MODAL -->
    <script>
        const botonAgregarPago =
            document.getElementById("btn-agregar-pago");

        const modalAgregarPago =
            document.getElementById("modal-agregar-pago");

        const botonCerrarPago =
            document.getElementById("cerrar-modal-pago");

        // Abrir modal
        botonAgregarPago.addEventListener("click", function () {
            modalAgregarPago.classList.add("modal-visible");
        });

        // Cerrar con la X
        botonCerrarPago.addEventListener("click", function () {
            modalAgregarPago.classList.remove("modal-visible");
        });

        // Cerrar al hacer clic fuera del modal
        modalAgregarPago.addEventListener("click", function (e) {
            if (e.target === modalAgregarPago) {
                modalAgregarPago.classList.remove("modal-visible");
            }
        });
    </script>

</body>
</html>
