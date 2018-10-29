var tabla;

//función que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar(); 
}

//función mostrar formulario
function mostrarForm(flag){
//    limpiar();
    if(flag){
        $("#formulariodiv").show();
        $("#listadodiv").hide();
        $("#btnGuardar").prop("disabled", false); //duda por q habilitarlo
        $('#btnAgregar').hide();
    }else{
        $("#formulariodiv").hide();
        $("#listadodiv").show(); //listar();
        $('#btnAgregar').hide();
    }
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
                        url : '../ajax/permiso.php?op=listar',
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

init();



