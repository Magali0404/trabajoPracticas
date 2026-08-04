<?php
require_once("Conexion.php");

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = $conn->query("
    SELECT 
        CONCAT(alumnos.nombre, ' ', alumnos.apellido) AS alumno,
        materias.nombre AS materia,
        materias.curso,
        examenes.nota AS nota_final,
        CASE 
            WHEN examenes.nota >= materias.nota_aprobacion THEN 'Aprobado'
            ELSE 'Desaprobado'
        END AS condicion
    FROM examenes
    INNER JOIN alumnos ON examenes.id_alumno = alumnos.id_alumno
    INNER JOIN materias ON examenes.id_materia = materias.id_materia
    ORDER BY alumnos.apellido, materias.curso
");
?>

<h1>Trayectoria</h1>

<table>
<tr>
    <th>Alumno</th>
    <th>Materia</th>
    <th>Curso</th>
    <th>Nota final</th>
    <th>Condición</th>
</tr>

<?php while($fila = $resultado->fetch_assoc()) {
    $color = $fila['condicion'] == 'Aprobado' ? '#27ae60' : '#e74c3c';
    echo "
    <tr>
        <td>".$fila['alumno']."</td>
        <td>".$fila['materia']."</td>
        <td>".$fila['curso']."</td>
        <td>".$fila['nota_final']."</td>
        <td style='color:white; background-color:$color; text-align:center; border-radius:4px; padding:4px 8px;'>".$fila['condicion']."</td>
    </tr>";
} ?>
</table>