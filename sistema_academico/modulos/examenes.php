<?php 
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM examenes WHERE id_examen = $id");
}

if (isset($_POST['agregar'])) {
    $conn->query("INSERT INTO examenes (id_alumno, id_materia, fecha, nota) 
                  VALUES ('{$_POST['id_alumno']}', '{$_POST['id_materia']}', '{$_POST['fecha']}', '{$_POST['nota']}')");
}

if (isset($_POST['editar'])) {
    $conn->query("UPDATE examenes 
                  SET id_alumno='{$_POST['id_alumno']}', id_materia='{$_POST['id_materia']}',
                      fecha='{$_POST['fecha']}', nota='{$_POST['nota']}'
                  WHERE id_examen={$_POST['id']}");
}

$editando = false;
if (isset($_GET['editar'])) {
    $resultado = $conn->query("SELECT * FROM examenes WHERE id_examen = {$_GET['editar']}");
    $fila = $resultado->fetch_assoc();
    $editando = true;
}

$alumno_seleccionado = null;
if (isset($_POST['seleccionar_alumno'])) {
    $alumno_seleccionado = $_POST['id_alumno'];
} elseif ($editando) {
    $alumno_seleccionado = $fila['id_alumno'];
}

$alumnos = $conn->query("SELECT id_alumno, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM alumnos");

$materias = null;
if ($alumno_seleccionado) {
    $materias = $conn->query("SELECT materias.id_materia, materias.nombre
                              FROM materias
                              INNER JOIN alumnos ON materias.id_carrera = alumnos.id_carrera
                              WHERE alumnos.id_alumno = $alumno_seleccionado");
}
?>

<h1>Exámenes</h1>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editando ? $fila['id_examen'] : ''; ?>">

    <label>Alumno:</label>
    <select name="id_alumno">
        <option value="">-- Seleccione un alumno --</option>
        <?php while($a = $alumnos->fetch_assoc()) { ?>
            <option value="<?php echo $a['id_alumno']; ?>"
                <?php echo ($alumno_seleccionado == $a['id_alumno']) ? 'selected' : ''; ?>>
                <?php echo $a['nombre_completo']; ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit" name="seleccionar_alumno">Seleccionar</button>

    <?php if ($alumno_seleccionado) { ?>

        <label>Materia:</label>
        <select name="id_materia" required>
            <option value="">-- Seleccione una materia --</option>
            <?php while($m = $materias->fetch_assoc()) { ?>
                <option value="<?php echo $m['id_materia']; ?>"
                    <?php echo ($editando && $fila['id_materia'] == $m['id_materia']) ? 'selected' : ''; ?>>
                    <?php echo $m['nombre']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Fecha:</label>
        <input type="date" name="fecha" required value="<?php echo $editando ? $fila['fecha'] : ''; ?>">

        <label>Nota:</label>
        <input type="number" name="nota" step="0.01" min="0" max="10" required value="<?php echo $editando ? $fila['nota'] : ''; ?>">

        <?php if ($editando) { ?>
            <button type="submit" name="editar">Actualizar</button>
            <a href="menu.php?pagina=examenes"><button type="button">Limpiar</button></a>
        <?php } else { ?>
            <button type="submit" name="agregar">Agregar</button>
        <?php } ?>

    <?php } ?>
</form>

<br>

<table>
<tr>
    <th>ID</th>
    <th>Alumno</th>
    <th>Materia</th>
    <th>Fecha</th>
    <th>Nota</th>
    <th>Acciones</th>
</tr>

<?php
$resultado = $conn->query("SELECT examenes.*, CONCAT(alumnos.nombre, ' ', alumnos.apellido) AS nombre_alumno,
                           materias.nombre AS nombre_materia
                           FROM examenes
                           INNER JOIN alumnos ON examenes.id_alumno = alumnos.id_alumno
                           INNER JOIN materias ON examenes.id_materia = materias.id_materia
                           ORDER BY examenes.id_examen DESC");

while($fila = $resultado->fetch_assoc()) {
    echo "
        <tr>
            <td>".$fila['id_examen']."</td>
            <td>".$fila['nombre_alumno']."</td>
            <td>".$fila['nombre_materia']."</td>
            <td>".$fila['fecha']."</td>
            <td>".$fila['nota']."</td>
            <td>
                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='examenes'>
                    <input type='hidden' name='editar' value='".$fila['id_examen']."'>
                    <button type='submit'>Editar</button>
                </form>
                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='examenes'>
                    <input type='hidden' name='eliminar' value='".$fila['id_examen']."'>
                    <button type='submit'>Eliminar</button>
                </form>
            </td>
        </tr>";
}
?>
</table>