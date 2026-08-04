<?php 
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM materias WHERE id_materia = $id");
}

if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];
    $id_carrera = $_POST['id_carrera'];
    $id_profesor = $_POST['id_profesor'];
    $nota_aprobacion = $_POST['nota_aprobacion'];

    $conn->query("INSERT INTO materias (nombre, curso, id_carrera, id_profesor, nota_aprobacion) 
                  VALUES ('$nombre', '$curso', '$id_carrera', '$id_profesor', '$nota_aprobacion')");
}

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];
    $id_carrera = $_POST['id_carrera'];
    $id_profesor = $_POST['id_profesor'];
    $nota_aprobacion = $_POST['nota_aprobacion'];

    $conn->query("UPDATE materias 
                  SET nombre='$nombre', curso='$curso', id_carrera='$id_carrera',
                      id_profesor='$id_profesor', nota_aprobacion='$nota_aprobacion'
                  WHERE id_materia=$id");
}

$editando = false;

if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM materias WHERE id_materia = $id");
    $fila = $resultado->fetch_assoc();
    $editando = true;
}

$carreras = $conn->query("SELECT id_carrera, nombre FROM carreras");
$profesores = $conn->query("SELECT id_profesor, nombre FROM profesores");
?>

<h1>Materias</h1>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editando ? $fila['id_materia'] : ''; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" required
        value="<?php echo $editando ? $fila['nombre'] : ''; ?>">

    <label>Curso:</label>
    <input type="text" name="curso" required
        value="<?php echo $editando ? $fila['curso'] : ''; ?>">

    <label>Carrera:</label>
    <select name="id_carrera" required>
        <?php while($c = $carreras->fetch_assoc()) { ?>
            <option value="<?php echo $c['id_carrera']; ?>"
                <?php echo ($editando && $fila['id_carrera'] == $c['id_carrera']) ? 'selected' : ''; ?>>
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Profesor:</label>
    <select name="id_profesor" required>
        <?php while($p = $profesores->fetch_assoc()) { ?>
            <option value="<?php echo $p['id_profesor']; ?>"
                <?php echo ($editando && $fila['id_profesor'] == $p['id_profesor']) ? 'selected' : ''; ?>>
                <?php echo $p['nombre']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Nota de aprobación:</label>
    <input type="number" name="nota_aprobacion" required
        value="<?php echo $editando ? $fila['nota_aprobacion'] : ''; ?>">

    <?php if ($editando) { ?>
        <button type="submit" name="editar">Actualizar</button>
        <a href="menu.php?pagina=materias"><button type="button">Limpiar</button></a>
    <?php } else { ?>
        <button type="submit" name="agregar">Agregar</button>
    <?php } ?>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Curso</th>
    <th>Carrera</th>
    <th>Profesor</th>
    <th>Nota aprobación</th>
    <th>Acciones</th>
</tr>

<?php
$sql = "SELECT m.*, c.nombre AS nombre_carrera, p.nombre AS nombre_profesor
        FROM materias m
        JOIN carreras c ON m.id_carrera = c.id_carrera
        JOIN profesores p ON m.id_profesor = p.id_profesor
        ORDER BY m.id_materia";
$resultado = $conn->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "
        <tr>
            <td>".$fila['id_materia']."</td>
            <td>".$fila['nombre']."</td>
            <td>".$fila['curso']."</td>
            <td>".$fila['nombre_carrera']."</td>
            <td>".$fila['nombre_profesor']."</td>
            <td>".$fila['nota_aprobacion']."</td>
            <td>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='materias'>
                    <input type='hidden' name='editar' value='".$fila['id_materia']."'>
                    <button type='submit'>Editar</button>
                </form>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='materias'>
                    <input type='hidden' name='eliminar' value='".$fila['id_materia']."'>
                    <button type='submit'>Eliminar</button>
                </form>

            </td>
        </tr>
        ";
}
?>
</table>