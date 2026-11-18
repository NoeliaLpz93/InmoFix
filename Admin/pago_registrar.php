<?php

require "conection.php";

// VERIFICAR ENVÍO DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECIBIR DATOS
    $idPago = (int) $_POST["IdPago"];
    $fechaPago = $_POST["FechaPago"];

    // VALIDAR DATOS
    if ($idPago <= 0 || empty($fechaPago)) {
        die("Error: faltan datos para registrar el pago.");
    }

    // ACTUALIZAR PAGO
    $sql = "UPDATE pagos
            SET Estado = 'Pagado',
                FechaPago = ?
            WHERE IdPago = ?
            AND Estado != 'Pagado'";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $fechaPago, $idPago);

    if ($stmt->execute()) {

        header("Location: pagos.php?ok=registrado");
        exit();

    } else {

        echo "Error al registrar el pago: " . $stmt->error;

    }

    $stmt->close();

} else {

    header("Location: pagos.php");
    exit();

}

?>