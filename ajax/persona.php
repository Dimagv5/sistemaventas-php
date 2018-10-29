<?php
require('../modelos/Persona.php');

$persona = new Persona();

$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
$nombre = isset($_POST['nombre']) ? limpiarCadena($_POST['nombre']) : "";
$tipo_persona = isset($_POST['tipo_persona']) ? limpiarCadena($_POST['tipo_persona']) : "";
$tipo_documento = isset($_POST['tipo_documento']) ? limpiarCadena($_POST['tipo_documento']) : "";
$num_documento = isset($_POST['num_documento']) ? limpiarCadena($_POST['num_documento']) : "";
$direccion = isset($_POST['direccion']) ? limpiarCadena($_POST['direccion']) : "";
$telefono = isset($_POST['telefono']) ? limpiarCadena($_POST['telefono']) : "";
$email = isset($_POST['email']) ? limpiarCadena($_POST['email']) : "";


//if($nombre != "" && $descripcion != ""){

    switch($_GET["op"]){
        case 'guardaryeditar':
            if(empty($idpersona)){
                $rpta = $persona->insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
                echo $rpta ? "Persona guardada" : "No se pudo guardar persona";
            }else{
                $rpta = $persona->editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
                echo $rpta ? "Persona actualizada" : "No se pudo actualizar persona";
            }    
        break;
        case 'eliminar':
            $rpta = $persona->eliminar($idpersona);
            echo $rpta ? "Persona eliminada exitosamente" : "No se pudo eliminar persona";
        break;
        case 'mostrar':
            $rpta = $persona->mostrar($idpersona);
            //Codificar el resultado utilizando json
            echo json_encode($rpta);
        break;
        case 'listarp':
            $rpta = $persona->listarp();
            //Vamos a declarar un array
            $data = Array();
            
            while($reg = $rpta->fetch_object()){
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.');"><i class="fa fa-pencil"></i></button>'.
                           ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.');"><i class="fa fa-trash"></i></button>',
                    "1" => $reg->nombre,
                    "2" => $reg->tipo_documento,
                    "3" => $reg->num_documento,
                    "4" => $reg->telefono,
                    "5" => $reg->email 
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
        case 'listarc':
            $rpta = $persona->listarc();
            //Vamos a declarar un array
            $data = Array();
            
            while($reg = $rpta->fetch_object()){
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.');"><i class="fa fa-pencil"></i></button>'.
                           ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.');"><i class="fa fa-trash"></i></button>',
                    "1" => $reg->nombre,
                    "2" => $reg->tipo_documento,
                    "3" => $reg->num_documento,
                    "4" => $reg->telefono,
                    "5" => $reg->email 
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



