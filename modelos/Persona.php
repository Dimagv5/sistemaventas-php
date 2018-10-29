<?php
//incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';
    
class Persona
{
    //implementamos nuestro constructor
    public function __construct(){
        
    }
    
    //implementamos un método para insertar registros
    public function insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email){
        $sql = "INSERT INTO persona(tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email) VALUES('$tipo_persona', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email');";
        return ejecutarConsulta($sql);
    }
    
    //implementamos un método para editar registros
    public function editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email){
        $sql = "UPDATE persona SET tipo_persona='$tipo_persona',nombre = '$nombre', tipo_documento = '$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email'  WHERE idpersona = $idpersona";
        return ejecutarConsulta($sql);
    }
    
    //implementamos un método para desactivar categorias
    public function eliminar($idpersona){
        $sql = "delete from persona WHERE idpersona = $idpersona";
        return ejecutarConsulta($sql);
    }
    
     
    //implementamos un método para mostrar los datos de un registro a modificar
    public function mostrar($idpersona){
        $sql = "SELECT * FROM persona WHERE idpersona = $idpersona";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    //implementamos un método para listar los registros de los Proveedores
    public function listarp(){
        $sql = "SELECT * FROM persona WHERE tipo_persona = 'Proveedor'";
        return ejecutarConsulta($sql);
    }
    
    //implementamos un método para listar los registros de los clientes
    public function listarc(){
        $sql = "SELECT * FROM persona WHERE tipo_persona = 'Cliente'";
        return ejecutarConsulta($sql);
    }
    
}



