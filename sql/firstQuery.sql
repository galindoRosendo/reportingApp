DESCRIBE gnditem5;

USE prueba;

#churchs
SELECT chk,mode,unit,dob, dispric
FROM gnditem5
GROUP BY chk
LIMIT 100;

#papa jones
SELECT *
FROM gnditempj2;

#QUERY CARLOS
USE prueba;

SET lc_time_names = 'es_MX';

use prueba;
SET lc_time_names = 'es_MX';
select     
    date_format(dob, "%d/%m/%Y") as Fecha,
    sucursal as Sucursal,
    name as Canal,
    count(distinct(chk)) as Transacciones,
    sum(gnditem5.price)/1.16 as Venta,
    WEEK(dob,3) as Semana,
    monthname(dob) as Mes
from     gnditem5 left
join     sucursales on sucursales.unit=gnditem5.unit left
join     odrch on odrch.id=gnditem5.mode
where DOB between '2016-05-01' and '2016-05-31'
group by dob,sucursal,name
order by dob,sucursal