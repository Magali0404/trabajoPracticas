<?php 
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM profesores WHERE id_profesor = $id");
}

if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $conn->query("INSERT INTO profesores (nombre, apellido, email, telefono) 
                  VALUES ('$nombre', '$apellido', '$email', '$telefono')");
}

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $conn->query("UPDATE profesores 
                  SET nombre='$nombre', apellido='$apellido',
                      email='$email', telefono='$telefono'
                  WHERE id_profesor=$id");
}

$editando = false;

if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM profesores WHERE id_profesor = $id");
    $fila = $resultado->fetch_assoc();
    $editando = true;
}
?>

<h1>Profesores</h1>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editando ? $fila['id_profesor'] : ''; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" required
        value="<?php echo $editando ? $fila['nombre'] : ''; ?>">

    <label>Apellido:</label>
    <input type="text" name="apellido" required
        value="<?php echo $editando ? $fila['apellido'] : ''; ?>">

    <label>Email:</label>
    <input type="email" name="email" required
        value="<?php echo $editando ? $fila['email'] : ''; ?>">

    <label>Teléfono:</label>
    <input type="text" name="telefono"
        value="<?php echo $editando ? $fila['telefono'] : ''; ?>">

    <?php if ($editando) { ?>
        <button type="submit" name="editar">Actualizar</button>
        <a href="menu.php?pagina=profesores"><button type="button">Limpiar</button></a>
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
    <th>Email</th>
    <th>Teléfono</th>
    <th>Acciones</th>
</tr>

<?php
$sql = "SELECT * FROM profesores ORDER BY id_profesor";
$resultado = $conn->query($sql);

while($fila = $resultado->fetch_assoc()) {
    echo "
        <tr>
            <td>".$fila['id_profesor']."</td>
            <td>".$fila['nombre']."</td>
            <td>".$fila['apellido']."</td>
            <td>".$fila['email']."</td>
            <td>".$fila['telefono']."</td>
            <td>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='profesores'>
                    <input type='hidden' name='editar' value='".$fila['id_profesor']."'>
                    <button type='submit'>Editar</button>
                </form>

                <form method='GET' action='menu.php' style='display:inline;'>
                    <input type='hidden' name='pagina' value='profesores'>
                    <input type='hidden' name='eliminar' value='".$fila['id_profesor']."'>
                    <button type='submit'>Eliminar</button>
                </form>

            </td>
        </tr>
        ";
}
?>
</table>