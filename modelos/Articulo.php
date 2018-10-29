<?php
require('../config/Conexion.php');

class Articulo{
    
    public function __construct(){
    
} 
    
    public function insertar($idcategoria, $nombre, $codigo, $descripcion, $stock, $imagen){
        $sql = "INSERT INTO articulo(idcategoria, nombre, codigo, descripcion, stock, imagen, condicion) VALUES($idcategoria, '$nombre', '$codigo', '$descripcion', $stock, '$imagen', 1)";
        return ejecutarConsulta($sql);
    }

    public function editar($idarticulo, $idcategoria, $nombre, $codigo, $descripcion, $stock, $imagen){
        $sql = "UPDATE articulo SET idcategoria = $idcategoria, nombre = '$nombre', codigo = '$codigo', descripcion = '$descripcion', stock = $stock, imagen = '$imagen', condicion = 1 WHERE idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($idarticulo){
        $sql = "UPDATE articulo SET condicion = 0 WHERE idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);
    }
    
    public function activar($idarticulo){
        $sql = "UPDATE articulo SET condicion = 1 WHERE idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);
    }
    
    public function mostrar($idarticulo){
        $sql = "SELECT * FROM articulo WHERE idarticulo = $idarticulo";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function listar(){
        $sql = "SELECT a.idarticulo, a.codigo, c.nombre AS categoria, a.nombre, a.descripcion, a.stock, a.imagen, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria = c.idcategoria";
        return ejecutarConsulta($sql);
    }
    
    public function listarActivados(){
        $sql = "SELECT a.idarticulo, a.codigo, c.nombre AS categoria, a.nombre, a.descripcion, a.stock, a.imagen, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria = c.idcategoria where a.condicion = 1";
        return ejecutarConsulta($sql);
    }
    
    public function listarActivadosVenta(){
        $sql = "SELECT a.idarticulo, a.codigo, c.nombre AS categoria, a.nombre, a.descripcion, a.stock, (select precio_venta from detalle_ingreso where idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta, a.imagen, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria = c.idcategoria where a.condicion = 1";
        return ejecutarConsulta($sql);
    }
}



