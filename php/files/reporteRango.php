<?php
include("../sql/dbCredentials.php");
include("../sql/querys.php");
session_start();
$fecha=$_SESSION['fechaA'];
$fechaFin=$_SESSION['fechaFin'];
// Conexion
$conn = new mysqli(NOMBRE_HOSTMKT, USUARIOMKT,CONTRASENAMKT,BASE_DE_DATOSMKT);
// Ver si no hay error
if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$sql = queryRange($fecha,$fechaFin);

$result = $conn->query($sql);

$conn->close();

if ($result->num_rows > 0) {
  date_default_timezone_set('America/Mexico_City');
   require_once '../Classes/PHPExcel.php';
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

    $tituloReporte = "Reporte de Ventas por Rango";
    $titulosColumnas = array('Sucursal','Fecha','Fecha Anterior','Transacciones','Transacciones AA','Venta','Venta AA','Ticket Promedio','TP AA','Variacion Venta $','Variacion Venta %','Variacion Transacciones','Variacion Tr %','Variacion TP','Variacion TP %');

    // Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:O1');

    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])  //Titulo de las columnas
    ->setCellValue('F3',  $titulosColumnas[5])
    ->setCellValue('G3',  $titulosColumnas[6])
    ->setCellValue('H3',  $titulosColumnas[7])
    ->setCellValue('I3',  $titulosColumnas[8])  //Titulo de las columnas
    ->setCellValue('J3',  $titulosColumnas[9])
    ->setCellValue('K3',  $titulosColumnas[10])
    ->setCellValue('L3',  $titulosColumnas[11])
    ->setCellValue('M3',  $titulosColumnas[12])
    ->setCellValue('N3',  $titulosColumnas[13])
    ->setCellValue('O3',  $titulosColumnas[14]);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.'2', 'Fecha:')
        ->setCellValue('B'.'2', $fecha)
        ->setCellValue('C'.'2', 'A:')
        ->setCellValue('D'.'2', $fechaFin);

    $i = 4; //Numero de fila donde se va a comenzar a rellenar
    while ($fila = $result->fetch_array()) {
     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['sucursal'])
         ->setCellValue('B'.$i, $fila['Fecha'])
         ->setCellValue('C'.$i, $fila['FechaAA'])
         ->setCellValue('D'.$i, $fila['Transacciones'])
         ->setCellValue('E'.$i, $fila['TransaccionesAA'])
         ->setCellValue('F'.$i, $fila['Venta'])
         ->setCellValue('G'.$i, $fila['VentaAA'])
         ->setCellValue('H'.$i, $fila['TicketPromedio'])
         ->setCellValue('I'.$i, $fila['TicketPromedioAA'])
         ->setCellValue('J'.$i, $fila['VariacionVenta$'])
         ->setCellValue('K'.$i, $fila['VariacionVenta%'])
         ->setCellValue('L'.$i, $fila['VariacionTransacciones'])
         ->setCellValue('M'.$i, $fila['VariacionTransacciones%'])
         ->setCellValue('N'.$i, $fila['VariacionTicket'])
         ->setCellValue('O'.$i, $fila['VariacionTicket%']);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:O".($i-1));
for($i = 'A'; $i <= 'O'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Reporte'.$fecha.'-'.$fechaFin);

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);


// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename='Reporte".$fecha.'-'.$fechaFin.".xlsx'");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
}
else{
    print_r('No hay resultados para mostrar');
}


 ?>
