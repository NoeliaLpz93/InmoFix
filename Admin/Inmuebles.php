<?php
    require "conection.php";

    $busqueda = $_GET["q"] ?? ""; // Guarda el texto buscado. Si no hay búsqueda, queda vacío.

    $sql = "SELECT * FROM inmuebles 
        WHERE direccion LIKE '%$busqueda%'"; // Consulta los inmuebles cuya dirección contenga el texto buscado.

    $resultado = $conn->query($sql); // Ejecuta la consulta y guarda los resultados.
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Inmuebles</title>

    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">

</head>

    <body>

    <?php include "nav.php"; ?> <!--Inserta la barra de navegación-->

        <div class="contenedor">

            <h2>Inmuebles</h2>

            <!-- BOTONES SUPERIORES -->
            <div class="acciones-superior">
                <button type="button" class="btn" id="btn-agregar-inmueble">
                    <i class="fa-solid fa-plus"></i>
                    Agregar inmueble
                </button>

                <form method="GET" class="buscador">
                    <input type="text" name="q" placeholder="Buscar por dirección" value="<?= $busqueda ?>">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                </form>
            </div>
    <!-- TARJETAS -->
            <div class="grid-inmuebles">

            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <!-- Recorre todos los inmuebles obtenidos de la base de datos.-->

            <div class="card-inmueble">

                <img src="../uploads/<?= $fila['imagen'] ?>" class="foto-inmueble">

            <div class="info">
                <h3><?= $fila["Direccion"] ?></h3>
                <p><?= $fila["Tipo"] ?></p>
            </div>

            <div class="acciones-tarjeta">

            <a href="inmueble_detalles.php?id=<?= $fila['IdInmueble'] ?>" class="btn-detalles">
                <i class="fa-solid fa-circle-info"></i>
                Detalles
            </a>

            <a href="inmueble_eliminar.php?id=<?= $fila['IdInmueble'] ?>"
                class="btn btn-rojo"
                onclick="return confirm('¿Seguro que deseas eliminar este inmueble? Esta acción no se puede deshacer.');">
                <i class="fa-solid fa-trash"></i>
            Eliminar
            </a>

</div>

        </div>

    <?php } ?>

    </div>


<!-- ==================================================
     MODAL AGREGAR INMUEBLE
     ================================================== -->

<div class="modal-fondo" id="modal-agregar-inmueble">

    <div class="modal-inmueble">

        <!-- ENCABEZADO -->
        <div class="modal-header">

            <h2>
                <span class="modal-icono-titulo">
                    <i class="fa-solid fa-plus"></i>
                </span>

                Agregar inmueble
            </h2>

            <button
                type="button"
                class="modal-cerrar"
                id="cerrar-modal-inmueble"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <!-- FORMULARIO -->
        <form
            action="Inmueble_agregar.php"
            method="POST"
            enctype="multipart/form-data"
            class="formulario-inmueble"
        >

            <!-- ZONA DE IMAGEN -->
            <div class="modal-columna-imagen">

                <label for="imagen" class="zona-subir-imagen">

                    <i class="fa-solid fa-cloud-arrow-up"></i>

                    <span>Subir fotos</span>

                    <small>Hacé clic para seleccionar una imagen</small>

                </label>

                <input
                    type="file"
                    id="imagen"
                    name="imagen"
                    accept="image/*"
                    required
                >

            </div>


            <!-- ZONA DE DATOS -->
            <div class="modal-columna-datos">

                <div class="campo-inmueble">

                    <label for="direccion">
                        Dirección
                    </label>

                    <input
                        type="text"
                        id="direccion"
                        name="direccion"
                        placeholder="dirección"
                        required
                    >

                </div>


                <div class="campo-inmueble">

                    <label for="tipo">
                        Tipo
                    </label>

                    <select
                        id="tipo"
                        name="tipo"
                        required
                    >
                        <option value="">Seleccionar tipo</option>
                        <option value="Casa">Casa</option>
                        <option value="Departamento">Departamento</option>
                        <option value="Duplex">Duplex</option>
                        <option value="Monoambiente">Monoambiente</option>
                        <option value="Local comercial">Local comercial</option>
                    </select>

                </div>


                <div class="campo-inmueble">

                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        required
                    >
                        <option value="Disponible">Disponible</option>
                        <option value="Alquilado">Alquilado</option>
                        <option value="Suspendido">Suspendido</option>
                    </select>

                </div>


                <div class="campo-inmueble">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        placeholder="descripción del inmueble"
                    ></textarea>

                </div>

            </div>


            <!-- BOTÓN GUARDAR -->
            <div class="modal-footer">

                <button
                    type="submit"
                    class="btn modal-guardar"
                >
                    <i class="fa-solid fa-check"></i>
                    Guardar inmueble
                </button>

            </div>

        </form>

    </div>

</div>

<script>

    const botonAgregar = document.getElementById("btn-agregar-inmueble"); //busca el botón de Agregar inmueble.
    const modalAgregar = document.getElementById("modal-agregar-inmueble"); //busca el modal
    const botonCerrar = document.getElementById("cerrar-modal-inmueble");


    // ABRIR MODAL
    botonAgregar.addEventListener("click", function () {

        modalAgregar.classList.add("modal-visible"); //le agrega la clase

    });


    // CERRAR MODAL CON LA X
    botonCerrar.addEventListener("click", function () {

        modalAgregar.classList.remove("modal-visible");

    });


        // CERRAR MODAL AL HACER CLIC FUERA
    modalAgregar.addEventListener("click", function (e) {

        if (e.target === modalAgregar) {

            modalAgregar.classList.remove("modal-visible");

        }

    });

</script>
</body>
</html>