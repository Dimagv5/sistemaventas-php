<?php
//incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';
    
class Venta
{
    //implementamos nuestro constructor
    public function __construct(){
        
    }
    
    //implementamos un método para insertar registros
    public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $descuento, $precio_venta){
        $sql = "INSERT INTO venta(idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_venta, estado) VALUES($idcliente, $idusuario, '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_venta', 'Aceptado');";
//        return ejecutarConsulta($sql);
        $idventault = ejecutarConsulta_retornarID($sql);
        
        $posicion = 0;
        $sw = true; //variable bandera para confirmar que todo se ha isertado correctamente
        while($posicion < count($idarticulo)){ 
//            if($permisos[$posicion]->ischecked()){}
            $sqldetalle = "insert into detalle_venta(idventa, idarticulo, cantidad, descuento, precio_venta) values($idventault, $idarticulo[$posicion], $cantidad[$posicion], $descuento[$posicion], $precio_venta[$posicion])"; //guarda el value que tiene el array permisos en la posición 0: $posicion
            ejecutarConsulta($sqldetalle) or $sw = false; //si no se inserta la variable $sw cambia a false 
            $posicion++;
        }
        return $sw;
    }
    
    //implementamos un método para anular ingreso
    public function anular($idventa){
        $sql = "UPDATE venta SET estado = 'Anulado' WHERE idventa = $idventa";
        return ejecutarConsulta($sql);
    }
     
    //implementamos un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa){
        $sql = "SELECT v.idventa, v.idcliente, p.nombre cliente, v.idusuario, u.nombre usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, DATE(v.fecha_hora) fecha, v.impuesto, v.total_venta, v.estado FROM venta v inner join persona p on v.idcliente = p.idpersona inner join usuario u on v.idusuario = u.idusuario WHERE idventa = $idventa";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    //implementamos un método para listar los registros
    public function listar(){
        $sql = "SELECT v.idventa, v.idcliente, p.nombre cliente, v.idusuario, u.nombre usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, DATE(v.fecha_hora) fecha, v.impuesto, v.total_venta, v.estado FROM venta v inner join persona p on v.idcliente = p.idpersona inner join usuario u on v.idusuario = u.idusuario order by v.idventa desc";
        return ejecutarConsulta($sql);
    }
    
    public function listarDetalle($idventa){
        $sql = "SELECT dv.idventa, dv.idarticulo, a.nombre, dv.cantidad, dv.descuento, dv.precio_venta, (dv.cantidad*dv.precio_venta-dv.descuento) subtotal FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa = $idventa";
        return ejecutarConsulta($sql);
    }
    
    public function ventacabecera($idventa){
        $sql = "select v.idventa, v.idcliente, p.nombre cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, v.idusuario, u.nombre usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, date(fecha_hora) fecha, v.impuesto, v.total_venta from venta v inner join persona p on v.idcliente= p.idpersona inner join usuario u on v.idusuario=u.idusuario where v.idventa=$idventa";
        return ejecutarConsulta($sql);
    }
    
    public function ventadetalle($idventa){
        $sql = "select a.nombre articulo, a.codigo, d.cantidad, d.precio_venta, d.descuento, d.cantidad*d.precio_venta-d.descuento subtotal from detalle_venta d inner join articulo a on d.idarticulo= a.idarticulo where d.idventa=$idventa";
        return ejecutarConsulta($sql);
    }
}
