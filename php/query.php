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

$sql = "select
	sucursal as sucursales,
	SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 7 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '7DIAS',
	SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 6 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '6DIAS',
	SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 5 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '5DIAS',
    SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 4 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '4DIAS',
    SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 3 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '3DIAS',
    SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 2 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '2DIAS',
    SUM(CASE WHEN DOB = ( '$fecha' - INTERVAL 1 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '1DIAS'
    from sucursales
    left join gnditem5 on gnditem5.unit=sucursales.unit
    where DOB >= ( '$fecha' - INTERVAL 7 DAY )
    group by sucursal
    ORDER BY FIELD(sucursales, 'ldo1','ldo2','ldo3','ldo5','ldo6','ldo7','ldo8','REY1','REY2','REY3','REY4','REY5','REY6','REY7','REY8','MAT1','MAT2','MAT3','MAT4','MAT6','MAT7','JRZ3','JRZ4','JRZ6','JRZ8','JRZ9','mal1','rbr1','png1')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // salida
    echo "<table><th>Sucursales</th><th>7Dias</th><th>6Dias</th><th>5Dias</th><th>4Dias</th><th>3Dias</th><th>2Dias</th><th>1Dia</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["sucursales"]."</td><td>".$row["7DIAS"]."</td><td>".$row["6DIAS"]."</td><td>".$row["5DIAS"]."</td><td>".$row["4DIAS"]."</td><td>".$row["3DIAS"]."</td><td>".$row["2DIAS"]."</td><td>".$row["1DIAS"];
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
 ?>
