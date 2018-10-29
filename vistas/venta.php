<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION['nombre'])){
    header('location: login.html');
}
require('header.php');
if($_SESSION['Ventas'] == 0){
    require 'noacceso.php';
}
else{
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Venta <button id="btnAgregar" class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadodiv">
                        <table id="tblista" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Opciones</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <th>Opciones</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tfoot>
                        </table>      
                    </div>         
                    <div class="panel-body"  id="formulariodiv">
                        <form id="formulario" name="formulario" method="POST">
                            <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <label>Cliente(*):</label>
                                <input class="form-control" type="hidden" name="idventa" id="idventa"/>
                                <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                                    
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Fecha(*):</label>
                                <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tipo Comprobante(*):</label>
                                <select id="tipo_comprobante" name="tipo_comprobante" class="form-control selectpicker" required>
                                    <option value="">Seleccione</option>
                                    <option value="Factura">FACTURA</option>
                                    <option value="Ticket">TICKET</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Serie:</label>
                                <input class="form-control" type="text" name="serie_comprobante" id="serie_comprobante" maxlength="7"/>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Número(*):</label>
                                <input class="form-control" type="text" name="num_comprobante" id="num_comprobante" maxlength="10" required/>
                            </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Impuesto(*):</label>
                                <input class="form-control" type="text" name="impuesto" id="impuesto" required/>
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a data-toggle="modal" href="#myModal">
                                    <button id="btnAgregarArt" type="button" class="btn btn-primary" ><span class="fa fa-plus"></span> Agregar Artículo</button>
                                </a>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                <table id="tbdetalles" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color: #A9D0F5;">
                                        <th>Opciones</th>
                                        <th>Artículo</th>
                                        <th>Cantidad</th>
                                        <th>Precio Venta</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    
                                    <tfoot>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><h4 id="total">$ 0,00</h4><input type="hidden" name="total_venta" id="total_venta"/></th>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div id="guardar" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn btn-danger" type="button" id="btnCancelar" onclick="cancelarForm();"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>                   
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  
  <!--Modal-->
  <div id="myModal" class="modal fade" role="dialog" tabindex="-1" aria-labelledby= "myModalLabel" aria-hidden= "true">
      <div class="modal-dialog" style="width: 65% !important;">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden= "true">&times;</button>
                  <h4 class="modal-title">Seleccione un Artículo</h4>
              </div>
              <div class="modal-body">
                  <table id="tbarticulos" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Stock</th>
                        <th>Precio Venta</th>
                        <th>Imagen</th>
                      </thead>
                      <tbody>
                          
                      </tbody>
                      <tfoot>
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Stock</th>
                        <th>Precio Venta</th>
                        <th>Imagen</th>
                      </tfoot>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
      
  </div>
<?php
}
    require('footer.php');
?>

<script src="scripts/venta.js"></script>
<?php
//session_destroy();
ob_end_flush();
?>


