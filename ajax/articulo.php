<?php
require('../modelos/Articulo.php');

$articulo = new Articulo();

$idarticulo = isset($_POST['idarticulo']) ? limpiarCadena($_POST['idarticulo']) : "";
$idcategoria = isset($_POST['idcategoria']) ? limpiarCadena($_POST['idcategoria']) : "";
$codigo = isset($_POST['codigo']) ? limpiarCadena($_POST['codigo']) : "";
$nombre = isset($_POST['nombre']) ? limpiarCadena($_POST['nombre']) : "";
$descripcion = isset($_POST['descripcion']) ? limpiarCadena($_POST['descripcion']) : "";
$stock = isset($_POST['stock']) ? limpiarCadena($_POST['stock']) : "";
$imagen = isset($_POST['imagen']) ? limpiarCadena($_POST['imagen']) : "";

switch($_GET['op']){
    case 'guardaryeditar':
        if(file_exists($_FILES['imagen']['tmp_name']) || is_uploaded_file($_FILES['imagen']['tmp_name'])){
            $ext = explode('.', $_FILES['imagen']['name']);
            if($_FILES['imagen']['type']== 'image/jpg' || $_FILES['imagen']['type']=='image/jpeg' || $_FILES['imagen']['type']=='image/png'){
                
                $imagen = round(microtime(true)).'.'. end($ext);
                
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/articulos/'.$imagen);
            }
        }else{
            $imagen = $_POST['imagenactual'];
        }
        
        if(empty($idarticulo)){
            $rpta = $articulo->insertar($idcategoria, $nombre, $codigo, $descripcion, $stock, $imagen);
            echo $rpta ? 'Artículo registrado' : 'Artículo no se pudo registrar';
        }else{
            $rpta = $articulo->editar($idarticulo, $idcategoria, $nombre, $codigo, $descripcion, $stock, $imagen);
            echo $rpta ? 'Artículo actualizado' : 'Artículo no se pudo actualizar';            
        }
    break; 
    case 'desactivar':
        $rpta = $articulo->desactivar($idarticulo);
        echo $rpta ? 'Artículo desactivado' : 'Artículo no se pudo desactivar';
    break;  
    case 'activar':
        $rpta = $articulo->activar($idarticulo);
        echo $rpta ? 'Artículo activado' : 'Artículo no se pudo activar';
    break;
    case 'mostrar':
        $rpta = $articulo->mostrar($idarticulo);
        echo json_encode($rpta);
    break;
    case 'listar':
        $rpta = $articulo->listar();
        $data = Array();
        
        while($row = $rpta->fetch_object()){
            $data[] = array(
                '0'=>$row->condicion ? '<button class="btn btn-warning" onclick="mostrar('.$row->idarticulo.');"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="desactivar('.$row->idarticulo.');"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$row->idarticulo.');"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="activar('.$row->idarticulo.');"><i class="fa fa-check"></i></button>',
                '1'=>$row->categoria,
                '2'=>$row->codigo,
                '3'=>$row->nombre,
                '4'=>$row->descripcion,
                '5'=>$row->stock,
                '6'=>'<img src="../files/articulos/'.$row->imagen.'" height="50px" width="50px"></img>',
                '7'=>$row->condicion ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span'
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
    case 'selectCategoria':
        require_once '../modelos/Categoria.php';
        $categoria = new Categoria();
        
        $resp = $categoria->seleccionar();
        echo '<option value="">Seleccione</option>';
        while($reg = $resp->fetch_object()){
            echo '<option value="'.$reg->idcategoria.'">'.$reg->nombre.'</option>';
        }
    break;
}   


