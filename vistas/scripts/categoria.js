var tabla;

//función que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar(); 
    
    $('#formulario').on('submit', function(e){
        guardaryeditar(e);
    });
}

//función limpiar campos del formulario
function limpiar(){
    $("#idcategoria").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
}

//función mostrar formulario
function mostrarForm(flag){
    limpiar();
    if(flag){
        $("#formulariodiv").show();
        $("#listadodiv").hide();
        $("#btnGuardar").prop("disabled", false); //duda por q habilitarlo
        $('#btnAgregar').hide();
    }else{
        $("#formulariodiv").hide();
        $("#listadodiv").show(); //listar();
        $('#btnAgregar').show();
    }
}

//función cancelar formulario
function cancelarForm(){
    limpiar();
    mostrarForm(false);
}

//función listar en tabla
function listar(){
    tabla = $("#tblista").dataTable(
            {
                'aProcessing' : true, //Activamos el procesamiento del datatables
                'aServerSide' : true, //Paginación y filtrado realizado por el servidor
                dom : 'Bfrtip', //Definimos los elementos del control de tabla
                buttons:
                    [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdf'
                    ],
                'ajax':
                    ({
                        url : '../ajax/categoria.php?op=listar',
                        dataType : 'json',
                        type : 'GET', //o POST
                        error : function(e){
                            console.log(e.responseText);
                        }
                    }),
                'iDestroy': true,
                'iDisplayLength': 5, //Paginación
                'order': [[0, "desc"]] //ordenar (columna, orden)
            }
            ).DataTable();
}

function guardaryeditar(e){
    e.preventDefault(); //No se activará la acción prederterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
//    alert(formData); //no se muestra objeto
    $.ajax({
        url: '../ajax/categoria.php?op=guardaryeditar',
        type: 'POST',
        data: formData,
        contentType: false, //que es 
        processData: false, //que es
        success: function(r){
            bootbox.alert(r);
            limpiar(); //
            mostrarForm(false);
//            listar();  //no puede reinicializarse DataTable
            tabla.ajax.reload();
        }    
    });
}

function mostrar(idcategoria){
    $.post('../ajax/categoria.php?op=mostrar', {idcategoria:idcategoria}, function(data){
        data = JSON.parse(data); //convertir a javascript
        mostrarForm(true);
//      , status  
        $("#idcategoria").val(data.idcategoria);
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
    });
}

function desactivar(idcategoria){
    bootbox.confirm('¿Está seguro que desea desactivar la categoría?', function(respuesta){
        if(respuesta){
            $.post('../ajax/categoria.php?op=desactivar', {idcategoria: idcategoria}, function(data, status){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idcategoria){
    bootbox.confirm('¿Está seguro que desea activar la categoría?', function(respuesta){
        if(respuesta){
            $.post('../ajax/categoria.php?op=activar', {idcategoria:idcategoria}, function(data, status){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

init();
