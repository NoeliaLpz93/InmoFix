
<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) $_GET["id"];

// OBTENER DATOS DEL INMUEBLE
$sql = "SELECT * FROM inmuebles WHERE IdInmueble = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$inmueble = $resultado->fetch_assoc();

$stmt->close();

// VERIFICAR SI EXISTE EL INMUEBLE
if (!$inmueble) {
    header("Location: Inmuebles.php");
    exit();
}

// OBTENER RECLAMOS DEL INMUEBLE
$sqlReclamos = "SELECT * FROM reclamos
                WHERE IdInmueble = ?
                ORDER BY FechaCreacion DESC";

$stmtReclamos = $conn->prepare($sqlReclamos);
$stmtReclamos->bind_param("i", $id);
$stmtReclamos->execute();

$reclamos = $stmtReclamos->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalles del Inmueble</title>

    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!--css-->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
</head>

<body>
    <!--Navbar-->
    <?php include "nav.php"; ?>

    <div class="contenedor detalle-contenedor">

        <h2>Detalles del Inmueble</h2>

        <!-- INFORMACIÓN PRINCIPAL -->
        <div class="detalle-principal">

            <!-- FOTO -->
            <div class="detalle-foto-card">

                <img
                    src="../uploads/<?= htmlspecialchars($inmueble['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Imagen del inmueble"
                    class="detalle-foto"
                >

            </div>


        <!-- INFORMACIÓN -->
            <div class="detalle-info-card">

                <div class="detalle-info-header">
                    <h3>Información general</h3>

                    <span class="estado-inmueble">
                        <?= htmlspecialchars($inmueble["Estado"], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>


                <div class="detalle-dato">
                    <span class="detalle-label">Dirección</span>
                    <p><?= htmlspecialchars($inmueble["Direccion"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>


                <div class="detalle-dato">
                    <span class="detalle-label">Tipo de inmueble</span>
                    <p><?= htmlspecialchars($inmueble["Tipo"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>


                <div class="detalle-dato detalle-descripcion">
                    <span class="detalle-label">Descripción</span>
                    <p>
                        <?= !empty($inmueble["Descripcion"])
                            ? htmlspecialchars($inmueble["Descripcion"], ENT_QUOTES, 'UTF-8')
                            : "Este inmueble no tiene una descripción cargada." ?>
                    </p>
                </div>


                <!-- ACCIONES -->
                <div class="detalle-acciones">

                    <button
                        type="button"
                        class="btn"
                        id="btn-modificar-inmueble"
                        >
                        <i class="fa-solid fa-pen"></i>
                        Modificar
                    </button>

                    <a
                        href="inmueble_eliminar.php?id=<?= (int) $inmueble['IdInmueble'] ?>"
                        class="btn btn-rojo"
                        onclick="return confirm('¿Seguro que deseas eliminar este inmueble? Esta acción no se puede deshacer.');"
                    >
                        <i class="fa-solid fa-trash"></i>
                        Eliminar
                    </a>

                </div>

            </div>

        </div>


        <!-- CONTRATO -->
        <div class="detalle-bloque">

            <div class="detalle-bloque-header">
                <div>
                    <span class="detalle-bloque-etiqueta">GESTIÓN</span>
                    <h3>Contrato asociado</h3>
                </div>
            </div>


            <div class="contrato-vacio">

                <i class="fa-solid fa-file-contract"></i>

                <div>
                    <strong>Sin contrato asociado</strong>
                    <p>
                        Este inmueble todavía no tiene un contrato asociado.
                    </p>
                </div>

                <a href="#" class="detalle-link">
                    Asociar contrato
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </div>


        <!-- RECLAMOS -->
        <div class="detalle-bloque">

            <div class="detalle-bloque-header">
                <div>
                    <span class="detalle-bloque-etiqueta">SERVICIOS</span>
                    <h3>Reclamos recientes</h3>
                </div>
            </div>


            <div class="tabla-reclamos">

                <table>

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($reclamos->num_rows > 0) { ?>

                            <?php while ($reclamo = $reclamos->fetch_assoc()) { ?>

                                <tr>

                                    <td>
                                        <?= date("d/m/Y", strtotime($reclamo["FechaCreacion"])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($reclamo["Categoria"], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($reclamo["Descripcion"], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>

                                        <span class="reclamo-estado <?= htmlspecialchars(strtolower(str_replace(" ", "-", $reclamo["Estado"])), ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($reclamo["Estado"], ENT_QUOTES, 'UTF-8') ?>
                                        </span>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                        <tr>

                        <td colspan="4" style="text-align: center;">
                            Este inmueble no tiene reclamos registrados.
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

                    </table>

                </div>

            </div>


        <!-- VOLVER -->
        <div class="detalle-volver">

            <a href="inmuebles.php" class="btn btn-volver">
                <i class="fa-solid fa-arrow-left"></i>
                Volver a inmuebles
            </a>

        </div>

    </div>


    <!-- ==================================================
     MODAL MODIFICAR INMUEBLE
     ================================================== -->

    <div class="modal-fondo" id="modal-modificar-inmueble">

        <div class="modal-inmueble">

            <!-- ENCABEZADO -->
            <div class="modal-header">

                <h2>
                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                    Modificar inmueble
                </h2>

                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-modificar">
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>


            <!-- FORMULARIO -->
            <form
                action="inmueble_modificar.php?id=<?= (int) $inmueble['IdInmueble'] ?>"
                method="POST"
                enctype="multipart/form-data"
                class="formulario-inmueble">

                <!-- IMAGEN -->
            <div class="modal-columna-imagen">

                <label for="imagen-modificar" class="zona-subir-imagen">
                    <i class="fa-solid fa-cloud-arrow-up"></i>

                    <span>Subir nueva imagen</span>

                    <small>
                         Hacé clic para seleccionar una nueva imagen
                    </small>

                </label>

                <input
                    type="file"
                    id="imagen-modificar"
                    name="imagen"
                    accept="image/*">

            </div>


            <!-- DATOS -->
            <div class="modal-columna-datos">

                <div class="campo-inmueble">

                    <label for="direccion-modificar">
                        Dirección
                    </label>

                    <input
                        type="text"
                        id="direccion-modificar"
                        name="direccion"
                        value="value="<?= htmlspecialchars($inmueble['Direccion'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>


                <div class="campo-inmueble">

                    <label for="tipo-modificar">
                        Tipo
                    </label>

                    <select
                        id="tipo-modificar"
                        name="tipo"
                        required
                    >

                        <option value="Casa"
                            <?= $inmueble['Tipo'] == "Casa" ? "selected" : "" ?>>
                            Casa
                        </option>

                        <option value="Departamento"
                            <?= $inmueble['Tipo'] == "Departamento" ? "selected" : "" ?>>
                            Departamento
                        </option>

                        <option value="Duplex"
                            <?= $inmueble['Tipo'] == "Duplex" ? "selected" : "" ?>>
                            Duplex
                        </option>

                        <option value="Monoambiente"
                            <?= $inmueble['Tipo'] == "Monoambiente" ? "selected" : "" ?>>
                            Monoambiente
                        </option>

                        <option value="Local comercial"
                            <?= $inmueble['Tipo'] == "Local comercial" ? "selected" : "" ?>>
                            Local comercial
                        </option>

                    </select>

                </div>


                <div class="campo-inmueble">

                    <label for="estado-modificar">
                        Estado
                    </label>

                    <select
                        id="estado-modificar"
                        name="estado"
                        required
                    >

                        <option value="Disponible"
                            <?= $inmueble['Estado'] == "Disponible" ? "selected" : "" ?>>
                            Disponible
                        </option>

                        <option value="Alquilado"
                            <?= $inmueble['Estado'] == "Alquilado" ? "selected" : "" ?>>
                            Alquilado
                        </option>

                        <option value="Suspendido"
                            <?= $inmueble['Estado'] == "Suspendido" ? "selected" : "" ?>>
                            Suspendido
                        </option>

                    </select>

                </div>


                <div class="campo-inmueble">

                    <label for="descripcion-modificar">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion-modificar"
                        name="descripcion"
                        placeholder="descripción del inmueble"
                    ><?= htmlspecialchars($inmueble['Descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>

                </div>

            </div>


            <!-- BOTÓN -->
            <div class="modal-footer">

                <button
                    type="submit"
                    class="btn modal-guardar"
                >
                    <i class="fa-solid fa-check"></i>
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>
<script>

const botonModificar = document.getElementById("btn-modificar-inmueble");
const modalModificar = document.getElementById("modal-modificar-inmueble");
const botonCerrarModificar = document.getElementById("cerrar-modal-modificar");


// ABRIR MODAL
botonModificar.addEventListener("click", function () {

    modalModificar.classList.add("modal-visible");

});


// CERRAR CON LA X
botonCerrarModificar.addEventListener("click", function () {

    modalModificar.classList.remove("modal-visible");

});


// CERRAR HACIENDO CLIC FUERA
modalModificar.addEventListener("click", function (e) {

    if (e.target === modalModificar) {

        modalModificar.classList.remove("modal-visible");

    }

});

</script>
</body>
</html>