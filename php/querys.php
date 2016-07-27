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
  $fechaAAFin=strtotime("-1 year", strtotime($fechaFin));
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
        		count(distinct(chk)) as TransaccionesAA,
        		sum(gnditem5.price)/1.16 as VentaAA,
        		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedioAA'
        	FROM  gnditem5 LEFT
        	JOIN  sucursales on sucursales.unit=gnditem5.unit
        	WHERE DOB BETWEEN '$fechaAA' AND '$fechaAAFin'
        	GROUP BY dob,sucursal
        	ORDER BY dob,sucursal) AS PASADO ON (ACTUAL.sucursal = PASADO.sucursal);";
  return $sql;
}
