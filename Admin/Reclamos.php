<?php

require "verificar_admin.php";
require "conection.php";

$busqueda = $_GET["q"] ?? "";

// Reclamos + datos del inquilino + datos del inmueble
$sql = "SELECT r.*,
               q.nombre AS NombreInquilino,
               q.apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM Reclamos r
        INNER JOIN inquilinos q ON r.IdInquilino = q.id
        INNER JOIN Inmuebles i ON r.IdInmueble = i.IdInmueble
        WHERE q.nombre LIKE ?
        OR q.apellido LIKE ?
        OR i.Direccion LIKE ?";

// Ejecutamos la consulta
$stmt = $conn->prepare($sql);

$termino = "%" . $busqueda . "%";

$stmt->bind_param("sss", $termino, $termino, $termino);
$stmt->execute();

$resultado = $stmt->get_result();

// Obtener los inquilinos para el modal de agregar reclamo
$sqlInquilinos = "SELECT id, nombre, apellido
                  FROM inquilinos
                  ORDER BY apellido, nombre";

$inquilinos = $conn->query($sqlInquilinos);


// Obtener los inmuebles para el modal de agregar reclamo
$sqlInmuebles = "SELECT IdInmueble, Direccion
                 FROM Inmuebles
                 ORDER BY Direccion";

$inmuebles = $conn->query($sqlInmuebles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reclamos</title>
    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
</head>

<body>

    <?php include "nav.php"; ?>

    <div class="contenedor">

        <h2>Reclamos</h2>
    
        <!-- CARGAR RECLAMO --> 
 
        <div class="acciones-superior">

            <button
                type="button"
                class="btn"
                id="btn-agregar-reclamo"
            >
                <i class="fa-solid fa-plus"></i>
                Agregar reclamo
            </button>

            <!-- BUSCADOR -->
            <form method="GET" class="buscador">

                <input
                    type="text"
                    name="q"
                    placeholder="Buscar por categoría o dirección"
                    value="<?= htmlspecialchars($busqueda) ?>"
                >

                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <!-- TABLA -->
        <div class="tabla-contenedor">

        <table class="tabla-inquilinos">

            <thead>
                <tr>
                    <th>N° Reclamo</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha</th>
                    <th>Inquilino</th>
                    <th>Inmueble</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()) { ?>

                    <?php
                    // Colores según estado
                    $colorEstado = [
                        "Pendiente" => "estado-pendiente",
                        "En proceso" => "estado-proceso",
                        "En Proceso" => "estado-proceso",
                        "Resuelto" => "estado-resuelto"
                    ];

                    // Colores según prioridad
                    $colorPrioridad = [
                        "Alta" => "prioridad-alta",
                        "Media" => "prioridad-media",
                        "Baja" => "prioridad-baja"
                    ];
                ?>

                <tr>
                    <td>#<?= $fila["IdReclamo"] ?></td>

                    <td class="<?= htmlspecialchars($colorEstado[$fila["Estado"]] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($fila["Estado"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td class="<?= htmlspecialchars($colorPrioridad[$fila["Prioridad"]] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($fila["Prioridad"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($fila["FechaCreacion"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($fila["NombreInquilino"] . " " . $fila["ApellidoInquilino"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($fila["DireccionInmueble"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($fila["Categoria"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <div class="acciones-tabla">

                            <a
                                href="reclamos_detalle.php?id=<?= (int) $fila['IdReclamo'] ?>"
                                class="btn"
                            >
                                <i class="fa-solid fa-circle-info"></i>
                                Detalle
                            </a>

                            <button
                                type="button"
                                class="btn btn-naranja btn-cambiar-estado"
                                data-id="<?= (int) $fila['IdReclamo'] ?>"
                                data-estado="<?= htmlspecialchars($fila['Estado'], ENT_QUOTES, 'UTF-8') ?>"
                            >
                                <i class="fa-solid fa-pen"></i>
                                Cambiar Estado
                            </button>

                        </div>
                    </td>
                </tr>

            <?php } ?>
        </tbody>

    </table>

    </div>
    </div>

    <!-- MODAL AGREGAR RECLAMO -->
    <div class="modal-fondo" id="modal-agregar-reclamo">
        <div class="modal-inmueble">

        <div class="modal-header">
            <h2>
                <span class="modal-icono-titulo">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </span>
                Agregar reclamo
            </h2>

            <button type="button" class="modal-cerrar"
                    id="cerrar-modal-reclamo"
                    aria-label="Cerrar formulario">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="reclamos_cargar.php" method="POST"
              class="formulario-inquilino">

            <div class="modal-columna-datos">

                <div class="campo-inmueble">
                    <label>Inquilino</label>
                    <select name="IdInquilino" required>
                        <option value="">Seleccionar inquilino</option>
                        <?php while ($inquilino = $inquilinos->fetch_assoc()) { ?>
                            <option value="<?= $inquilino['id'] ?>">
                                <?= htmlspecialchars($inquilino['apellido'] . ", " . $inquilino['nombre']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-inmueble">
                    <label>Inmueble</label>
                    <select name="IdInmueble" required>
                        <option value="">Seleccionar inmueble</option>
                        <?php while ($inmueble = $inmuebles->fetch_assoc()) { ?>
                            <option value="<?= $inmueble['IdInmueble'] ?>">
                                <?= htmlspecialchars($inmueble['Direccion']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-inmueble">
                    <label>Categoría</label>
                    <select name="Categoria" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="Plomería">Plomería</option>
                        <option value="Electricidad">Electricidad</option>
                        <option value="Humedad">Humedad</option>
                        <option value="Gas">Gas</option>
                        <option value="Pintura">Pintura</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="campo-inmueble">
                    <label>Prioridad</label>
                    <select name="Prioridad" required>
                        <option value="">Seleccionar prioridad</option>
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>

                <div class="campo-inmueble">
                    <label>Descripción</label>
                    <textarea name="Descripcion" rows="5" required></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn modal-guardar">
                    <i class="fa-solid fa-check"></i>
                    Guardar reclamo
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL CAMBIAR ESTADO -->
<div class="modal-fondo" id="modal-cambiar-estado">

    <div class="modal-inmueble">

        <div class="modal-header">
            <h2>
                <span class="modal-icono-titulo">
                    <i class="fa-solid fa-pen"></i>
                </span>
                Cambiar estado
            </h2>

            <button
                type="button"
                class="modal-cerrar"
                id="cerrar-modal-estado"
                aria-label="Cerrar formulario"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="reclamos_estado.php" method="POST"
              class="formulario-inmueble">

            <input type="hidden" name="id" id="id-reclamo-estado">

            <div class="modal-columna-datos">

                <div class="campo-inmueble">
                    <label for="nuevo-estado">Estado</label>

                    <select name="estado" id="nuevo-estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Resuelto">Resuelto</option>
                    </select>
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

<script>
    const botonAgregarReclamo = document.getElementById("btn-agregar-reclamo");
    const modalAgregarReclamo = document.getElementById("modal-agregar-reclamo");
    const botonCerrarReclamo = document.getElementById("cerrar-modal-reclamo");

    // Abrir modal
    botonAgregarReclamo.addEventListener("click", function () {
        modalAgregarReclamo.classList.add("modal-visible");
    });

    // Cerrar desde la X
    botonCerrarReclamo.addEventListener("click", function () {
        modalAgregarReclamo.classList.remove("modal-visible");
    });

    // Cerrar al hacer clic fuera del formulario
    modalAgregarReclamo.addEventListener("click", function (evento) {
        if (evento.target === modalAgregarReclamo) {
            modalAgregarReclamo.classList.remove("modal-visible");
        }
    });

    // MODAL CAMBIAR ESTADO

const botonesCambiarEstado = document.querySelectorAll(".btn-cambiar-estado");
const modalCambiarEstado = document.getElementById("modal-cambiar-estado");
const botonCerrarEstado = document.getElementById("cerrar-modal-estado");

const inputIdReclamo = document.getElementById("id-reclamo-estado");
const selectorEstado = document.getElementById("nuevo-estado");

// Abrir modal y cargar los datos del reclamo
botonesCambiarEstado.forEach(function (boton) {

    boton.addEventListener("click", function () {

        inputIdReclamo.value = boton.dataset.id;
        selectorEstado.value = boton.dataset.estado;

        modalCambiarEstado.classList.add("modal-visible");

    });

});

// Cerrar con la X
botonCerrarEstado.addEventListener("click", function () {

    modalCambiarEstado.classList.remove("modal-visible");

});

// Cerrar al hacer clic fuera
modalCambiarEstado.addEventListener("click", function (evento) {

    if (evento.target === modalCambiarEstado) {

        modalCambiarEstado.classList.remove("modal-visible");

    }

});
</script>
</body>
</html>
