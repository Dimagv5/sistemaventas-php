<?php
require_once '../modelos/Consultas.php';

$consulta = new Consultas();


switch ($_GET['op']){
    case 'comprasfecha':
        $fecha_inicio = $_REQUEST['fecha_inicio'];
        $fecha_fin = $_REQUEST['fecha_fin'];
        
        $rspt = $consulta->comprasfecha($fecha_inicio, $fecha_fin);
        $data = Array();
        
        while($reg = $rspt->fetch_object()){
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->proveedor,
                "2" => $reg->usuario,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
                "5" => $reg->impuesto,
                "6" => $reg->total_compra,
                "7" => $reg->estado == 'Aceptado' ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>'        
            );     
        }    
        $result = array(
            "sEcho" => 1, //Información para el datatable
            "iTotalRecords" => count($data), //enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total de registtros a visualizar
            "aaData" => $data
        );
        echo json_encode($result);
    break;
    case 'ventasfechacliente':
        $fecha_inicio = $_REQUEST['fecha_inicio'];
        $fecha_fin = $_REQUEST['fecha_fin'];
        $idcliente = $_REQUEST['idcliente'];
        
        $rspt = $consulta->ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente);
        $data = Array();
        
        while($reg = $rspt->fetch_object()){
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->cliente,
                "2" => $reg->usuario,
                "3" => $reg->tipo_comprobante,
                "4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
                "5" => $reg->impuesto,
                "6" => $reg->total_venta,
                "7" => $reg->estado == 'Aceptado' ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>'        
            );     
        }    
        $result = array(
            "sEcho" => 1, //Información para el datatable
            "iTotalRecords" => count($data), //enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total de registtros a visualizar
            "aaData" => $data
        );
        echo json_encode($result);
    break;      
}

