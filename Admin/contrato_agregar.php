<?php

require "verificar_admin.php";
require "conection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECIBIR LOS DATOS DEL FORMULARIO
    $idInquilino = (int) $_POST["IdInquilino"];
    $idInmueble = (int) $_POST["IdInmueble"];
    $fechaInicio = $_POST["FechaInicio"];
    $fechaFin = $_POST["FechaFin"];
    $valorInicial = (float) $_POST["ValorInicial"];
    $tipoAjuste = $_POST["TipoAjuste"];
    $porcentajeAjuste = (float) ($_POST["PorcentajeAjuste"] ?? 0);

    // SI EL AJUSTE NO ES POR PORCENTAJE, GUARDAR 0
    if ($tipoAjuste != "Porcentaje") {
        $porcentajeAjuste = 0;
    }

    // VALIDAR FECHAS
    if (
        empty($fechaInicio) ||
        empty($fechaFin) ||
        $fechaFin <= $fechaInicio
    ) {
        die("Error: la fecha de finalización debe ser posterior a la fecha de inicio.");
    }

    // VALIDAR VALOR INICIAL
    if ($valorInicial <= 0) {
        die("Error: el valor inicial debe ser mayor a cero.");
    }   

    // VALIDAR TIPO DE AJUSTE
    $tiposPermitidos = [
        "Trimestral",
        "Semestral",
        "Porcentaje",
        "Cuatrimestral"
    ];

    if (!in_array($tipoAjuste, $tiposPermitidos, true)) {
        die("Error: el tipo de ajuste no es válido.");
    }

    // VALIDAR PORCENTAJE
    if ($tipoAjuste == "Porcentaje" &&
        ($porcentajeAjuste < 0 || $porcentajeAjuste > 100)) {
        die("Error: el porcentaje debe estar entre 0 y 100.");
    }

    // VERIFICAR QUE EL INQUILINO EXISTA
    $sqlInquilino = "SELECT id FROM inquilinos WHERE id = ?";
    $stmtInquilino = $conn->prepare($sqlInquilino);

    if (!$stmtInquilino) {
        die("Error al verificar el inquilino.");
    }

    $stmtInquilino->bind_param("i", $idInquilino);
    $stmtInquilino->execute();
    $stmtInquilino->store_result();

    if ($stmtInquilino->num_rows === 0) {
        $stmtInquilino->close();
        die("Error: el inquilino seleccionado no existe.");
    }

    $stmtInquilino->close();

    // INICIAR TRANSACCIÓN
    $conn->begin_transaction();

    try {

        // VERIFICAR Y ACTUALIZAR EL ESTADO DEL INMUEBLE
        $sqlInmueble = "UPDATE inmuebles
                        SET Estado = 'Alquilado'
                        WHERE IdInmueble = ?
                        AND Estado = 'Disponible'";

        $stmtInmueble = $conn->prepare($sqlInmueble);

        if (!$stmtInmueble) {
            throw new Exception("Error al preparar la actualización del inmueble.");
        }

        $stmtInmueble->bind_param("i", $idInmueble);
        $stmtInmueble->execute();

        if ($stmtInmueble->affected_rows != 1) {
            throw new Exception("El inmueble no está disponible.");
        }

        $stmtInmueble->close();

        // VERIFICAR SI YA EXISTE UN CONTRATO ACTIVO
        $sqlVerificar = "SELECT IdContrato
                         FROM contratos
                         WHERE IdInmueble = ?
                         AND Estado = 'Activo'
                         LIMIT 1";

        $stmtVerificar = $conn->prepare($sqlVerificar);

        if (!$stmtVerificar) {
            throw new Exception("Error al verificar los contratos existentes.");
        }

        $stmtVerificar->bind_param("i", $idInmueble);
        $stmtVerificar->execute();

        $stmtVerificar->bind_result($idContratoExistente);

        if ($stmtVerificar->fetch()) {
            $stmtVerificar->close();
            throw new Exception("El inmueble ya tiene un contrato activo.");
        }

        $stmtVerificar->close();

        // GUARDAR EL CONTRATO
        $sql = "INSERT INTO contratos
                (IdInquilino, IdInmueble, FechaInicio, FechaFin,
                 ValorInicial, TipoAjuste, PorcentajeAjuste, Estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Activo')";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar el contrato.");
        }

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

        $stmt->execute();
        $stmt->close();

        // CONFIRMAR TRANSACCIÓN
        $conn->commit();

        header("Location: contratos.php?ok=agregado");
        exit();

    } catch (Throwable $error) {

        $conn->rollback();

        echo "No se pudo guardar el contrato. Verificá que el inmueble esté disponible y no tenga otro contrato activo.";
    }
}
?>