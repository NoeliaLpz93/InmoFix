<?php
    require "conection.php";

    $id = $_GET["id"]; //obtiene el ID del inmueble que viene desde la URL

    $sql = "SELECT * FROM inmuebles WHERE IdInmueble = $id"; //La consulta SQL busca en la tabla inmuebles el registro que tenga ese ID
    $inmueble = $conn->query($sql)->fetch_assoc(); //convierte el resultado de la consulta en un array asociativo para poder acceder a los datos usando los nombres de las columnas
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
                    src="../uploads/<?= $inmueble['imagen'] ?>"
                    alt="Imagen del inmueble"
                    class="detalle-foto"
                >

            </div>


        <!-- INFORMACIÓN -->
            <div class="detalle-info-card">

                <div class="detalle-info-header">
                    <h3>Información general</h3>

                    <span class="estado-inmueble">
                        <?= $inmueble["Estado"] ?>
                    </span>
                </div>


                <div class="detalle-dato">
                    <span class="detalle-label">Dirección</span>
                    <p><?= $inmueble["Direccion"] ?></p>
                </div>


                <div class="detalle-dato">
                    <span class="detalle-label">Tipo de inmueble</span>
                    <p><?= $inmueble["Tipo"] ?></p>
                </div>


                <div class="detalle-dato detalle-descripcion">
                    <span class="detalle-label">Descripción</span>
                    <p>
                        <?= !empty($inmueble["Descripcion"])
                            ? $inmueble["Descripcion"]
                            : "Este inmueble no tiene una descripción cargada." ?>
                    </p>
                </div>


                <!-- ACCIONES -->
                <div class="detalle-acciones">

                    <a
                        href="inmueble_modificar.php?id=<?= $inmueble['IdInmueble'] ?>"
                        class="btn">
                        <i class="fa-solid fa-pen"></i>
                        Modificar
                    </a>

                    <a
                        href="inmueble_eliminar.php?id=<?= $inmueble['IdInmueble'] ?>"
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

                        <tr>
                            <td>12/09/2026</td>
                            <td>Plomería</td>
                            <td>Pérdida de agua en cocina</td>
                            <td>
                                <span class="reclamo-estado pendiente">
                                    Pendiente
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>03/09/2026</td>
                            <td>Electricidad</td>
                            <td>Falla en una de las habitaciones</td>
                            <td>
                                <span class="reclamo-estado resuelto">
                                    Resuelto
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>28/08/2026</td>
                            <td>Mantenimiento</td>
                            <td>Revisión general</td>
                            <td>
                                <span class="reclamo-estado proceso">
                                    En proceso
                                </span>
                            </td>
                        </tr>

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

</body>
</html>