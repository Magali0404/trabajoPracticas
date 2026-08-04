<?php 
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM alumnos WHERE id_alumno = $id");
}

if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $id_carrera = $_POST['id_carrera'];

    $conn->query("INSERT INTO alumnos (nombre, apellido, dni, email, fecha_nacimiento, telefono, id_carrera) 
                  VALUES ('$nombre', '$apellido', '$dni', '$email', '$fecha_nacimiento', '$telefono', '$id_carrera')");
}

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $id_carrera = $_POST['id_carrera'];

    $conn->query("UPDATE alumnos 
                  SET nombre='$nombre', apellido='$apellido', dni='$dni',
                      email='$email', fecha_nacimiento='$fecha_nacimiento',
                      telefono='$telefono', id_carrera='$id_carrera'
                  WHERE id_alumno=$id");
}

$editando = false;

if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM alumnos WHERE id_alumno = $id");
    $fila = $resultado->fetch_assoc();
    $editando = true;
}

$carreras = $conn->query("SELECT id_carrera, nombre FROM carreras");
?>

<h1>Alumnos</h1>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editando ? $fila['id_alumno'] : ''; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" required
        value="<?php echo $editando ? $fila['nombre'] : ''; ?>">

    <label>Apellido:</label>
    <input type="text" name="apellido" required
        value="<?php echo $editando ? $fila['apellido'] : ''; ?>">

    <label>DNI:</label>
    <input type="text" name="dni" required
        value="<?php echo $editando ? $fila['dni'] : ''; ?>">

    <label>Email:</label>
    <input type="email" name="email" required
        value="<?php echo $editando ? $fila['email'] : ''; ?>">

    <label>Fecha de nacimiento:</label>
    <input type="date" name="fecha_nacimiento" required
        value="<?php echo $editando ? $fila['fecha_nacimiento'] : ''; ?>">

    <label>Teléfono:</label>
    <input type="text" name="telefono"
        value="<?php echo $editando ? $fila['telefono'] : ''; ?>">

    <label>Carrera:</label>
    <select name="id_carrera" required>
        <?php while($c = $carreras->fetch_assoc()) { ?>
            <option value="<?php echo $c['id_carrera']; ?>"
                <?php echo ($editando && $fila['id_carrera'] == $c['id_carrera']) ? 'selected' : ''; ?>>
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select>

    <?php if ($editando) { ?>
        <button type="submit" name="editar">Actualizar</button>
        <a href="menu.php?pagina=alumnos"><button type="button">Limpiar</button></a>
    <?php } else { ?>
        <button type="submit" name="agregar">Agregar</button>
    <?php } ?>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>DNI</th>
    <th>Email</th>
    <th>Fecha de nacimiento</th>
    <th>Teléfono</th>
    <th>Carrera</th>
    <th>Acciones</th>
</tr>

<?php
$sql = "SELECT alumnos.*, carreras.nombre AS nombre_carrera
        FROM alumnos
        INNER JOIN carreras ON alumnos.id_carrera = carreras.id_carrera
        ORDER BY alumnos.id_alumno";
$resultado = $conn->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "
        <tr>
            <td>".$fila['id_alumno']."</td>
            <td>".$fila['nombre']."</td>
            <td>".$fila['apellido']."</td>
            <td>".$fila['dni']."</td>
            <td>".$fila['email']."</td>
            <td>".$fila['fecha_nacimiento']."</td>
            <td>".$fila['telefono']."</td>
            <td>".$fila['nombre_carrera']."</td>
            <td>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='alumnos'>
                    <input type='hidden' name='editar' value='".$fila['id_alumno']."'>
                    <button type='submit'>Editar</button>
                </form>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='alumnos'>
                    <input type='hidden' name='eliminar' value='".$fila['id_alumno']."'>
                    <button type='submit'>Eliminar</button>
                </form>

            </td>
        </tr>
        ";
}
?>
</table>