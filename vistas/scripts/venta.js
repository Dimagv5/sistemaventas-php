var tabla;
var tablaa;

function ini(){
    limpiar(); //
    mostrarForm(false);
    listar();
    listarArticulos();
    
    $("#formulario").on("submit",function(e){
        guardaryeditar(e);
    });
    //cargamos los items al select Proveedor
    $.post('../ajax/venta.php?op=selectCliente', function(r){
        $('#idcliente').html(r);
        $('#idcliente').selectpicker('refresh');
    });
}

function limpiar(){
    $("#idventa").val("");
    $('#idcliente').val("");
    $('#idcliente').selectpicker('refresh');
    $("#tipo_comprobante").val("");
    $('#tipo_comprobante').selectpicker('refresh');
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $('#impuesto').val("");
    
    $('#total_venta').val("");
    $('#total').html('$ 0,00');
    $('.filas').remove();
    
    //obtenemos la fecha actual
    var now = new Date();
    var day = ('0'+ now.getDate()).slice(-2);
    var month = ('0' + (now.getMonth()+1)).slice(-2);
    var today = now.getFullYear()+'-'+month+'-'+day;
    $("#fecha_hora").val(today);
}

function mostrarForm(flag){
//    limpiar();
    if(flag){
        $("#formulariodiv").show();
        $("#listadodiv").hide();
        $("#btnGuardar").prop("disabled", false); 
        $('#btnAgregar').hide();
//        listarArticulos();
        tablaa.ajax.reload(); //es necesario recargarlo para que se actualice el stock
        $('#btnGuardar').hide();
        $('#btnAgregarArt').show();
        detalles = 0;
    }else{
        $("#formulariodiv").hide();
        $("#listadodiv").show();
        $('#btnAgregar').show();
    }
}

function cancelarForm(){
    limpiar(); //
    mostrarForm(false);
//    tablaa.destroy(); //para que no se muestre error de inicialización de DataTable: DataTables warning: table id={tbarticulos} - Cannot reinitialise DataTable.
}

function listar(){
    tabla = $("#tblista").dataTable(
            {
               "aProcessing" : true, //Activamos el procesamiento del datatables
               "aServerSide" : true, //Paginación y filtrado realizado por el servidor
               dom : "Bfrtip", //Definimos los elementos del control de tabla          
               buttons:[
                   "copyHtml5",
                   "excelHtml5",
                   "csvHtml5",
                   "pdf"
               ],
               "ajax":
                {
                   url : "../ajax/venta.php?op=listar",
                   type: "get",
                   dataType: "json",
                   error: function(e){
                       console.log(e.responseText);
                   }
               },
               "iDestroy": true,
               "iDisplayLength": 5, //Paginación
               "order": [[0,"desc"]]                
            }
            ).DataTable();
}

function guardaryeditar(e){
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false, //que es 
        processData: false, //que es
        success: function(r){
            bootbox.alert(r);
//            limpiar(); //
//            mostrarForm(false);
            cancelarForm();
            tabla.ajax.reload();
        }
    });
}

function mostrar(idventa){
    $.post("../ajax/venta.php?op=mostrar", {idventa:idventa}, function(data, status){
        mostrarForm(true);
        data = JSON.parse(data);
        $("#idventa").val(data.idventa);
        $('#idcliente').val(data.idcliente);
        $('#idcliente').selectpicker('refresh');
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $('#tipo_comprobante').selectpicker('refresh');
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $('#impuesto').val(data.impuesto);
    
//        $('#total_compra').val(data.total_compra);
//        $('#total').html('$ '+data.total_compra);
        
        //ocultar los botones
        $('#btnGuardar').hide();
        $('#btnAgregarArt').hide();
    });
    
    $.post('../ajax/venta.php?op=listarDetalle&id='+idventa, function(r){  
        $('#tbdetalles').html(r);
    });
}

function anular(idventa){
    bootbox.confirm("¿Está seguro que desea anular la venta?", function(respuesta){
        if(respuesta){
            $.post("../ajax/venta.php?op=anular", {idventa:idventa}, function(data){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

function listarArticulos(){
    tablaa = $("#tbarticulos").dataTable(
            {
               "aProcessing" : true, //Activamos el procesamiento del datatables
               "aServerSide" : true, //Paginación y filtrado realizado por el servidor
               dom : "Bfrtip", //Definimos los elementos del control de tabla          
               buttons:[
                   
               ],
               "ajax":
                {
                   url : "../ajax/venta.php?op=listarArticulos",
                   type: "get",
                   dataType: "json",
                   error: function(e){
                       console.log(e.responseText);
                   }
               },
               "iDestroy": true,
               "iDisplayLength": 5, //Paginación
               "order": [[0,"desc"]]                
            }
            ).DataTable();
}

var impuesto = 12;
var cont = 0;
var detalles = 0;

//$('#guardar').hide();
$('#btnGuardar').hide();
$('#tipo_comprobante').change(definirImpuesto);

function definirImpuesto(){
    var tipo_comprobante = $('#tipo_comprobante option:selected').text();
    if(tipo_comprobante == 'FACTURA'){
        $('#impuesto').val(impuesto);
    }else{
        $('#impuesto').val("0");
    }   
}

function agregarDetalle(idarticulo, articulo, precio_venta){
    var cantidad = 1;
//    var precio_venta = 1;
    var descuento = 0;
    
    if(idarticulo != ""){
        subtotal = cantidad*precio_venta-descuento;
        var fila = '<tr class="filas" id="fila'+cont+'">'
                +'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+');">x</button></td>'
                +'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'"/>'+articulo+'</td>'
                +'<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"/></td>'
                +'<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"/></td>'
                +'<td><input type="number" name="descuento[]" id="descuento[]" value="'+descuento+'"/></td>'
                +'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'
                +'<td><button type="button" class="btn btn-info" onclick="modificarSubtotales();"><i class="fa fa-refresh"></i></button></td>'
                +'</tr>';
        cont++;
        detalles = detalles + 1;
        $('#tbdetalles').append(fila);
        modificarSubtotales(); //para que al eliminar detalle no haya problema en TOTAL        
    }else{
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubtotales(){
    var cant = document.getElementsByName('cantidad[]');
    var prec = document.getElementsByName('precio_venta[]');
    var desc = document.getElementsByName('descuento[]');
    var sub = document.getElementsByName('subtotal');
    
    for(var i=0; i<cant.length; i++){
        var cantInp = cant[i];
        var precInp = prec[i];
        var descInp = desc[i];
        var subInp = sub[i];
        
        subInp.value = cantInp.value * precInp.value - descInp.value; //por que .value? porque son números
        document.getElementsByName('subtotal')[i].innerHTML = subInp.value;
//        alert(subInp.value);
    }
    calcularTotal();    
}

function calcularTotal(){
    var sub = document.getElementsByName('subtotal');
    var total = 0.0;
    
    for(var i=0; i<sub.length; i++){
        total += sub[i].value;
//        total += document.getElementsByName('subtotal')[i].value;
    }
//    alert(total);
    $('#total').html('$ '+total);
    $('#total_venta').val(total);
    evaluar();
}

function evaluar(){
    if(detalles > 0){
        $('#btnGuardar').show();
    }else{
        $('#btnGuardar').hide();
        cont = 0; //
    }
}

function eliminarDetalle(indice){
    $('#fila'+indice).remove();
    --detalles;
    
//    var sub = document.getElementsByName('subtotal');
    calcularTotal();
}

ini();




