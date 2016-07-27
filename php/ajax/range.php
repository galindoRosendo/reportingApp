<?php
include("../sql/dbCredentials.php");
include("../sql/querys.php");
session_start();
$fecha = $_POST['fechaA'];
$fechaFin = $_POST['fechaFin'];
$_SESSION['fechaA']=$fecha;
$_SESSION['fechaFin']=$fechaFin;
// Conexion
$conn = new mysqli(NOMBRE_HOSTMKT, USUARIOMKT,CONTRASENAMKT,BASE_DE_DATOSMKT);
// Ver si no hay error
if ($conn->connect_error) {
  die("<div class='formi'>
        <div class='error'>
          <h3>Error de conexion: ". $conn->connect_error."</h3>
        </div>
      </div>");
}

$sql = queryRange($fecha,$fechaFin);

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // salida
    echo " <h2 style='text-align: left;'>Reporte de $fecha a $fechaFin</h2>
    <table><th>Sucursal</th><th>Fecha</th><th>Fecha Anterior</th><th>Transacciones</th><th>Año Pasado</th><th>Venta</th><th>Año Pasado</th><th>Ticket Promedio</th><th>Año Pasado</th><th>Variacion Venta</th><th>Variacion V %</th><th>Variacion Transacciones</th><th>Variacion T %</th><th>Variacion Ticket Promedio</th><th>Variacion TP %</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row['sucursal']."</td><td>".$row['Fecha']."</td><td>".$row['FechaAA']."</td><td>".$row['Transacciones']."</td><td>".$row['TransaccionesAA']."</td><td>".$row['Venta']."</td><td>".$row['VentaAA']."</td><td>".$row['TicketPromedio']."</td><td>".$row['TicketPromedioAA']."</td>";
        echo "<td>".$row['VariacionVenta$']."</td><td>".$row['VariacionVenta%']."</td><td>".$row['VariacionTransacciones']."</td><td>".$row['VariacionTransacciones%']."</td><td>".$row['VariacionTicket']."</td><td>".$row['VariacionTicket%']."</td></tr>";
    }
    echo "</table>";
} else {
  echo "<div class='formi'>
  <div class='info'>
    <h3>0 resultados</h3>
  </div>
</div>";
}
$conn->close();
 ?>
