<?php
include("dbCredentials.php");
session_start();
$fecha = $_POST['fechainicio'];
$fechafin = $_POST['fechafin'];
$_SESSION['fechainicio']=$fecha;
$_SESSION['fechafin']=$fechafin;
// Conexion
$conn = new mysqli(NOMBRE_HOSTMKT, USUARIOMKT,CONTRASENAMKT,BASE_DE_DATOSMKT);
// Ver si no hay error
if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$sql = "SELECT
            sucursal as Sucursal,
            count(distinct(chk)) as Transacciones,
            sum(gnditem5.price)/1.16 as Venta,
            (sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'Ticket Promedio'
        FROM  gnditem5 LEFT
        JOIN  sucursales on sucursales.unit=gnditem5.unit
        WHERE DOB ='$fecha'
        GROUP BY dob,sucursal
        ORDER BY dob,sucursal;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // salida
    echo "<table><th>Sucursal</th><th>Transacciones</th><th>Venta</th><th>Ticket Promedio</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["Sucursal"]."</td><td>".$row["Transacciones"]."</td><td>".$row["Venta"]."</td><td>".$row['Ticket Promedio']."</td>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
 ?>
