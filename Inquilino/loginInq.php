<?php
// No mostrar error al cargar la página
$error = "";

// Solo procesar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") 

    $usuario = $_POST["usuario"];   // número de contrato
    $clave   = $_POST["clave"];     // DNI

    // Datos de ejemplo (luego se reemplazan por BD)
    $contrato_correcto = "A12345";
    $dni_correcto      = "30123456";

    // Validación
    if ($usuario === $contrato_correcto && $clave === $dni_correcto) {
        header("Location: panel.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Inquilino</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>
    <div class="contenedor">
          
        <div class="banner">
            <img src="../img/inmofix-02.png" alt="logo">
            <h2>Gestión inmobiliaria inteligente</h2>
        </div>

        <a class="flecha" href="../index.php">←</a>

        <h2>Acceso Inquilino</h2>

        <!-- SOLO aparece si el usuario intentó ingresar -->
        <?php if ($error !== ""): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <a class="olvide" href="recuperar.php">Olvidé mi contraseña</a>

    </div>
</body>
</html>