<?php
//incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';
    
class Permiso
{
    //implementamos nuestro constructor
    public function __construct(){
        
    }
    
    //implementamos un método para listar los registros
    public function listar(){
        $sql = "SELECT * FROM permiso";
        return ejecutarConsulta($sql);
    }
    
}


