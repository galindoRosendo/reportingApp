<?php
include("dbCredentials.php");
include("querys.php");
session_start();
$fecha = $_POST['fechaA'];
$fechaAA = '2015-07-25';
$_SESSION['fechaA']=$fecha;
$_SESSION['fechaAA']=$fechaAA;
// Conexion
$conn = new mysqli(NOMBRE_HOSTMKT, USUARIOMKT,CONTRASENAMKT,BASE_DE_DATOSMKT);
// Ver si no hay error
if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$sql = queryOneDay($fecha,$fechaAA);

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // salida
    echo " <h2 style='text-align: left;'>Reporte de $fecha</h2>
    <table><th>Sucursal</th><th>Transacciones</th><th>Año Pasado</th><th>Venta</th><th>Año Pasado</th><th>Ticket Promedio</th><th>Año Pasado</th><th>Variacion Venta</th><th>Variacion V %</th><th>Variacion Transacciones</th><th>Variacion T %</th><th>Variacion Ticket Promedio</th><th>Variacion TP %</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row['sucursal']."</td><td>".$row['Transacciones']."</td><td>".$row['TransaccionesAA']."</td><td>".$row['Venta']."</td><td>".$row['VentaAA']."</td><td>".$row['TicketPromedio']."</td><td>".$row['TicketPromedioAA']."</td>";
        echo "<td>".$row['VariacionVenta$']."</td><td>".$row['VariacionVenta%']."</td><td>".$row['VariacionTransacciones']."</td><td>".$row['VariacionTransacciones%']."</td><td>".$row['VariacionTicket']."</td><td>".$row['VariacionTicket%']."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}
$conn->close();
 ?>
