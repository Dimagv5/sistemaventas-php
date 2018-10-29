var tabla;

function ini(){
    mostrarForm(false);
    listar();
    
    $("#formulario").on("submit",function(e){
        guardaryeditar(e);
    });
    
}

function limpiar(){    
    $("#idusuario").val("");
    $("#nombre").val("");
    $('#tipo_documento').val("");
    $('#tipo_documento').selectpicker('refresh');
    $('#num_documento').val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");
    $('#mostrarimagen').attr('src', ''); 
    $('#mostrarimagen').hide();
    $('#imagen').val("");
    $('#imagenactual').val(""); //no me parece tan necesario pero se debe de
}

function mostrarForm(flag){
    limpiar();
    if(flag){
        $("#formulariodiv").show();
        $("#listadodiv").hide();
        $("#btnGuardar").prop("disabled", false); 
        $('#btnAgregar').hide();
        //mostramos los permisos
        $.post('../ajax/usuario.php?op=permisos&id=', function(r){
            $('#permisos').html(r);
        });
    }else{
        $("#formulariodiv").hide();
        $("#listadodiv").show();
        $('#btnAgregar').show();
    }
}

function cancelarForm(){
//    limpiar();
    mostrarForm(false);
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
                   url : "../ajax/usuario.php?op=listar",
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
    e.preventDefault(); //para que no trabaje con el atributo action del form sino con q se ejecute esta funcion
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
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

function mostrar(idusuario){
    $.post("../ajax/usuario.php?op=mostrar", {idusuario:idusuario}, function(data, status){
        mostrarForm(true);
        data = JSON.parse(data);
        $("#idusuario").val(data.idusuario);
        $("#nombre").val(data.nombre);
        $('#tipo_documento').val(data.tipo_documento);
        $('#tipo_documento').selectpicker('refresh');
        $('#num_documento').val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#cargo").val(data.cargo);
        $("#login").val(data.login);
        $("#clave").val(data.clave);
        $('#mostrarimagen').attr('src', '../files/usuarios/'+data.imagen); 
        $('#mostrarimagen').show();
        $('#imagenactual').val(data.imagen);
//        $('#imagen').val(data.imagen); //no muestra nada
        //este post no funciona fuera del post ¿?
        $.post('../ajax/usuario.php?op=permisos&id='+idusuario, function(r){
            $('#permisos').html(r);
        });
    });   
}

function desactivar(idusuario){
    bootbox.confirm("¿Está seguro que desea desactivar el Usuario?", function(respuesta){
        if(respuesta){
            $.post("../ajax/usuario.php?op=desactivar", {idusuario:idusuario}, function(data){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idusuario){
    bootbox.confirm("¿Está seguro que desea activar el Usuario?", function(respuesta){
        if(respuesta){
            $.post("../ajax/usuario.php?op=activar", {idusuario:idusuario}, function(data){
                bootbox.alert(data);
                tabla.ajax.reload();
            });
        }
    });
}

ini();



