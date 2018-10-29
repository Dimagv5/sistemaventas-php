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
    $("#idpersona").val("");
    $("#nombre").val("");
//    $("#tipo_persona").val("");
    $("#tipo_documento").val("");
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email ").val("");
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
                        url : '../ajax/persona.php?op=listarp',
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
        url: '../ajax/persona.php?op=guardaryeditar',
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

function mostrar(idpersona){
    $.post('../ajax/persona.php?op=mostrar', {idpersona:idpersona}, function(data){
        data = JSON.parse(data); //convertir a javascript
        mostrarForm(true);
//      , status  
        $("#idpersona").val(data.idpersona);
        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email ").val(data.email);
    });
}

function eliminar(idpersona){
    bootbox.confirm('¿Está seguro que desea eliminar el proveedor?', function(respuesta){
        if(respuesta){
            $.post('../ajax/persona.php?op=eliminar', {idpersona: idpersona}, function(data, status){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

init();



