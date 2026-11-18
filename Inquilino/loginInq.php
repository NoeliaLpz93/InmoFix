<?php
session_start();
require_once "../Admin/conection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $clave = $_POST["clave"] ?? "";

    // Buscar el usuario en la base de datos
    $sql = "SELECT IdUsuario, Nombre, Apellido, Usuario,
                   Password, Rol, idInquilino
            FROM usuarios
            WHERE Usuario = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $datosUsuario = $resultado->fetch_assoc();

    if ($datosUsuario) {

        $passwordGuardada = $datosUsuario["Password"];

        // Comprobar contraseña cifrada o contraseña antigua
        $passwordCorrecta = password_verify($clave, $passwordGuardada);

        // Compatibilidad con contraseñas guardadas en texto plano
        if (!$passwordCorrecta && hash_equals((string)$passwordGuardada, $clave)) {

            $passwordCorrecta = true;

            // Actualizar automáticamente a un hash seguro
            $nuevoHash = password_hash($clave, PASSWORD_DEFAULT);

            $sqlActualizar = "UPDATE usuarios
                              SET Password = ?
                              WHERE IdUsuario = ?";

            $stmtActualizar = $conn->prepare($sqlActualizar);
            $stmtActualizar->bind_param(
                "si",
                $nuevoHash,
                $datosUsuario["IdUsuario"]
            );
            $stmtActualizar->execute();
            $stmtActualizar->close();
        }

        // Verificar contraseña, rol y vinculación con inquilinos
        if (
            $passwordCorrecta &&
            $datosUsuario["Rol"] === "Inquilino" &&
            !empty($datosUsuario["idInquilino"])
        ) {

            session_regenerate_id(true);

            $_SESSION["idUsuario"] = $datosUsuario["IdUsuario"];
            $_SESSION["idInquilino"] = $datosUsuario["idInquilino"];
            $_SESSION["nombre"] = $datosUsuario["Nombre"];
            $_SESSION["apellido"] = $datosUsuario["Apellido"];
            $_SESSION["rol"] = $datosUsuario["Rol"];

            header("Location: panelInq.php");
            exit();

        } else {
            $error = "Usuario o contraseña incorrecto";
        }

    } else {
        $error = "Usuario o contraseña incorrecto";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Inquilino</title>

    <!-- Fuente Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/estilo.css?v=2">

</head>

<body>

<div class="login-contenedor">

    <!-- Botón para volver al Index -->
    <a class="login-flecha" href="../index.php">
        ←
    </a>

    <!-- Logo y eslogan -->
    <div class="login-banner">

        <img src="../img/inmofix-02.png" alt="logo">

        <h2>
            Gestión inmobiliaria inteligente
        </h2>

    </div>

    <!-- CONTENEDOR VISUAL -->
    <div class="login-tarjeta">

        <div class="login-formulario">

            <h3>
                Acceso Inquilino
            </h3>

            <?php if (!empty($error)): ?>

                <p class="login-error">
                    <?= $error ?>
                </p>

            <?php endif; ?>

            <form method="POST">

                <label>
                    Usuario
                </label>

                <input
                    type="text"
                    name="usuario"
                    placeholder="Ingrese su usuario"
                    required>

                <label>
                    Contraseña
                </label>

                <input
                    type="password"
                    name="clave"
                    placeholder="Ingrese su contraseña"
                    required>

                <a class="login-olvide" href="recuperar.php">
                    Olvidé mi contraseña
                </a>

                <button class="login-btn" type="submit">
                    Ingresar
                </button>

            </form>

        </div>

    </div>

</div>

</body>

</html>
