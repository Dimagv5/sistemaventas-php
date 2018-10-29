<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION['nombre'])){
    header('location: login.html');
}
require('header.php');
if($_SESSION['Almacén'] == 0){
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
                        <h1 class="box-title">Artículo <button id="btnAgregar" class="btn btn-success" onclick="mostrarForm(true);"><i class="fa fa-plus-circle"></i> Agregar</button> <a href="../reportes/rptarticulos.php" target="_blank" id="reporte"><button class="btn btn-info">Reporte</button></a></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadodiv">
                        <table id="tblista" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Opciones</th>
                                <th>Categoría</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <th>Opciones</th>
                                <th>Categoría</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </tfoot>
                        </table>      
                    </div>         
                    <div class="panel-body" id="formulariodiv">
                        <form id="formulario" name="formulario" method="POST">
                            
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nombre(*):</label>
                                <input class="form-control" type="hidden" name="idarticulo" id="idarticulo"/>
                                <input class="form-control " type="text" name="nombre" id="nombre" maxlength="100" required/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Categoría(*):</label>
                                <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" required="required">
                                    
                                </select>
                            </div>
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Stock(*):</label>
                                <input class="form-control" type="number" name="stock" id="stock" required="required"/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Descripción:</label>
                                <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="512"/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Imagen:</label>
                                <input class="form-control" type="file" name="imagen" id="imagen"/>
                                <input id="imagenactual" name="imagenactual" type="hidden"/>
                                <img id="mostrarimagen" width="150px" height="150px" style="margin-top: 10px;">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Código:</label>
                                <input class="form-control" type="text" name="codigo" id="codigo" placeholder="Código barras"/>
                                <button type="button" class="btn btn-success" onclick="generarBarcode();" style="margin-top: 10px;">Generar</button>
                                <button type="button" class="btn btn-info" onclick="imprimir();" style="margin-top: 10px;">Imprimir</button>
                                <div id="print">
                                    <svg id="barcode" style="width: 350px; height: 110px;" ></svg>
                                </div>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn btn-danger" type="button" onclick="cancelarForm();"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<?php
}
    require('footer.php');
?>
  <script src="../public/js/JsBarcode.all.min.js"></script>
  <script src="../public/js/jquery.PrintArea.js"></script>
  <script src="scripts/articulo.js"></script>
<?php
ob_end_flush();
?>