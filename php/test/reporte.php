<?php
require_once('dbCredentials.php');
session_start();
$fecha=$_SESSION['fechainicio'];
$fechafin=$_SESSION['fechafin'];
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

$conn->close();

if ($result->num_rows > 0) {
  date_default_timezone_set('America/Mexico_City');
   require_once 'Classes/PHPExcel.php';
   $objPHPExcel = new PHPExcel();
   $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');

   $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
   //Informacion del excel
   $objPHPExcel->getProperties()->setCreator("Dival") // Nombre del autor
    ->setLastModifiedBy("Dival") //Ultimo usuario que lo modificó
    ->setTitle("Reporte de Ventas") // Titulo
    ->setSubject("Reporte Excel") //Asunto
    ->setDescription("Reporte de Ventas") //Descripción
    ->setKeywords("reporte ventas excel") //Etiquetas
    ->setCategory("Reporte excel"); //Categorias

    $tituloReporte = "Reporte de Ventas";
    $titulosColumnas = array('Sucursales','7Dias','6Dias','5Dias','4Dias','3Dias','2Dias','1Dia');

    // Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:H1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])
    ->setCellValue('F3',  $titulosColumnas[5])
    ->setCellValue('G3',  $titulosColumnas[6])
    ->setCellValue('H3',  $titulosColumnas[7]);

    $i = 4; //Numero de fila donde se va a comenzar a rellenar
    while ($fila = $result->fetch_array()) {
     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['sucursales'])
         ->setCellValue('B'.$i, $fila['7DIAS'])
         ->setCellValue('C'.$i, $fila['6DIAS'])
         ->setCellValue('D'.$i, $fila['5DIAS'])
         ->setCellValue('E'.$i, $fila['4DIAS'])
         ->setCellValue('F'.$i, $fila['3DIAS'])
         ->setCellValue('G'.$i, $fila['2DIAS'])
         ->setCellValue('H'.$i, $fila['1DIAS']);
     $i++;
 }
 $estiloTituloReporte = array(
    'font' => array(
        'name'      => 'Verdana',
        'bold'      => true,
        'italic'    => false,
        'strike'    => false,
        'size' =>16,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array(
            'argb' => 'FFFFFFFF')
  ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$estiloTituloColumnas = array(
    'font' => array(
        'name'  => 'Arial',
        'bold'  => true,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '000000'
            )
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '000000'
            )
        )
    ),
    'alignment' =>  array(
        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap'      => TRUE
    )
);

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray( array(
    'font' => array(
        'name'  => 'Arial',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN ,
      'color' => array(
              'rgb' => '000000'
            )
        )
    )
));
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:H".($i-1));
for($i = 'A'; $i <= 'H'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);


// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
}
else{
    print_r('No hay resultados para mostrar');
}


 ?>
