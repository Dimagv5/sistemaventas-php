var tabla;

function ini(){
    mostrarForm(false);
    listar();
    
    $("#formulario").on("submit",function(e){
        guardaryeditar(e);
    });
    
    //cargamos los items al select categoria
    $.post('../ajax/articulo.php?op=selectCategoria', function(r){
        $("#idcategoria").html(r);
        $("#idcategoria").selectpicker('refresh');
    });
}

function limpiar(){
    $("#idarticulo").val("");
    $('#idcategoria').val("");
    $('#idcategoria').selectpicker('refresh');
    $("#nombre").val("");
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $('#mostrarimagen').attr('src', ''); 
    $('#mostrarimagen').hide();
    $('#imagen').val("");
    $('#imagenactual').val(""); //no me parece tan necesario pero se debe de
    $('#barcode').hide();
}

function mostrarForm(flag){
    limpiar();
    if(flag){
        $("#formulariodiv").show();
        $("#listadodiv").hide();
        $("#btnGuardar").prop("disabled", false); 
        $('#btnAgregar').hide();
        $('#reporte').hide();
    }else{
        $("#formulariodiv").hide();
        $("#listadodiv").show();
        $('#btnAgregar').show();
    }
}

function cancelarForm(){
//    limpiar();
    mostrarForm(false);
    $('#reporte').show();
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
                   url : "../ajax/articulo.php?op=listar",
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
        url: "../ajax/articulo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false, //que es 
        processData: false, //que es
        success: function(r){
            bootbox.alert(r);
//            limpiar();
            mostrarForm(false);
            tabla.ajax.reload();
        }
    });
}

function mostrar(idarticulo){
    $.post("../ajax/articulo.php?op=mostrar", {idarticulo:idarticulo}, function(data, status){
        mostrarForm(true);
        data = JSON.parse(data);
        $("#idarticulo").val(data.idarticulo);
        $('#idcategoria').val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');
        $("#nombre").val(data.nombre);
        $("#codigo").val(data.codigo);
        $("#descripcion").val(data.descripcion);
        $("#stock").val(data.stock);
        $('#mostrarimagen').attr('src', '../files/articulos/'+data.imagen); 
        $('#mostrarimagen').show();
        $('#imagenactual').val(data.imagen);
//        $('#imagen').val(data.imagen); //no muestra nada
        generarBarcode();
    });
}

function desactivar(idarticulo){
    bootbox.confirm("¿Está seguro que desea desactivar el artículo?", function(respuesta){
        if(respuesta){
            $.post("../ajax/articulo.php?op=desactivar", {idarticulo:idarticulo}, function(data){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idarticulo){
    bootbox.confirm("¿Está seguro que desea activar el artículo?", function(respuesta){
        if(respuesta){
            $.post("../ajax/articulo.php?op=activar", {idarticulo:idarticulo}, function(data){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

function generarBarcode(){
    var codigo = $('#codigo').val();
    JsBarcode('#barcode', codigo);
    $('#barcode').show();
}

function imprimir(){
    $('#print').printArea();
}

ini();

