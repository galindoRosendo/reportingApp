<?php
include("../sql/dbCredentials.php");
include("../sql/querys.php");
error_reporting(E_ALL ^ E_NOTICE);
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

$result_array = array();

while($row = $result->fetch_assoc()) {
  $result_array[] = $row;
}

$conn->close();

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

$sql= querySucursales();

$sucursales = array();

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
  $sucursales[] = $row;
}

$conn->close();

//Sucursales
$resultAcum[0] = array();
//Ventas
$resultAcum[1] = array();
//Transacciones
$resultAcum[2] = array();

for ($i=0; $i <count($sucursales) ; $i++) {
  for ($j=0; $j < count($result_array); $j++) {
    if($result_array[$j]['sucursal'] == $sucursales[$i]['sucursal']){
      $resultAcum[0][$i]= $result_array[$j]['sucursal'];
      $resultAcum[1][$i]= $resultAcum[1][$i] + $result_array[$j]['Venta'];
      $resultAcum[2][$i]= $resultAcum[2][$i] + $result_array[$j]['Transacciones'];
    }
  }
}
echo "<h2 style='text-align: left;'>Reporte de Venta Acumulada: $fecha a $fechaFin</h2>
<table>
  <th>Sucursal</th><th>Venta Acumulada</th><th>Transacciones Acumuladas</th>";
for ($i=0; $i < count($resultAcum[0]); $i++) {
  echo "
    <tr>
      <td>".$resultAcum[0][$i]."</td><td>".$resultAcum[1][$i]."</td><td>".$resultAcum[2][$i]."</td>
    </tr>";
}
echo "</table>";

$_SESSION['arrayAcum'] = array($resultAcum[0], $resultAcum[1], $resultAcum[2]);
 ?>
