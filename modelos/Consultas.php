<?php
require_once('../config/Conexion.php');

class Consultas
{
    
    public function __construct() {
        
    }
    
    public function comprasfecha($fecha_inicio, $fecha_fin){
        $sql = "select i.idingreso, DATE(i.fecha_hora) fecha, p.nombre proveedor, u.nombre usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.impuesto, i.total_compra, i.estado from ingreso i inner join usuario u on i.idusuario = u.idusuario inner join persona p on i.idproveedor = p.idpersona where DATE(i.fecha_hora)>= '$fecha_inicio' and DATE(i.fecha_hora) <= '$fecha_fin'";
        return ejecutarConsulta($sql);     
    }
    
    public function ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente){
        $sql = "select v.idventa, DATE(v.fecha_hora) fecha, p.nombre cliente, u.nombre usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.impuesto, v.total_venta, v.estado from venta v inner join usuario u on v.idusuario = u.idusuario inner join persona p on v.idcliente = p.idpersona where DATE(v.fecha_hora)>= '$fecha_inicio' and DATE(v.fecha_hora) <= '$fecha_fin' and v.idcliente='$idcliente'";
        return ejecutarConsulta($sql);     
    }
    
    public function totalcomprashoy(){
        $sql = "select ifnull(sum(total_compra),0) total_compra from ingreso where DATE(fecha_hora)=curdate()";
        return ejecutarConsulta($sql);
    }
    
    public function totalventashoy(){
        $sql = "select ifnull(sum(total_venta),0) total_venta from venta where DATE(fecha_hora)=curdate()";
        return ejecutarConsulta($sql);
    }
    
    public function comprasultimos_10dias(){
        $sql = "select concat(day(fecha_hora),'-', month(fecha_hora)) fecha, sum(total_compra) total_compra from ingreso group by fecha_hora order by fecha_hora desc limit 0,10";
        return ejecutarConsulta($sql);
    }
    
    public function ventasultimos_12meses(){
        $sql = "select date_format(fecha_hora, '%M') fecha, sum(total_venta) total_venta from venta group by month(fecha_hora) order by month(fecha_hora) desc limit 0,12";
        return ejecutarConsulta($sql);
    }
}