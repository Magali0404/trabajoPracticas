<?php
session_start();

require_once("Conexion.php");

if (isset($_SESSION['login'])) {
    header("Location: menu.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];

    $sql = "SELECT * FROM usuarios WHERE correo=? AND contrasenia=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $contrasenia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['login'] = true;
        $_SESSION['correo'] = $correo;

        header("Location: menu.php");
        exit();
    } else {
        $error = "Correo o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-body">

<div class="login-box">
    <h2>Sistema académico</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Correo:
            <input type="email" name="correo" required>
        </label>
        <label>Contraseña:
            <input type="password" name="contrasenia" required>
        </label>
        <button type="submit">Ingresar</button>
    </form>
</div>

</body>
</html>