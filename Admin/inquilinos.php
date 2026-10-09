<?php
    require "conection.php";
    
    // BUSCADOR
    $busqueda = $_GET["q"] ?? ""; // Obtiene el texto ingresado en el buscador.

    $sql = "SELECT * FROM inquilinos
            WHERE nombre LIKE '%$busqueda%'
            OR apellido LIKE '%$busqueda%'
            OR DNI LIKE '%$busqueda%'";

    $resultado = $conn->query($sql);  // Ejecuta la consulta

?>

<!DOCTYPE html>
    <html lang="es">

    <head>

        <meta charset="UTF-8">

        <title>Inquilinos</title>

        <!-- La letra -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" href="../css/estilo.css?v=1">

    </head>


    <body>

        <?php include "nav.php"; ?> <!--header y navegacion-->

        <div class="contenedor">

            <h2>Inquilinos</h2>


            <!-- BOTÓN AGREGAR + BUSCADOR -->

            <div class="acciones-superior">


                <!-- BOTÓN AGREGAR INQUILINO -->

                <button
                    type="button"
                    class="btn"
                    id="btn-agregar-inquilino"
                    >
                    <i class="fa-solid fa-plus"></i>
                    Agregar Inquilino
                </button>


                <!-- BUSCADOR -->

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



            <!-- TABLA DE INQUILINOS -->

            <div class="tabla-contenedor">

                <table class="tabla-inquilinos">

                    <thead>

                        <tr>

                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                <tbody>

                <?php while ($fila = $resultado->fetch_assoc()) { ?>

                    <!-- Leer resultados de la base de datos -->

                    <tr>
                        <td>
                            <?= htmlspecialchars($fila["nombre"]) ?> <!-- convierte caracteres especiales en entidades HTML antes de insertar los datos en los atributos HTML -->
                        </td>

                        <td>
                            <?= htmlspecialchars($fila["apellido"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($fila["dni"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($fila["telefono"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($fila["email"]) ?>
                        </td>

                        <td>
                            <div class="acciones-tabla">

                                <!-- ==================================================
                                     BOTÓN EDITAR
                                     Los datos del inquilino quedan guardados
                                     en los atributos data- para que JavaScript
                                     pueda utilizarlos después.
                                     ================================================== -->

                                <button
                                    type="button"
                                    class="btn btn-editar-inquilino"
                                    data-id="<?= $fila['id'] ?>"
                                    data-apellido="<?= htmlspecialchars($fila['apellido'], ENT_QUOTES) ?>"
                                    data-nombre="<?= htmlspecialchars($fila['nombre'], ENT_QUOTES) ?>"
                                    data-dni="<?= htmlspecialchars($fila['dni'], ENT_QUOTES) ?>"
                                    data-telefono="<?= htmlspecialchars($fila['telefono'], ENT_QUOTES) ?>"
                                    data-email="<?= htmlspecialchars($fila['email'], ENT_QUOTES) ?>"
                                    >
                                    <i class="fa-solid fa-pen"></i>
                                    Editar
                                </button>

                                <!-- ==================================================
                                     BOTÓN ELIMINAR
                                     ================================================== -->
                                <a
                                    href="inquilino_eliminar.php?id=<?= $fila['id'] ?>"
                                    class="btn btn-rojo"
                                    onclick="return confirm('¿Seguro que deseas eliminar este inquilino?');"
                                    >
                                    <i class="fa-solid fa-trash"></i>
                                    Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php } ?>


                </tbody>

                    </table>

                        </div>

                            <!-- MODAL AGREGAR INQUILINO -->
                            <!-- El modal comienza oculto mediante CSS,
                            JavaScript agregará la clase modal-visible
                            cuando el usuario presione Agregar Inquilino -->

                            <div class="modal-fondo" id="modal-agregar-inquilino">

                            <div class="modal-inmueble">

                            <!-- ENCABEZADO -->

                            <div class="modal-header">

                            <h2>

                                <span class="modal-icono-titulo">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>

                                Agregar inquilino

                            </h2>

                            <button
                                    type="button"
                                    class="modal-cerrar"
                                    id="cerrar-modal-inquilino"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>



                            <!-- FORMULARIO -->

                            <form
                                action="inquilino_agregar.php"
                                method="POST"
                                class="formulario-inquilino"
                            >

                            <div class="campos-inquilino">

                            <!-- APELLIDO -->

                            <div class="campo-inquilino">

                            <label for="apellido">
                                Apellido
                            </label>

                            <input
                                type="text"
                                id="apellido"
                                name="apellido"
                                placeholder="apellido"
                                required
                            >

                        </div>



                        <!-- NOMBRE -->

                        <div class="campo-inquilino">

                            <label for="nombre">
                                Nombre
                            </label>

                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                placeholder="nombre"
                                required
                            >

                        </div>



                        <!-- DNI -->

                        <div class="campo-inquilino">

                            <label for="dni">
                                DNI
                            </label>

                            <input
                                type="text"
                                id="dni"
                                name="dni"
                                placeholder="DNI"
                                required
                            >

                        </div>



                        <!-- TELÉFONO -->

                        <div class="campo-inquilino">

                            <label for="telefono">
                                Teléfono
                            </label>

                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                placeholder="teléfono"
                            >

                        </div>



                        <!-- EMAIL -->

                        <div class="campo-inquilino campo-inquilino-completo">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="correo electrónico"
                            >

                        </div>


                    </div>



                    <!-- BOTÓN GUARDAR -->

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn modal-guardar"
                        >

                            <i class="fa-solid fa-check"></i>
                            Guardar inquilino

                        </button>

                    </div>


                </form>


            </div>


        </div>



    <!-- ==================================================
         MODAL MODIFICAR INQUILINO
         ================================================== -->

    <div class="modal-fondo" id="modal-modificar-inquilino">


        <div class="modal-inmueble">


            <!-- ENCABEZADO -->

            <div class="modal-header">


                <h2>

                    <span class="modal-icono-titulo">
                        <i class="fa-solid fa-pen"></i>
                    </span>

                    Modificar inquilino

                </h2>


                <button
                    type="button"
                    class="modal-cerrar"
                    id="cerrar-modal-modificar-inquilino"
                >

                    <i class="fa-solid fa-xmark"></i>

                </button>


            </div>



            <!-- FORMULARIO DE MODIFICACIÓN -->

            <form
                method="POST"
                class="formulario-inquilino"
                id="form-modificar-inquilino"
            >


                <div class="campos-inquilino">


                    <!-- APELLIDO -->

                    <div class="campo-inquilino">

                        <label for="apellido-modificar">
                            Apellido
                        </label>

                        <input
                            type="text"
                            id="apellido-modificar"
                            name="apellido"
                            required
                        >

                    </div>



                    <!-- NOMBRE -->

                    <div class="campo-inquilino">

                        <label for="nombre-modificar">
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="nombre-modificar"
                            name="nombre"
                            required
                        >

                    </div>



                    <!-- DNI -->

                    <div class="campo-inquilino">

                        <label for="dni-modificar">
                            DNI
                        </label>

                        <input
                            type="text"
                            id="dni-modificar"
                            name="dni"
                            required
                        >

                    </div>



                    <!-- TELÉFONO -->

                    <div class="campo-inquilino">

                        <label for="telefono-modificar">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            id="telefono-modificar"
                            name="telefono"
                        >

                    </div>



                    <!-- EMAIL -->

                    <div class="campo-inquilino campo-inquilino-completo">

                        <label for="email-modificar">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email-modificar"
                            name="email"
                        >

                    </div>


                </div>



                <!-- BOTÓN GUARDAR CAMBIOS -->

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



</div>



<!-- ==================================================
     JAVASCRIPT
     Está DESPUÉS de los dos modales para que
     JavaScript pueda encontrarlos correctamente.
     ================================================== -->

<script>


    // ==================================================
    // MODAL AGREGAR INQUILINO
    // ==================================================


    // Buscamos el botón "Agregar Inquilino"
    const botonAgregarInquilino =
        document.getElementById("btn-agregar-inquilino");   // Busca en el HTML el elemento que tiene este ID
                                                            // y lo guarda en una constante para poder manipularlo.

    // Buscamos el modal de agregar
    const modalAgregarInquilino =
        document.getElementById("modal-agregar-inquilino");


    // Buscamos el botón X
    const botonCerrarInquilino =
        document.getElementById("cerrar-modal-inquilino");



    // ABRIR MODAL AGREGAR

    botonAgregarInquilino.addEventListener("click", function () {

        modalAgregarInquilino.classList.add("modal-visible");

    });



    // CERRAR MODAL AGREGAR CON LA X

    botonCerrarInquilino.addEventListener("click", function () {

        modalAgregarInquilino.classList.remove("modal-visible");

    });



    // CERRAR MODAL AGREGAR HACIENDO CLIC FUERA

    modalAgregarInquilino.addEventListener("click", function (e) {

        if (e.target === modalAgregarInquilino) {

            modalAgregarInquilino.classList.remove("modal-visible");

        }

    });



    // ==================================================
    // MODAL MODIFICAR INQUILINO
    // ==================================================


    // Buscamos todos los botones "Editar"
    const botonesEditarInquilino =
        document.querySelectorAll(".btn-editar-inquilino");


    // Buscamos el modal de modificar
    const modalModificarInquilino =
        document.getElementById("modal-modificar-inquilino");


    // Buscamos el botón X para cerrar
    const botonCerrarModificarInquilino =
        document.getElementById("cerrar-modal-modificar-inquilino");


    // Buscamos el formulario de modificación
    const formModificarInquilino =
        document.getElementById("form-modificar-inquilino");



    // Buscamos los campos del formulario

    const apellidoModificar =
        document.getElementById("apellido-modificar");

    const nombreModificar =
        document.getElementById("nombre-modificar");

    const dniModificar =
        document.getElementById("dni-modificar");

    const telefonoModificar =
        document.getElementById("telefono-modificar");

    const emailModificar =
        document.getElementById("email-modificar");



    // ==================================================
    // CUANDO SE HACE CLIC EN "EDITAR"
    // ==================================================

    botonesEditarInquilino.forEach(function (boton) {


        boton.addEventListener("click", function () {


            // Tomamos el ID del inquilino
            const id = boton.dataset.id;

            // Recuperamos los datos almacenados previamente
            // en los atributos data-* del botón "Editar".
            // Tomamos los datos del inquilino
            const apellido = boton.dataset.apellido;
            const nombre = boton.dataset.nombre;
            const dni = boton.dataset.dni;
            const telefono = boton.dataset.telefono;
            const email = boton.dataset.email;



            // Colocamos los datos dentro del formulario

            apellidoModificar.value = apellido;

            nombreModificar.value = nombre;

            dniModificar.value = dni;

            telefonoModificar.value = telefono;

            emailModificar.value = email;



            // Le indicamos al formulario qué inquilino modificar
            //
            // El archivo inquilino_modificar.php recibe
            // el ID mediante $_GET["id"]

            formModificarInquilino.action =
                "inquilino_modificar.php?id=" + id;



            // Finalmente abrimos el modal

            modalModificarInquilino.classList.add("modal-visible");


        });


    });



    // ==================================================
    // CERRAR MODAL MODIFICAR CON LA X
    // ==================================================

    botonCerrarModificarInquilino.addEventListener("click", function () {

        modalModificarInquilino.classList.remove("modal-visible");

    });



    // ==================================================
    // CERRAR MODAL MODIFICAR HACIENDO CLIC FUERA
    // ==================================================

    modalModificarInquilino.addEventListener("click", function (e) {

        if (e.target === modalModificarInquilino) {

            modalModificarInquilino.classList.remove("modal-visible");

        }

    });


</script>


</body>
</html>