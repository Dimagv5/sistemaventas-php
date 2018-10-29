$('#frmAcceso').on('submit', function(e){
    e.preventDefault();
     login = $('#logina').val();
     clave = $('#clavea').val();
    
    $.post('../ajax/usuario.php?op=verificar', {logina:login, clavea:clave}, function(data){
//          alert(data);
        data = JSON.parse(data);
        if(data != null){
//          alert(data.login);
            $(location).attr('href', 'escritorio.php');
        }else{
            bootbox.alert('Usuario y/o contraseña son incorrectos');
        }
    });
});


