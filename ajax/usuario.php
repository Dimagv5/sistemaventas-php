<?php
session_start();
require_once('../modelos/Usuario.php');

$usuario = new Usuario();

$idusuario = isset($_POST['idusuario']) ? limpiarCadena($_POST['idusuario']) : "";
$nombre = isset($_POST['nombre']) ? limpiarCadena($_POST['nombre']) : "";
$tipo_documento = isset($_POST['tipo_documento']) ? limpiarCadena($_POST['tipo_documento']) : "";
$num_documento = isset($_POST['num_documento']) ? limpiarCadena($_POST['num_documento']) : "";
$direccion = isset($_POST['direccion']) ? limpiarCadena($_POST['direccion']) : "";
$telefono = isset($_POST['telefono']) ? limpiarCadena($_POST['telefono']) : "";
$email = isset($_POST['email']) ? limpiarCadena($_POST['email']) : "";
$cargo = isset($_POST['cargo']) ? limpiarCadena($_POST['cargo']) : "";
$login = isset($_POST['login']) ? limpiarCadena($_POST['login']) : "";
$clave = isset($_POST['clave']) ? limpiarCadena($_POST['clave']) : "";
$imagen = isset($_POST['imagen']) ? limpiarCadena($_POST['imagen']) : "";

switch($_GET['op']){
    case 'guardaryeditar':
        if(file_exists($_FILES['imagen']['tmp_name']) || is_uploaded_file($_FILES['imagen']['tmp_name'])){
            $ext = explode('.', $_FILES['imagen']['name']);
            if($_FILES['imagen']['type']== 'image/jpg' || $_FILES['imagen']['type']=='image/jpeg' || $_FILES['imagen']['type']=='image/png'){
                
                $imagen = round(microtime(true)).'.'. end($ext);
                
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/usuarios/'.$imagen);
            }
        }else{
            $imagen = $_POST['imagenactual'];
        }
        
        //Hash SHA256 encriptar contraseña/clave
        $clavehash = hash('sha256', $clave);
        
        if(empty($idusuario)){
            $rpta = $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permisosch']);
            echo $rpta ? 'Usuario registrado' : 'No se pudo registrar todos los datos';
        }else{
            $rpta = $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permisosch']);
            echo $rpta ? 'Usuario actualizado' : 'Usuario no se pudo actualizar';            
        }
    break; 
    case 'desactivar':
        $rpta = $usuario->desactivar($idusuario);
        echo $rpta ? 'Usuario desactivado' : 'Usuario no se pudo desactivar';
    break;  
    case 'activar':
        $rpta = $usuario->activar($idusuario);
        echo $rpta ? 'Usuario activado' : 'Usuario no se pudo activar';
    break;
    case 'mostrar':
        $rpta = $usuario->mostrar($idusuario);
        echo json_encode($rpta);
    break;
    case 'listar':
        $rpta = $usuario->listar();
        $data = Array();
        
        while($row = $rpta->fetch_object()){
            $data[] = array(
                '0'=>$row->condicion ? '<button class="btn btn-warning" onclick="mostrar('.$row->idusuario.');"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="desactivar('.$row->idusuario.');"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$row->idusuario.');"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-primary" onclick="activar('.$row->idusuario.');"><i class="fa fa-check"></i></button>',
                '1'=>$row->nombre,
                '2'=>$row->tipo_documento,
                '3'=>$row->num_documento,
                '4'=>$row->telefono,
                '5'=>$row->email,
                '6'=>$row->cargo,
                '7'=>$row->login,
                '8'=>'<img src="../files/usuarios/'.$row->imagen.'" height="50px" width="50px"></img>',
                '9'=>$row->condicion ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span'
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
    case 'permisos':
        //obtenemos todos los permisos de la tabla permisos
        require_once '../modelos/Permiso.php';
        $permiso = new Permiso();
        $resultado = $permiso->listar();
        
        $idu = $_GET['id'];
        $marcados = $usuario->listarmarcados($idu);
        $valores = array();
        while($fila = $marcados->fetch_object()){
            array_push($valores, $fila->idpermiso);
        }
        
        //mostramos la lista de permisos en la vista y si están o no marcados
        while($reg = $resultado->fetch_object()){ //el array permisosop se obtiene al marcar el checkbox
            $sw = in_array($reg->idpermiso, $valores) ? 'checked' : "";
            echo '<li><input type="checkbox" name="permisosch[]" '.$sw.' value="'.$reg->idpermiso.'"/>'.$reg->nombre.'</li>';
        }
    break;    
    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];
        
        //para comparar encriptamos a contraseña a Hash SHA256
//        $clavehash = hash('SHA256', $clavea);
        $clavehash = hash('sha256', $clavea);
        
        $registro = $usuario->verificar($logina, $clavehash);
        
//        $fetch = null;
        $fetch = $registro->fetch_object();
        //si se encuentra una conincidencia creamos las sesiones 
        if(isset($fetch)){
//            session_start(); //ubicado aqui no funciona al querer destruir la sesión
            //declaramos las variables de sesión
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            $_SESSION['cargo'] = $fetch->cargo;
            
            $marcados = $usuario->listarmarcados($fetch->idusuario);
            $valores = array();
            while($fila = $marcados->fetch_object()){
                array_push($valores, $fila->idpermiso);     
            }
//            Por cada permiso de la tabla permiso:
//            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
//            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
//          Otra forma de hacer lo anterior, de manera dinámica:
            require '../modelos/Permiso.php';
            $permiso = new Permiso();
            $permisos = $permiso->listar();
            while($registro = $permisos->fetch_object()){
                $_SESSION[$registro->nombre] = in_array($registro->idpermiso, $valores) ? 1 : 0;
//                in_array($registro->idpermiso, $valores) ? $_SESSION[$registro->nombre] = 1 : $_SESSION[$registro->nombre] = 0;
            }
            
        }
        
        echo json_encode($fetch);
    break;    
    case 'salir':
        session_unset(); //limpiamos las variables de sesión
        session_destroy(); //destruimos la sesión y todas sus variables
        header('location: ../index.php'); //redireccionamos al login
    break;    
}   



