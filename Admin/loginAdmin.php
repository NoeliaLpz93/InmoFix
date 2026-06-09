<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST["usuario"];
    $clave   = $_POST["clave"];

    if ($usuario === "admin" && $clave === "admin123") {
        header("Location: panel.php");
        exit();
    } else {
        $error = "Credenciales inválidas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>
     <div class="contenedor">
        
        <div class="banner">
            <img src="../img/inmofix-02.png" alt="logo">
            <h2>Gestión inmobiliaria inteligente</h2>
        </div>
    <div class="contenedor">

        <a class="flecha" href="../index.php">←</a>

        <h2>Acceso Administrador</h2>

        <?php if (!empty($error)): ?>
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