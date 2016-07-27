#Range
SELECT 
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
		date_format(dob, "%d/%m/%Y") as Fecha,
		count(distinct(chk)) as Transacciones,
		sum(gnditem5.price)/1.16 as Venta,
		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedio'
	FROM  gnditem5 LEFT
	JOIN  sucursales on sucursales.unit=gnditem5.unit
	WHERE DOB BETWEEN '2016-07-23' AND '2016-07-25'
	GROUP BY dob,sucursal
	ORDER BY dob,sucursal) AS ACTUAL JOIN
	(SELECT
		sucursal as Sucursal,
		date_format(dob, "%d/%m/%Y") as FechaAA,
		count(distinct(chk)) as TransaccionesAA,
		sum(gnditem5.price)/1.16 as VentaAA,
		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedioAA'
	FROM  gnditem5 LEFT
	JOIN  sucursales on sucursales.unit=gnditem5.unit
	WHERE DOB BETWEEN '2015-07-23' AND '2015-07-25'
	GROUP BY dob,sucursal
	ORDER BY dob,sucursal) AS PASADO ON (ACTUAL.sucursal = PASADO.sucursal);

#ondeDay
SELECT 
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
	WHERE DOB ='2016-07-25'
	GROUP BY dob,sucursal
	ORDER BY dob,sucursal) AS ACTUAL JOIN
	(SELECT
		sucursal as Sucursal,
		count(distinct(chk)) as TransaccionesAA,
		sum(gnditem5.price)/1.16 as VentaAA,
		(sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'TicketPromedioAA'
	FROM  gnditem5 LEFT
	JOIN  sucursales on sucursales.unit=gnditem5.unit
	WHERE DOB ='2015-07-23'
	GROUP BY dob,sucursal
	ORDER BY dob,sucursal) AS PASADO ON (ACTUAL.sucursal = PASADO.sucursal);
    