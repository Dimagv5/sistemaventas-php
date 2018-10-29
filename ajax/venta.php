<?php

If (strlen(session_id()) < 1) {
    session_start();
}
require_once('../modelos/Venta.php');

$venta = new Venta();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION['idusuario'];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

//if($nombre != "" && $descripcion != ""){

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idventa)) {
            $rpta = $venta->insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $_POST['idarticulo'], $_POST['cantidad'], $_POST['descuento'], $_POST['precio_venta']);
            echo $rpta ? "Venta guardada" : "No se pudo guardar todos los datos de la venta";
        } else {
            
        }
        break;
    case 'anular':
        $rpta = $venta->anular($idventa);
        echo $rpta ? "Registro anulado exitosamente" : "No se pudo anular registro";
        break;
    case 'mostrar':
        $rpta = $venta->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rpta);
        break;
    case 'listarDetalle':
        //recibimos el idingreso
        $id = $_GET['id'];
        
        $total = 0;
        $rpta = $venta->listarDetalle($id);
        echo '<thead style="background-color: #A9D0F5;">
                                        <th>Opciones</th>
                                        <th>Artículo</th>
                                        <th>Cantidad</th>
                                        <th>Precio Venta</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>';
        while($reg = $rpta->fetch_object()){
            echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio_venta.'</td><td>'.$reg->descuento.'</td><td>'.$reg->subtotal.'</td></tr>';
            $total = $total + $reg->subtotal;
        }
        echo '<tfoot>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"/></th>
                                    </tfoot>';
        break;
    case 'listar':
        $rpta = $venta->listar();
        //Vamos a declarar un array
        $data = Array();

        while ($reg = $rpta->fetch_object()) {
            if($reg->tipo_comprobante == 'Factura'){
                $url = '../reportes/exFactura.php?id=';
            }else{
                $url = '../reportes/exTicket.php?id=';
            }
            
            $data[] = array(
                "0" => (($reg->estado == 'Aceptado') ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idventa . ');"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular(' . $reg->idventa . ');"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning" onclick="mostrar(' . $reg->idventa . ');"><i class="fa fa-eye"></i></button>').
                    '<a href="'.$url.$reg->idventa.'" target="_blank"><button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                "1" => $reg->fecha,
                "2" => $reg->cliente,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
                "6" => $reg->impuesto,
                "7" => $reg->total_venta,
                "8" => $reg->estado == 'Aceptado' ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>'
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
    case 'selectCliente':
        require_once '../modelos/Persona.php';
        $persona = new Persona();
        $rpta = $persona->listarc();

//        echo '<option value="Consumidor Final">Consumidor Final</option>';
        while ($reg = $rpta->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;
    case 'listarArticulos':
        require_once '../modelos/Articulo.php';
        $articulo = new Articulo();
        $rpta = $articulo->listarActivadosVenta();
        $data = Array();

        while ($row = $rpta->fetch_object()) {
            $data[] = array(
                '0' => '<button class="btn btn-warning" onclick="agregarDetalle('.$row->idarticulo. ',\''.$row->nombre.'\','.$row->precio_venta.');"><i class="fa fa-plus"></i></button>',
                '1' => $row->nombre,
                '2' => $row->categoria,
                '3' => $row->codigo,
                '4' => $row->stock,
                '5' => $row->precio_venta,
                '6' => '<img src="../files/articulos/' . $row->imagen . '" height="50px" width="50px"></img>',
            );
        }

        $result = array(
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data
        );
        echo json_encode($result);
    break;
}
    
//}else{
//    echo 'Llene todos los campos';
//}



