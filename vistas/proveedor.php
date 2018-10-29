<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION['nombre'])){
    header('location: login.html');
}
require('header.php');
if($_SESSION['Compras'] == 0){
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
                        <h1 class="box-title">Proveedor <button id="btnAgregar" class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadodiv">
                        <table id="tblista" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Num Documento</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Número</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                            </tfoot>
                        </table>      
                    </div>         
                    <div class="panel-body" style="height: 400px;" id="formulariodiv">
                        <form id="formulario" name="formulario" method="POST">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nombre:</label>
                                <input class="form-control" type="hidden" name="idpersona" id="idpersona"/>
                                <input class="form-control" type="hidden" name="tipo_persona" id="tipo_persona" value="Proveedor"/>
                                <input class="form-control " type="text" name="nombre" id="nombre" maxlength="100" required/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tipo Documento:</label>
                                <select class="form-control selectpicker" id="tipo_documento" name="tipo_documento" required="">
                                    <option value="">Seleccionar</option>
                                    <option value="RUC">Ruc</option>
                                    <option value="Cédula">Cédula</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Número Documento:</label>
                                <input class="form-control" type="text" name="num_documento" id="num_documento" maxlength="20" required=""/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Dirección:</label>
                                <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70"/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Teléfono:</label>
                                <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20"/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Email:</label>
                                <input class="form-control" type="email" name="email" id="email" maxlength="50"/>
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

<script src="scripts/proveedor.js"></script>
<?php
ob_end_flush();
?>