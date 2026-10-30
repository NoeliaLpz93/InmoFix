<?php

require "conection.php";

// ==================================================
// BUSCADOR
// Recibe el texto por GET (?q=...). Se busca por nombre, apellido.
// ==================================================

$busqueda = trim($_GET["q"] ?? "");

// Se escapan % y \_ para que se busquen como texto literal dentro del LIKE

$like = "%" . addcslashes($busqueda, "%\_\\\\") . "%";

$sql = "SELECT
            c.IdContrato,
            c.Estado,
            c.FechaFin,
            c.FechaInicio,
            q.nombre   AS NombreInquilino,
            q.apellido AS ApellidoInquilino,
            i.Direccion AS DireccionInmueble
        FROM contratos c
        INNER JOIN inquilinos q ON c.IdInquilino = q.id
        INNER JOIN inmuebles  i ON c.IdInmueble  = i.IdInmueble
        WHERE (? = ''
               OR q.nombre   LIKE ?
               OR q.apellido LIKE ?
               OR q.dni      LIKE ?
               OR CONCAT(q.nombre, ' ', q.apellido) LIKE ?)
        ORDER BY c.IdContrato DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sssss", $busqueda, $like, $like, $like, $like);

$stmt->execute();

$contratos = $stmt->get_result();

// Obtener los inquilinos registrados para el formulario.

$sqlInquilinos = "SELECT id, nombre, apellido, dni
                  FROM inquilinos
                  ORDER BY apellido, nombre";
$inquilinos = $conn->query($sqlInquilinos);

// Obtener solamente los inmuebles disponibles.

$sqlInmuebles = "SELECT IdInmueble, Direccion, Tipo
                 FROM inmuebles
                 WHERE Estado = 'Disponible'
                 ORDER BY Direccion";

$inmueblesDisponibles = $conn->query($sqlInmuebles);

// Mensaje de éxito

$mensajes = [
    "agregado"   => "El contrato se creó correctamente.",
    "editado"    => "El contrato se actualizó correctamente.",
    "finalizado" => "El contrato se finalizó correctamente."
];

$mensajeOk = $mensajes[$_GET["ok"] ?? ""] ?? null;

?>


<!DOCTYPE html>

<html lang="es">

    <head>

        <meta charset="UTF-8">

        <title>InmoFix - Contratos</title>

        <!-- la letra -->

        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- css  -->

        <link rel="stylesheet" href="../Css/estilo.css?v=1">

    </head>

    <body>

        <?php include "nav.php"; ?> <!--Inserta la barra de navegación-->

        <?php if ($mensajeOk): ?>

            <div class="notificacion-toast" id="notificacion-toast">

                <i class="fa-solid fa-circle-check"></i>

                <span><?= htmlspecialchars($mensajeOk) ?></span>

            </div>

        <?php endif; ?>

        <div class="contenedor">

            <h2>Contratos</h2>

            <!-- BOTÓN AGREGAR + BUSCADOR -->

            <div class="acciones-superior">

                <!-- Todavía no existe la pantalla de alta de contratos -->

                <button type="button" class="btn" id="btn-agregar-contrato">

                    <i class="fa-solid fa-plus"></i>

                        Agregar contrato

                </button>

                <form method="GET" class="buscador">

                    <input
                        type="text"
                        name="q"
                        placeholder="Buscar por DNI o nombre"
                        value="<?= htmlspecialchars($busqueda) ?>"
                    >

                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

            </div>

            <!-- TABLA DE CONTRATOS -->

            <div class="tabla-contenedor">

                <table class="tabla-inquilinos">

                    <thead>
                        <tr>
                            <th>Id Contrato</th>
                            <th>Inquilino</th>
                            <th>Inmueble</th>
                            <th>Estado</th>
                            <th>Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                <tbody>

                <?php if ($contratos->num_rows > 0): ?>
                    <?php while ($c = $contratos->fetch_assoc()): ?>

                        <tr>
                            <td>#<?= (int) $c["IdContrato"] ?></td>
                            <td><?= htmlspecialchars($c["NombreInquilino"] . " " . $c["ApellidoInquilino"]) ?></td>
                            <td><?= htmlspecialchars($c["DireccionInmueble"]) ?></td>
                            <td><?= htmlspecialchars($c["Estado"]) ?></td>
                            <td><?= date("d/m/Y", strtotime($c["FechaFin"])) ?></td>

                            <td>
                                <!-- Cada botón lleva el id de SU fila (antes era siempre id=1) -->
                                <div class="acciones-tabla">
                                    <a href="detalleContrato.php?id=<?= (int) $c["IdContrato"] ?>" class="btn">
                                        <i class="fa-solid fa-circle-info"></i>
                                        Detalle
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-editar-contrato"

                                        data-id="<?= (int) $c["IdContrato"] ?>"
                                        data-estado="<?= htmlspecialchars($c["Estado"], ENT_QUOTES, 'UTF-8') ?>"
                                        data-fecha-inicio="<?= htmlspecialchars($c["FechaInicio"], ENT_QUOTES, 'UTF-8') ?>"
                                        data-fecha-fin="<?= htmlspecialchars($c["FechaFin"], ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                    <i class="fa-solid fa-pen"></i>
                                        Editar
                                    </button>

                                    <?php if ($c["Estado"] !== "Finalizado"): ?>
                                        <button
                                            type="button"
                                            class="btn btn-rojo btn-finalizar-contrato"
                                            data-id="<?= (int) $c["IdContrato"] ?>"
                                        >
                                            <i class="fa-solid fa-flag-checkered"></i>
                                                Finalizar
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                    <?php else: ?>

                    <tr>
                        <td colspan="6">
                            <?= $busqueda !== "" ? "No se encontraron contratos para esa búsqueda." : "No hay contratos registrados." ?>
                        </td>
                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- MODAL AGREGAR CONTRATO -->

    <div class="modal-fondo" id="modal-agregar-contrato">

        <div class="modal-inmueble">

            <div class="modal-header">

                <h2>

                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-file-circle-plus"></i>
                    </span>

                    Agregar contrato

                </h2>

                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-contrato"
                    aria-label="Cerrar formulario"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

            <form action="contrato_agregar.php" method="POST"

                class="formulario-inquilino">

                <div class="campos-inquilino">

                    <div class="campo-inquilino">

                        <label for="IdInquilino">Inquilino</label>

                        <select id="IdInquilino" name="IdInquilino" required>

                            <option value="">Seleccionar inquilino</option>

                            <?php while ($inquilino = $inquilinos->fetch_assoc()): ?>

                                <option value="<?= (int) $inquilino['id'] ?>">

                                    <?= htmlspecialchars(

                                        $inquilino['apellido'] . ', ' .

                                        $inquilino['nombre'] . ' - DNI ' .

                                        $inquilino['dni']

                                    ) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <div class="campo-inquilino">

                        <label for="IdInmueble">Inmueble</label>



                        <select id="IdInmueble" name="IdInmueble" required>

                            <option value="">Seleccionar inmueble</option>



                            <?php while ($inmueble = $inmueblesDisponibles->fetch_assoc()): ?>

                                <option value="<?= (int) $inmueble['IdInmueble'] ?>">

                                    <?= htmlspecialchars(

                                        $inmueble['Direccion'] . ' - ' .

                                        $inmueble['Tipo']

                                    ) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>



                    <div class="campo-inquilino">

                        <label for="FechaInicio">Fecha de inicio</label>

                        <input type="date" id="FechaInicio"

                            name="FechaInicio" required>

                    </div>



                    <div class="campo-inquilino">

                        <label for="FechaFin">Fecha de vencimiento</label>

                        <input type="date" id="FechaFin"

                            name="FechaFin" required>

                    </div>



                    <div class="campo-inquilino">

                        <label for="ValorInicial">Valor inicial del alquiler</label>

                        <input type="number" id="ValorInicial"

                            name="ValorInicial" min="0.01"

                            step="0.01" placeholder="Ej.: 250000" required>

                    </div>



                    <div class="campo-inquilino">

                        <label for="TipoAjuste">Tipo de ajuste</label>

                        <select id="TipoAjuste" name="TipoAjuste" required>

                            <option value="">Seleccionar ajuste</option>

                            <option value="Trimestral">Trimestral</option>

                            <option value="Cuatrimestral">Cuatrimestral</option>

                            <option value="Semestral">Semestral</option>

                            <option value="Porcentaje">Porcentaje</option>

                        </select>

                    </div>



                    <div class="campo-inquilino">

                        <label for="PorcentajeAjuste">

                            Porcentaje de ajuste

                        </label>

                        <input type="number" id="PorcentajeAjuste"

                            name="PorcentajeAjuste" min="0"

                            max="100" step="0.01"

                            placeholder="Ej.: 15">

                    </div>



                </div>



                <div class="modal-footer">

                    <button type="submit" class="btn modal-guardar">

                        <i class="fa-solid fa-check"></i>

                        Guardar contrato

                    </button>

                </div>



            </form>

        </div>

    </div>





    <!-- MODAL EDITAR CONTRATO -->
    <div class="modal-fondo" id="modal-editar-contrato">
        <div class="modal-inmueble">

            <div class="modal-header">
                <h2>
                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                    Editar contrato
                </h2>

                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-editar"
                    aria-label="Cerrar formulario"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form
                action="editarContrato.php"
                method="POST"
                class="formulario-inquilino"
                id="form-editar-contrato"
            >
                <input type="hidden" name="IdContrato" id="editar-IdContrato">

                <div class="campos-inquilino">
                    <div class="campo-inquilino">
                        <label for="editar-estado">Estado</label>
                        <select name="estado" id="editar-estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="campo-inquilino">
                        <label for="editar-fechaFin">Fecha de vencimiento</label>
                        <input
                            type="date"
                            name="fechaFin"
                            id="editar-fechaFin"
                            required
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn modal-guardar">
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FINALIZAR CONTRATO -->
    <div class="modal-fondo" id="modal-finalizar-contrato">

    <div class="modal-inmueble">

        <div class="modal-header">
            <h2>
                <span class="modal-icono-titulo">
                    <i class="fa-solid fa-flag-checkered"></i>
                </span>
                Finalizar contrato
            </h2>

            <button
                type="button"
                class="modal-cerrar"
                id="cerrar-modal-finalizar"
                aria-label="Cerrar confirmación"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form
            action="finalizarContrato.php"
            method="POST"
            class="formulario-inquilino"
            id="form-finalizar-contrato"
        >

            <input
                type="hidden"
                name="IdContrato"
                id="finalizar-IdContrato"
            >

            <p>
                ¿Estás seguro de que querés finalizar este contrato?
                Esta acción cambiará su estado a <strong>Finalizado</strong>.
            </p>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn"
                    id="cancelar-modal-finalizar"
                >
                    Cancelar
                </button>

                <button type="submit" class="btn btn-rojo">
                    <i class="fa-solid fa-flag-checkered"></i>
                    Sí, finalizar
                </button>

            </div>

        </form>

    </div>

    </div>

    <script>

        const botonAgregarContrato =
            document.getElementById("btn-agregar-contrato");

        const modalAgregarContrato =
            document.getElementById("modal-agregar-contrato");

        const botonCerrarContrato =
            document.getElementById("cerrar-modal-contrato");

        // Abrir el modal.
        botonAgregarContrato.addEventListener("click", function () {
        modalAgregarContrato.classList.add("modal-visible");

        });

        // Cerrar desde la X.
        botonCerrarContrato.addEventListener("click", function () {
        modalAgregarContrato.classList.remove("modal-visible");

        });

        // Cerrar al hacer clic fuera del formulario.

        modalAgregarContrato.addEventListener("click", function (evento) {
        if (evento.target === modalAgregarContrato) {
            modalAgregarContrato.classList.remove("modal-visible");
        }
        });

        // Evitar que la fecha de vencimiento sea anterior al inicio.
        const fechaInicio = document.getElementById("FechaInicio");

        const fechaFin = document.getElementById("FechaFin");


        fechaInicio.addEventListener("change", function () {

        fechaFin.min = fechaInicio.value;



        if (fechaFin.value && fechaFin.value < fechaInicio.value) {

            fechaFin.value = "";

        }

        });

    </script>

    <script>
        // Elementos del modal de edición.
        const modalEditar = document.getElementById("modal-editar-contrato");
        const cerrarModalEditar = document.getElementById("cerrar-modal-editar");
        const formularioEditar = document.getElementById("form-editar-contrato");

        const idEditar = document.getElementById("editar-IdContrato");
        const estadoEditar = document.getElementById("editar-estado");
        const fechaFinEditar = document.getElementById("editar-fechaFin");

        // Cada botón carga los datos del contrato de su propia fila.
        document.querySelectorAll(".btn-editar-contrato").forEach(function (boton) {
            boton.addEventListener("click", function () {
                idEditar.value = boton.dataset.id;
                estadoEditar.value = boton.dataset.estado;
                fechaFinEditar.value = boton.dataset.fechaFin;
                fechaFinEditar.min = boton.dataset.fechaInicio;

                formularioEditar.action = "editarContrato.php?id=" + boton.dataset.id;
                modalEditar.classList.add("modal-visible");
            });
        });

        // Cerrar desde la X.
        cerrarModalEditar.addEventListener("click", function () {
            modalEditar.classList.remove("modal-visible");
        });

        // Cerrar al hacer clic fuera del modal.
        modalEditar.addEventListener("click", function (evento) {
            if (evento.target === modalEditar) {
                modalEditar.classList.remove("modal-visible");
            }
        });
    </script>
    <script>
        const modalFinalizar = document.getElementById("modal-finalizar-contrato");

        document.querySelectorAll(".btn-finalizar-contrato").forEach(function (boton) {
            boton.addEventListener("click", function () {
                const id = boton.dataset.id;

                document.getElementById("finalizar-IdContrato").value = id;

                document.getElementById("form-finalizar-contrato").action =
                    "finalizarContrato.php?id=" + id;

                modalFinalizar.classList.add("modal-visible");
            });
        });

        document.getElementById("cerrar-modal-finalizar").addEventListener("click", function () {
            modalFinalizar.classList.remove("modal-visible");
        });

        document.getElementById("cancelar-modal-finalizar").addEventListener("click", function () {
            modalFinalizar.classList.remove("modal-visible");
        });

        modalFinalizar.addEventListener("click", function (evento) {
            if (evento.target === modalFinalizar) {
             modalFinalizar.classList.remove("modal-visible");
            }
        });
    git </script>
    <script>

        const notificacion = document.getElementById("notificacion-toast");

        if (notificacion) {

            setTimeout(function () {

                notificacion.classList.add("ocultar-toast");

            setTimeout(function () {

                notificacion.remove();

            }, 300);

            }, 4000);

        }

    </script>

</body>

</html>