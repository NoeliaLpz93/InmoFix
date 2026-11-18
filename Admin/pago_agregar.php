<?php

require "conection.php";

// VERIFICAR QUE EL FORMULARIO SE HAYA ENVIADO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECIBIR DATOS
    $idContrato = (int) $_POST["IdContrato"];
    $monto = (float) $_POST["Monto"];
    $fechaEmision = $_POST["FechaEmision"];
    $fechaVencimiento = $_POST["FechaVencimiento"];

    // VALIDAR DATOS
    if (
        $idContrato <= 0 ||
        $monto <= 0 ||
        empty($fechaEmision) ||
        empty($fechaVencimiento) ||
        $fechaEmision > $fechaVencimiento
    ) {
        die("Error: verificá los datos ingresados.");
    }

    // ESTADO INICIAL
    $estado = "Pendiente";

    // INSERTAR PAGO
    $sql = "INSERT INTO pagos
            (IdContrato, Estado, Monto, FechaEmision, FechaVencimiento, FechaPago)
            VALUES (?, ?, ?, ?, ?, NULL)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isdss",
        $idContrato,
        $estado,
        $monto,
        $fechaEmision,
        $fechaVencimiento
    );

    if ($stmt->execute()) {

        header("Location: pagos.php?ok=agregado");
        exit();

    } else {

        echo "Error al guardar el pago: " . $stmt->error;

    }

    $stmt->close();

} else {

    header("Location: pagos.php");
    exit();

}

?>