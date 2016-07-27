use prueba;
set @inicial = '2016-06-20';
set @final = '2016-06-26';
select    dob as Fecha, item as Item, gnditem5.price as Precio, count(*) as Cantidad, sum(gnditem5.price)/1.16 as Venta, name as Canal, 'Laredo', sucursal as Sucursal
from gnditem5 left
join sucursales on sucursales.unit=gnditem5.unit left
join itmch on itmch.id=gnditem5.item left
join odrch on odrch.id=gnditem5.mode
where dob between @inicial and @final AND gnditem5.DISCPRIC > '0' AND (sucursal = 'ldo1' or sucursal = 'ldo2' or sucursal = 'ldo3' or sucursal = 'ldo5' or sucursal = 'ldo6' or sucursal = 'ldo7' or sucursal = 'ldo8' or sucursal = 'png1')
group by dob, item, gnditem5.price, name, gnditem5.unit

UNION

select    dob as Fecha, item as Item, gnditem5.price as Precio, count(*) as Cantidad, sum(gnditem5.price)/1.16 as Venta, name as Canal, 'Reynosa', sucursal as Sucursal
from gnditem5 left
join sucursales on sucursales.unit=gnditem5.unit left
join itmch on itmch.id=gnditem5.item left
join odrch on odrch.id=gnditem5.mode
where dob between @inicial and @final AND gnditem5.DISCPRIC > '0' AND (sucursal = 'rey1' or sucursal = 'rey2' or sucursal = 'rey3' or sucursal = 'rey4' or sucursal = 'rey5' or sucursal = 'rey6' or sucursal = 'rey7' or sucursal = 'rey8' or sucursal = 'mal1')
group by dob, item, gnditem5.price, name, gnditem5.unit

UNION

select    dob as Fecha, item as Item, gnditem5.price as Precio, count(*) as Cantidad, sum(gnditem5.price)/1.16 as Venta, name as Canal, 'Matamoros', sucursal as Sucursal
from gnditem5 left
join sucursales on sucursales.unit=gnditem5.unit left
join itmch on itmch.id=gnditem5.item left
join odrch on odrch.id=gnditem5.mode
where dob between @inicial and @final AND gnditem5.DISCPRIC > '0' AND (sucursal = 'mat1' or sucursal = 'mat2' or sucursal = 'mat3' or sucursal = 'mat4' or sucursal = 'mat6' or sucursal = 'mat7' or sucursal = 'rbr1')
group by dob, item, gnditem5.price, name, gnditem5.unit
