<?php
//incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';
    
class Usuario
{
    //implementamos nuestro constructor
    public function __construct(){
        
    }
    
    //implementamos un método para insertar registros
    public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos){
        $sql = "INSERT INTO usuario(nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion) VALUES('$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', '$cargo', '$login', '$clave', '$imagen', 1);";
//        return ejecutarConsulta($sql);
        $idusuarioult = ejecutarConsulta_retornarID($sql);
        
        $posicion = 0;
        $sw = true; //variable bandera para confirmar que todo se ha isertado correctamente
        while($posicion < count($permisos)){ //variable $permisos almacena todos los checkbox que han sido marcados
//            if($permisos[$posicion]->ischecked()){}
            $sqlp = "insert into usuario_permiso(idusuario, idpermiso) values($idusuarioult, $permisos[$posicion])"; //guarda el value que tiene el array permisos en la posición 0: $posicion
            ejecutarConsulta($sqlp) or $sw = false; //si no se inserta la variable $sw cambia a false 
            $posicion++;
        }
        return $sw;
    }
    
    //implementamos un método para editar registros
    public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos){
        $sql = "UPDATE usuario SET nombre = '$nombre', tipo_documento = '$tipo_documento', num_documento = '$num_documento', direccion = '$direccion', telefono = '$telefono', email = '$email', cargo = '$cargo', login = '$login', clave = '$clave', imagen = '$imagen' WHERE idusuario = $idusuario";
        ejecutarConsulta($sql);
        
        $sqldelete = "delete from usuario_permiso where idusuario = $idusuario";
        ejecutarConsulta($sqldelete);
        
        $posicion = 0;
        $sw = true; //variable bandera para confirmar que todo se ha isertado correctamente
        while($posicion < count($permisos)){ //variable $permisos almacena todos los checkbox que han sido marcados
//            if($permisos[$posicion]->ischecked()){}
            $sqlp = "insert into usuario_permiso(idusuario, idpermiso) values($idusuario, $permisos[$posicion])"; //guarda el value que tiene el array permisos en la posición 0: $posicion
            ejecutarConsulta($sqlp) or $sw = false; //si no se inserta la variable $sw cambia a false 
            $posicion++;
        }
        return $sw;
    }
    
    //implementamos un método para desactivar usuarios
    public function desactivar($idusuario){
        $sql = "UPDATE usuario SET condicion = 0 WHERE idusuario = $idusuario";
        return ejecutarConsulta($sql);
    }
    
    //implementamos un método para activar usuarios
    public function activar($idusuario){
        $sql = "UPDATE usuario SET condicion = 1 WHERE idusuario = $idusuario";
        return ejecutarConsulta($sql);
    }
     
    //implementamos un método para mostrar los datos de un registro a modificar
    public function mostrar($idusuario){
        $sql = "SELECT * FROM usuario WHERE idusuario = $idusuario";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    //implementamos un método para listar los registros
    public function listar(){
        $sql = "SELECT * FROM usuario";
        return ejecutarConsulta($sql);
    }
    
    public function listarmarcados($idusuario){
        $sql = "select * from usuario_permiso where idusuario = '$idusuario'"; //entr comillas para que funcione al recibir el parametro id vacio que es cadena
        return ejecutarConsulta($sql);
    }
    
    public function verificar($login, $clave){
        $sql = "select idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login from usuario where login = '$login' and clave = '$clave' and condicion = 1";
        return ejecutarConsulta($sql);
    }

}


