var tabla_tipo_negocio;

//Función que se ejecuta al inicio
function init_negocio() {
  
  $("#bloc_Recurso").addClass("menu-open");
  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");

  listar_tipo_negocio();

  $("#guardar_registro_tipo_negocio").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-tipo-negocio").submit(); }  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_tipo_negocio() {
  $("#guardar_registro_tipo_negocio").html('Guardar Cambios').removeClass('disabled send-data');
  //Mostramos los Materiales
  $("#idtipo_negocio").val("");
  $("#nombre_tipo_negocio").val(""); 
  $("#descripcion_tipo_negocio").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_tipo_negocio() {

  tabla_tipo_negocio=$('#tabla-tipo-negocio').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3], }, footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success",  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger",  } ,
    ],
    ajax:{
      url: '../ajax/tipo_negocio.php?op=listar_tipo_negocio',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: #
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar

function guardar_y_editar_tipo_negocio(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-tipo-negocio")[0]);
 
  $.ajax({
    url: "../ajax/tipo_negocio.php?op=guardar_y_editar_tipo_negocio",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Tipo trabajado registrado correctamente.", "success");
          tabla_tipo_negocio.ajax.reload(null, false);         
          limpiar_tipo_negocio();
          $("#modal-agregar-tipo-negocio").modal("hide");        
          
        }else{
          ver_errores(e);	
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
      $("#guardar_registro_tipo_negocio").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_tipo_negocio").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_tipo_negocio").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_tipo_negocio").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_tipo_negocio").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_tipo_negocio(idtipo_negocio) {
  $(".tooltip").remove();
  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();

  limpiar_tipo_negocio();

  $("#modal-agregar-tipo-negocio").modal("show")

  $.post("../ajax/tipo_negocio.php?op=mostrar_tipo", { idtipo_negocio: idtipo_negocio }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idtipo_negocio").val(e.data.idtipo_negocio);
      $("#nombre_tipo_negocio").val(e.data.nombre);
      $("#descripcion_tipo_negocio").val(e.data.descripcion);

      $("#cargando-11-fomulario").show();
      $("#cargando-12-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para eliminar registros
function eliminar_tipo_negocio(idtipo_negocio, nombre) {  
  
  crud_eliminar_papelera(
    "../ajax/tipo_negocio.php?op=desactivar_tipo",
    "../ajax/tipo_negocio.php?op=eliminar_tipo", 
    idtipo_negocio, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_tipo.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init_negocio();

$(function () { 

  $("#form-tipo-negocio").validate({
    rules: {
      nombre_tipo_negocio: { required: true, maxlength: 100, minlength: 3 }      // terms: { required: true },
    },
    messages: {
      nombre_tipo_negocio: { required: "Por favor ingrese nombre.", maxlength: "Maximo 100 caracteres.", minlength: "Maximo 3 caracteres." },      
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      guardar_y_editar_tipo_negocio(e);      
    },
  });
});

