use prueba;
SET lc_time_names = 'es_MX';
select     
    #date_format(dob, "%d/%m/%Y") as Fecha,
    sucursal as Sucursal,
    #name as Canal,
    count(distinct(chk)) as Transacciones,
    sum(gnditem5.price)/1.16 as Venta
    #WEEK(dob,3) as Semana,
    #monthname(dob) as Mes
from     gnditem5 left
join     sucursales on sucursales.unit=gnditem5.unit left
join     odrch on odrch.id=gnditem5.mode
where DOB ='2015-07-23' #between '2016-05-01' and '2016-05-31'
group by dob,sucursal
order by dob,sucursal;

select curdate();

select
	sucursal as sucursales,
	SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 7 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '7DIAS',
	SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 6 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '6DIAS',
	SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 5 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '5DIAS',
    SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 4 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '4DIAS',
    SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 3 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '3DIAS',
    SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 2 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '2DIAS',
    SUM(CASE WHEN DOB = ( CURDATE() - INTERVAL 1 DAY )  THEN ROUND((gnditem5.price/1.16), 2) ELSE 0 END) '1DIAS'
    from sucursales
    left join gnditem5 on gnditem5.unit=sucursales.unit
    where DOB >= ( CURDATE() - INTERVAL 7 DAY )
    group by sucursal
    ORDER BY FIELD(sucursales, 'ldo1','ldo2','ldo3','ldo5','ldo6','ldo7','ldo8','REY1','REY2','REY3','REY4','REY5','REY6','REY7','REY8','MAT1','MAT2','MAT3','MAT4','MAT6','MAT7','JRZ3','JRZ4','JRZ6','JRZ8','JRZ9','mal1','rbr1','png1')