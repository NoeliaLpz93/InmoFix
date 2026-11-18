<?php
require "conection.php";

// ACTUALIZAR PAGOS VENCIDOS
$sqlVencidos = "UPDATE pagos
                SET Estado = 'Vencido'
                WHERE Estado = 'Pendiente'
                AND FechaVencimiento < CURDATE()";

$conn->query($sqlVencidos);

// CONSULTA DE PAGOS
$sql = "SELECT IdPago, IdContrato, Estado, Monto,
               FechaEmision, FechaVencimiento, FechaPago
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
                        <th>Contrato</th>
                        <th>Fecha de emisión</th>
                        <th>Fecha de vencimiento</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha de pago</th>
                        <th>Acciones</th>
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
                                    Contrato #<?= htmlspecialchars($fila["IdContrato"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($fila["FechaEmision"] ?? "—") ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($fila["FechaVencimiento"] ?? "—") ?>
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
                                    <?= htmlspecialchars($fila["FechaPago"] ?? "—") ?>
                                </td>

                                <td>
                                    <?php if ($fila["Estado"] != "Pagado") { ?>

                                        <button
                                            type="button"
                                            class="btn btn-registrar-pago"
                                            data-id="<?= (int) $fila["IdPago"] ?>"
                                        >
                                            Registrar pago
                                        </button>

                                    <?php } else { ?>

                                        Pagado

                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="8">
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

                    <!-- FECHA DE EMISIÓN -->
                    <div class="campo-inquilino">

                        <label for="FechaEmision">Fecha de emisión</label>

                        <input
                            type="date"
                            id="FechaEmision"
                            name="FechaEmision"
                            value="<?= date('Y-m-d') ?>"
                            required
                        >

                    </div>

                    <!-- FECHA DE VENCIMIENTO -->
                    <div class="campo-inquilino">

                        <label for="FechaVencimiento">Fecha de vencimiento</label>

                        <input
                            type="date"
                            id="FechaVencimiento"
                            name="FechaVencimiento"
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


    <!-- MODAL REGISTRAR PAGO -->
    <div class="modal-fondo" id="modal-registrar-pago">

        <div class="modal-inmueble">

            <!-- ENCABEZADO -->
            <div class="modal-header">

                <h2>
                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </span>
                    Registrar pago
                </h2>

                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-registrar"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

            <!-- FORMULARIO -->
            <form
                action="pago_registrar.php"
                method="POST"
                class="formulario-inquilino"
            >

                <div class="campos-inquilino">

                    <input
                        type="hidden"
                        name="IdPago"
                        id="id-pago-registrar"
                    >

                    <div class="campo-inquilino campo-inquilino-completo">

                        <label for="FechaPago">Fecha en que se recibió el pago</label>

                        <input
                            type="date"
                            id="FechaPago"
                            name="FechaPago"
                            value="<?= date('Y-m-d') ?>"
                            required
                        >

                    </div>

                </div>

                <!-- BOTÓN CONFIRMAR -->
                <div class="modal-footer">

                    <button type="submit" class="btn modal-guardar">
                        <i class="fa-solid fa-check"></i>
                        Confirmar pago
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

        // MODAL REGISTRAR PAGO

const botonesRegistrarPago =
    document.querySelectorAll(".btn-registrar-pago");

const modalRegistrarPago =
    document.getElementById("modal-registrar-pago");

const cerrarModalRegistrar =
    document.getElementById("cerrar-modal-registrar");

const idPagoRegistrar =
    document.getElementById("id-pago-registrar");

// Abrir modal
botonesRegistrarPago.forEach(function (boton) {

    boton.addEventListener("click", function () {

        idPagoRegistrar.value = boton.dataset.id;

        modalRegistrarPago.classList.add("modal-visible");

    });

});

// Cerrar con la X
cerrarModalRegistrar.addEventListener("click", function () {

    modalRegistrarPago.classList.remove("modal-visible");

});

// Cerrar al hacer clic fuera
modalRegistrarPago.addEventListener("click", function (e) {

    if (e.target === modalRegistrarPago) {
        modalRegistrarPago.classList.remove("modal-visible");
    }

});
    </script>

</body>
</html>
