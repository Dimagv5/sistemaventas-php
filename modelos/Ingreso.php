<?php
//incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';
    
class Ingreso
{
    //implementamos nuestro constructor
    public function __construct(){
        
    }
    
    //implementamos un método para insertar registros
    public function insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta){
        $sql = "INSERT INTO ingreso(idproveedor, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_compra, estado) VALUES($idproveedor, $idusuario, '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_compra', 'Aceptado');";
//        return ejecutarConsulta($sql);
        $idingresoult = ejecutarConsulta_retornarID($sql);
        
        $posicion = 0;
        $sw = true; //variable bandera para confirmar que todo se ha isertado correctamente
        while($posicion < count($idarticulo)){ 
//            if($permisos[$posicion]->ischecked()){}
            $sqldetalle = "insert into detalle_ingreso(idingreso, idarticulo, cantidad, precio_compra, precio_venta) values($idingresoult, $idarticulo[$posicion], $cantidad[$posicion], $precio_compra[$posicion], $precio_venta[$posicion])"; //guarda el value que tiene el array permisos en la posición 0: $posicion
            ejecutarConsulta($sqldetalle) or $sw = false; //si no se inserta la variable $sw cambia a false 
            $posicion++;
        }
        return $sw;
    }
    
    //implementamos un método para anular ingreso
    public function anular($idingreso){
        $sql = "UPDATE ingreso SET estado = 'Anulado' WHERE idingreso = $idingreso";
        return ejecutarConsulta($sql);
    }
     
    //implementamos un método para mostrar los datos de un registro a modificar
    public function mostrar($idingreso){
        $sql = "SELECT i.idingreso, i.idproveedor, p.nombre proveedor, i.idusuario, u.nombre usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, DATE(i.fecha_hora) fecha, i.impuesto, i.total_compra, i.estado FROM ingreso i inner join persona p on i.idproveedor = p.idpersona inner join usuario u on i.idusuario = u.idusuario WHERE idingreso = $idingreso";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    //implementamos un método para listar los registros
    public function listar(){
        $sql = "SELECT i.idingreso, i.idproveedor, p.nombre proveedor, i.idusuario, u.nombre usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, DATE(i.fecha_hora) fecha, i.impuesto, i.total_compra, i.estado FROM ingreso i inner join persona p on i.idproveedor = p.idpersona inner join usuario u on i.idusuario = u.idusuario order by i.idingreso desc";
        return ejecutarConsulta($sql);
    }
    
    public function listarDetalle($idingreso){
        $sql = "SELECT di.idingreso, di.idarticulo, a.nombre, di.cantidad, di.precio_compra, di.precio_venta FROM detalle_ingreso di inner join articulo a on di.idarticulo=a.idarticulo where di.idingreso = $idingreso";
        return ejecutarConsulta($sql);
    }
}





