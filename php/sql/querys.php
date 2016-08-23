<?php

function queryOneDay($fecha,$fechaAA){
  $sql = "SELECT
          	ACTUAL.sucursal,
            ACTUAL.Transacciones,
            PASADO.TransaccionesAA,
            ACTUAL.Venta,
            PASADO.VentaAA,
            ACTUAL.TicketPromedio,
            PASADO.TicketPromedioAA,
            PASADO.VentaAA - ACTUAL.Venta  as 'VariacionVenta$',
            (ACTUAL.Venta)/(PASADO.VentaAA) -1   as 'VariacionVenta%',
            PASADO.TransaccionesAA - ACTUAL.Transacciones  as 'VariacionTransacciones',
            (ACTUAL.Transacciones)/(PASADO.TransaccionesAA) -1   as 'VariacionTransacciones%',
          	PASADO.TicketPromedioAA - ACTUAL.TicketPromedio  as 'VariacionTicket',
            (ACTUAL.TicketPromedio)/(PASADO.TicketPromedioAA) -1   as 'VariacionTicket%'
          FROM
          	(SELECT
          	sucursal as Sucursal,
          	count(distinct(chk)) as Transacciones,
          	sum(gnditem5.price)/1.16 as Venta,
              (sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedio'
          	FROM  gnditem5 LEFT
          	JOIN  sucursales on sucursales.unit=gnditem5.unit
          	WHERE DOB ='$fecha'
          	GROUP BY dob,sucursal
          	ORDER BY dob,sucursal) AS ACTUAL JOIN
          	(SELECT
          		sucursal as Sucursal,
          		count(distinct(chk)) as TransaccionesAA,
          		sum(gnditem5.price)/1.16 as VentaAA,
          		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedioAA'
          	FROM  gnditem5 LEFT
          	JOIN  sucursales on sucursales.unit=gnditem5.unit
          	WHERE DOB ='$fechaAA'
          	GROUP BY dob,sucursal
          	ORDER BY dob,sucursal) AS PASADO ON (ACTUAL.sucursal = PASADO.sucursal);";
  return $sql;
}

function queryRange($fecha,$fechaFin){
  $fechaAA= strtotime("-1 year", strtotime($fecha));
  $fechaAA=date("Y-m-d", $fechaAA);
  $fechaAA= strtotime("+2 day", strtotime($fechaAA));
  $fechaAA=date("Y-m-d", $fechaAA);
  $fechaAAFin=strtotime("-1 year", strtotime($fechaFin));
  $fechaAAFin=date("Y-m-d", $fechaAAFin);
  $fechaAAFin= strtotime("+2 day", strtotime($fechaAAFin));
  $fechaAAFin=date("Y-m-d", $fechaAAFin);
$sql = "SELECT
        	ACTUAL.sucursal,
          ACTUAL.Fecha,
          PASADO.FechaAA,
          ACTUAL.Transacciones,
          PASADO.TransaccionesAA,
          ACTUAL.Venta,
          PASADO.VentaAA,
          ACTUAL.TicketPromedio,
          PASADO.TicketPromedioAA,
          PASADO.VentaAA - ACTUAL.Venta  as 'VariacionVenta$',
          (ACTUAL.Venta)/(PASADO.VentaAA) -1   as 'VariacionVenta%',
          PASADO.TransaccionesAA - ACTUAL.Transacciones  as 'VariacionTransacciones',
          (ACTUAL.Transacciones)/(PASADO.TransaccionesAA) -1   as 'VariacionTransacciones%',
        	PASADO.TicketPromedioAA - ACTUAL.TicketPromedio  as 'VariacionTicket',
          (ACTUAL.TicketPromedio)/(PASADO.TicketPromedioAA) -1   as 'VariacionTicket%'
        FROM
        	(SELECT
        		sucursal as Sucursal,
        		date_format(dob, '%d/%m/%Y') as Fecha,
                DAYOFMONTH(dob) as Dia,
        		count(distinct(chk)) as Transacciones,
        		sum(gnditem5.price)/1.16 as Venta,
        		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedio'
        	FROM  gnditem5 LEFT
        	JOIN  sucursales on sucursales.unit=gnditem5.unit
        	WHERE DOB BETWEEN '$fecha' AND '$fechaFin'
        	GROUP BY dob,sucursal
        	ORDER BY dob,sucursal) AS ACTUAL JOIN
        	(SELECT
        		sucursal as Sucursal,
        		date_format(dob, '%d/%m/%Y') as FechaAA,
                DAYOFMONTH(dob) as DiaAA,
        		count(distinct(chk)) as TransaccionesAA,
        		sum(gnditem5.price)/1.16 as VentaAA,
        		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedioAA'
        	FROM  gnditem5 LEFT
        	JOIN  sucursales on sucursales.unit=gnditem5.unit
        	WHERE DOB BETWEEN '$fechaAA' AND '$fechaAAFin'
        	GROUP BY dob,sucursal
        	ORDER BY dob,sucursal) AS PASADO ON (ACTUAL.sucursal = PASADO.sucursal) AND ((ACTUAL.Dia+2) = PASADO.DiaAA);";
  return $sql;
}

function querySucursales(){
  $sql = "SELECT sucursal
          FROM sucursales;";

    return $sql;
}

/*
===========================================================
  Funciones que manejan el reporte de inventario
===========================================================

function queryItemsInventario($paramsQueryInventarios){
  $paramsQueryInventarios['items'];
  $paramsQueryInventarios['fechas'];
  $paramsQueryInventarios['sucursales'];
  $sql = "";
  return $sql;
}

function llenarHojas($DBQuery){
  //Variables de inicio
  $items = $DBQuery['item'];
  $fechas = $DBQuery['fecha'];
  $cantidadFechas = count($fechas);
  $sucs = $DBQuery['sucs'];
  $formula;
  $formulaTotal;
  $hojaActualdeExcel;
  $columna;
  $renglon;
  $celdaActual
  llenarHojaDB($DBQuery);
  for ($i=0; $i < count($items); $i++) {
      llenarHojaVentaItems($items[$i],$fechas,$sucs);
      $hojaActualdeExcel++;
  }

}

function llenarHojaDB($DBQuery){
  //Asignar la primer hoja con php
  $hojaActualdeExcel = 0;

  //llenar informacion de la base de datos

  //Cambiar de hoja ACTUAL
  $hojaActualdeExcel++;
}

function llenarHojaVentaItems($item,$fechas,$sucs){
  llenarItem($item);
  llenarFechas($fechas);
}

function llenarItem($item){
  //asignar valor con phpexcel a la celda X,Y
  $celdaActual[$columna][$renglon].valor = $item;
  $columna++;
}

function llenarFechas($fechas){
  for ($j=0; $j < count($fechas); $j++) {
    $celdaActual=$fecha[$j];
    $columna++;
  }
  $renglon++;
}

function llenarRenglon($suc,$formula,$cantidadFechas){
  $celdaActual= $suc;
  $columna++;
  for ($k=0; $k < $cantidadFechas; $k++) {
    $celdaActual=$formula;
    $columna++;
  }
  $renglon++;
}

function vaciarFormulasTotales($FormulaTotal){
  //FormulaTotal
  $celdaActual = $FormulaTotal;
  $columna++;
}
*/
