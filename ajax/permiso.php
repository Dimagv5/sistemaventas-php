<?php
require('../modelos/Permiso.php');

$permiso = new Permiso();

//if($nombre != "" && $descripcion != ""){

    switch($_GET["op"]){
        case 'listar':
            $rpta = $permiso->listar();
            //Vamos a declarar un array
            $data = Array();
            
            while($reg = $rpta->fetch_object()){
                $data[] = array(
//                    "0" => $reg->idpermiso,
                    "0" => $reg->nombre
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




