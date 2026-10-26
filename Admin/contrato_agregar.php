<?php

require "conection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECIBIR LOS DATOS DEL FORMULARIO
    $idInquilino = $_POST["IdInquilino"];
    $idInmueble = $_POST["IdInmueble"];
    $fechaInicio = $_POST["FechaInicio"];
    $fechaFin = $_POST["FechaFin"];
    $valorInicial = $_POST["ValorInicial"];
    $tipoAjuste = $_POST["TipoAjuste"];
    $porcentajeAjuste = $_POST["PorcentajeAjuste"];

    // SI EL AJUSTE NO ES POR PORCENTAJE, GUARDAR 0
    if ($tipoAjuste != "Porcentaje") {
        $porcentajeAjuste = 0;
    }

    // GUARDAR EL CONTRATO
    $sql = "INSERT INTO contratos
            (IdInquilino, IdInmueble, FechaInicio, FechaFin,
             ValorInicial, TipoAjuste, PorcentajeAjuste, Estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Activo')";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iissdsd",
        $idInquilino,
        $idInmueble,
        $fechaInicio,
        $fechaFin,
        $valorInicial,
        $tipoAjuste,
        $porcentajeAjuste
    );

    // ACTUALIZAR EL ESTADO DEL INMUEBLE
    $conn->begin_transaction();

    try {
        $stmt->execute();

        $sqlInmueble = "UPDATE inmuebles
                        SET Estado = 'Alquilado'
                        WHERE IdInmueble = ?
                        AND Estado = 'Disponible'";

        $stmtInmueble = $conn->prepare($sqlInmueble);
        $stmtInmueble->bind_param("i", $idInmueble);
        $stmtInmueble->execute();

        if ($stmtInmueble->affected_rows != 1) {
            throw new Exception("El inmueble ya no está disponible.");
        }

        $conn->commit();

        header("Location: contratos.php?ok=agregado");
        exit;

    } catch (Throwable $error) {
        $conn->rollback();

        echo "No se pudo guardar el contrato. Verificá que el inmueble siga disponible.";
    }
}
?>
