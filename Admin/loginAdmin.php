
<?php
include ("conection.php");

// Este if se pregunta si el usuario envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Si el usuario y contraseña son correctos se dirige al panel de Administrador
    if ($usuario === "admin" && $clave === "admin123") {
        header("Location: panelAdmin.php");
        exit();

    // Si no son correctos da error
    } else {
        $error = "Usuario o contraseña incorrecto";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>

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
                Acceso Administrador
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