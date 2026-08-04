<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <h2 class="logo">Sistema<br>académico</h2>

        <nav>
            <ul class="menu">
                <li><a href="menu.php?pagina=carreras">Carreras</a></li>
                <li><a href="menu.php?pagina=materias">Materias</a></li>
                <li><a href="menu.php?pagina=alumnos">Alumnos</a></li>
                <li><a href="menu.php?pagina=profesores">Profesores</a></li>
                <li><a href="menu.php?pagina=examenes">Exámenes</a></li>
                <li><a href="menu.php?pagina=trayectoria">Trayectoria</a></li>
            </ul>
        </nav>

        <div class="logout">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </aside>

    <main class="contenido">
        <?php
        if (isset($_GET['pagina'])) {

            $pagina = $_GET['pagina'];

            if ($pagina == 'carreras') {
                include("modulos/carreras.php");
            }

            if ($pagina == 'materias') {
                include("modulos/materias.php");
            }

            if ($pagina == 'alumnos') {
                include("modulos/alumnos.php");
            }

            if ($pagina == 'profesores') {
                include("modulos/profesores.php");
            }

            if ($pagina == 'examenes') {
                include("modulos/examenes.php");
            }

            if ($pagina == 'trayectoria') {
                include("modulos/trayectoria.php");
            }

        } else {
            echo "<h2>Bienvenido</h2>";
            echo "<p>Seleccioná una opción del menú.</p>";
        }
        ?>
    </main>

</div>
</body>
</html>