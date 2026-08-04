<?php 
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM carreras WHERE id_carrera = $id");
}

if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];

    $conn->query("INSERT INTO carreras (nombre, duracion) 
                  VALUES ('$nombre', '$duracion')");
}

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];

    $conn->query("UPDATE carreras 
                  SET nombre='$nombre', duracion='$duracion'
                  WHERE id_carrera=$id");
}

$editando = false;

if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM carreras WHERE id_carrera = $id");
    $fila = $resultado->fetch_assoc();

    $editando = true;
}
?>

<h1>Carreras</h1>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editando ? $fila['id_carrera'] : ''; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" required
        value="<?php echo $editando ? $fila['nombre'] : ''; ?>">

    <label>Duración:</label>
    <input type="text" name="duracion" required
        value="<?php echo $editando ? $fila['duracion'] : ''; ?>">

    <?php if ($editando) { ?>
        <button type="submit" name="editar">Actualizar</button>
        <a href="menu.php?pagina=carreras"><button type="button">Limpiar</button></a>
    <?php } else { ?>
        <button type="submit" name="agregar">Agregar</button>
    <?php } ?>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Duración</th>
    <th>Acciones</th>
</tr>

<?php
$sql = "SELECT * FROM carreras ORDER BY id_carrera";
$resultado = $conn->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "
        <tr>
            <td>".$fila['id_carrera']."</td>
            <td>".$fila['nombre']."</td>
            <td>".$fila['duracion']."</td>
            <td>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='carreras'>
                    <input type='hidden' name='editar' value='".$fila['id_carrera']."'>
                    <button type='submit'>Editar</button>
                </form>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='carreras'>
                    <input type='hidden' name='eliminar' value='".$fila['id_carrera']."'>
                    <button type='submit'>Eliminar</button>
                </form>

            </td>
        </tr>
        ";
}
?>
</table>