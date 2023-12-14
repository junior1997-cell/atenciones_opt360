var tabla_color;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");
  $("#mRecurso").addClass("active");

  listar_tabla_color();

  $("#guardar_registro_color").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-color").submit(); } });

  //color picker with addon
  $('.my-colorpicker2').colorpicker();
  $('.my-colorpicker2').on('colorpickerChange', function(event) { 
    var color = '#e9ecef';
    if (event.color == null || event.color == '') { } else { color = event.color.toString(); }
    $('.my-colorpicker2 .fa-square').css('color', color); 
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_form_color() {
  $("#guardar_registro_color").html('Guardar Cambios').removeClass('disabled send-data');
  //Mostramos los Materiales
  $("#idcolor").val("");
  $("#nombre_color").val("");
  $("#hexadecimal").val("").trigger('change');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_tabla_color() {

  tabla_color = $('#tabla-color').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 6, 10, 25, 75, 100, 200,], ["Todos", 6, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3], }, footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success",  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger",  } ,
    ],
    ajax:{
      url: '../ajax/color.php?op=listar',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 6,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_color(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-color")[0]);
 
  $.ajax({
    url: "../ajax/color.php?op=guardar_y_editar_color",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
         Swal.fire("Correcto!", "Color registrado correctamente.", "success");
          tabla_color.ajax.reload(null, false);         
          limpiar_form_color();
          $("#modal-agregar-color").modal("hide");
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
      
      $("#guardar_registro_color").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_color").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_color").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_color").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_color").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idcolor) {
  $(".tooltip").remove();
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  
  limpiar_form_color();

  $("#modal-agregar-color").modal("show")

  $.post("../ajax/color.php?op=mostrar", { idcolor: idcolor }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idcolor").val(e.data.idcolor);
      $("#nombre_color").val(e.data.nombre_color); 

      if (e.data.hexadecimal == null || e.data.hexadecimal == '') {  } else {
        $("#hexadecimal").val(e.data.hexadecimal).trigger('change');
      }       

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_color(idcolor, nombre) {

  crud_eliminar_papelera(
    "../ajax/color.php?op=desactivar",
    "../ajax/color.php?op=eliminar", 
    idcolor, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_color.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-color").validate({
    rules: {
      nombre_color: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_color: {  required: "Campo requerido.", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_color(e);      
    },

  });
});

