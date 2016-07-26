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

$sql = "SELECT
            sucursal as Sucursal,
            count(distinct(chk)) as Transacciones,
            sum(gnditem5.price)/1.16 as Venta,
            (sum(gnditem5.price)/1.16)/(count(distinct(chk))) as 'Ticket Promedio'
        FROM  gnditem5 LEFT
        JOIN  sucursales on sucursales.unit=gnditem5.unit
        WHERE DOB ='$fecha'
        GROUP BY dob,sucursal
        ORDER BY dob,sucursal;";
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

    $tituloReporte = "Reporte de Ventas Dia";
    $titulosColumnas = array('Sucursal','Transacciones','Venta','Ticket Promedio');

    // Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:D1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3]);

    $i = 4; //Numero de fila donde se va a comenzar a rellenar
    while ($fila = $result->fetch_array()) {
     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['Sucursal'])
         ->setCellValue('B'.$i, $fila['Transacciones'])
         ->setCellValue('C'.$i, $fila['Venta'])
         ->setCellValue('D'.$i, $fila['Ticket Promedio']);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
for($i = 'A'; $i <= 'D'; $i++){
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
