<?php
//Activamos el almacenamiento en el buffer
ob_start();
If (strlen(session_id()) < 1) {
    session_start();
}
if(!isset($_SESSION['nombre'])){
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}else{
if($_SESSION['Almacén'] == 0){
    echo 'No tiene permiso para visualizar el reporte';
}
else{
    //incluimos la clase PDF_MC_Table
    require('mc_table.php');
    
    //instanciamos la clase para crear el documento pdf
    $pdf = new PDF_MC_Table();
    
    //agregamos la primera página al documento pdf
    $pdf->AddPage();
    
    //seteamos el inico del margen superior en 25 px
    $y_axis_initial = 25;
    
    //seteamos el tipo de letra y creamos el título de la página. No es un ancabezado, no se repetirá
    $pdf->SetFont('Arial', 'B', 12);
    
    $pdf->Cell(40, 6, '', 0, 0, 'C');
    $pdf->Cell(100, 6, 'LISTA DE ARTICULOS', 1, 0, 'C');
    $pdf->Ln(10);
    
//    Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y 
//    el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(58, 6, 'Nombre', 1, 0, 'C', 1);
    $pdf->Cell(50, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
    $pdf->Cell(30, 6, utf8_decode('Código'), 1, 0, 'C', 1);
    $pdf->Cell(12, 6, 'Stock', 1, 0, 'C', 1);
    $pdf->Cell(35, 6, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Ln(10);
    
    //comenzamos a crear las filas de los registros según la consulta mysql
    require_once '../modelos/Articulo.php';
    $articulo = new Articulo();
    $rsta = $articulo->listar();
    
    //implementamos las celdas de la tabla con los registros a mostrar
    $pdf->SetWidths(array(58,50,30,12,35));
    while($fila = $rsta->fetch_object()){
        $nombre = $fila->nombre;
        $codigo = $fila->codigo;
        $categoria = $fila->categoria;
        $stock = $fila->stock;
        $descripcion = $fila->descripcion;
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Row(array(utf8_decode($nombre), utf8_decode($categoria), $codigo,$stock, utf8_decode($descripcion)));
    }
    
    //mostramos el documento pdf
    $pdf->Output();
}
}
ob_end_flush();
?>