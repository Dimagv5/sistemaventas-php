<?php
require('../modelos/Categoria.php');

$categoria = new Categoria();

$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST['nombre']) ? limpiarCadena($_POST['nombre']) : "";
$descripcion = isset($_POST['descripcion']) ? limpiarCadena($_POST['descripcion']) : "";

//if($nombre != "" && $descripcion != ""){

    switch($_GET["op"]){
        case 'guardaryeditar':
            if(empty($idcategoria)){
                $rpta = $categoria->insertar($nombre, $descripcion);
                echo $rpta ? "Categoría guardada" : "No se pudo guardar categoría";
            }else{
                $rpta = $categoria->editar($idcategoria, $nombre, $descripcion);
                echo $rpta ? "Categoría actualizada" : "No se pudo actualizar categoría";
            }    
        break;
        case 'activar':
            $rpta = $categoria->activar($idcategoria);
            echo $rpta ? "Registro activado exitosamente" : "No se pudo activar registro";
        break;
        case 'desactivar':
            $rpta = $categoria->desactivar($idcategoria);
            echo $rpta ? "Registro desactivado exitosamente" : "No se pudo desactivar registro";
        break;
        case 'mostrar':
            $rpta = $categoria->mostrar($idcategoria);
            //Codificar el resultado utilizando json
            echo json_encode($rpta);
        break;
        case 'listar':
            $rpta = $categoria->listar();
            //Vamos a declarar un array
            $data = Array();
            
            while($reg = $rpta->fetch_object()){
                $data[] = array(
                    "0" => $reg->condicion ? '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.');"><i class="fa fa-pencil"></i></button>'.
                           ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.');"><i class="fa fa-close"></i></button>': '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.');"><i class="fa fa-pencil"></i></button>'.
                           ' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.');"><i class="fa fa-check"></i></button>',
                    "1" => $reg->nombre,
                    "2" => $reg->descripcion,
                    "3" => $reg->condicion ? '<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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
    
//}else{
//    echo 'Llene todos los campos';
//}


