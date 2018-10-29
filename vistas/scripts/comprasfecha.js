var tabla;

function ini(){
    listar();
    $('#fecha_inicio').change(function(){
        tabla.destroy();
        listar();
    });
    $('#fecha_fin').change(function(){
        tabla.destroy();
        listar();
    });
}

function listar(){
//    var fecha_inicio = $('#fecha_inicio').val();
//    var fecha_fin = $('#fecha_fin').val();
   
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
                   url : "../ajax/consultas.php?op=comprasfecha",
                   data: {fecha_inicio:$('#fecha_inicio').val(), fecha_fin:$('#fecha_fin').val()},
                   type: "get", 
                   dataType: "json",
                   error: function(e){
                       console.log(e.responseText);
                   }
//                   complete: function(){
//                       alert($('#fecha_inicio').val()+' '+$('#fecha_fin').val());
//                   }
               },
               "iDestroy": true,
               "iDisplayLength": 5, //Paginación
               "order": [[0,"desc"]]                
            }
            ).DataTable();
}

ini();

