var tabla_cargos;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");
  
  // $("#lBancoColor").addClass("active");

  listar_cargo();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_cargo").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-cargo").submit(); } });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  
  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_cargo() {
  $("#guardar_registro_cargo").html('Guardar Cambios').removeClass('disabled send-data');
  $("#idcargo_trabajador").val("");
  $("#nombre_cargo").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función listar_cargo
function listar_cargo() {

  tabla_cargos=$('#tabla-cargo').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', exportOptions: { columns: [0,2], }, footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success",  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger",  } ,
    ],
    ajax:{
      url: '../ajax/cargo.php?op=listar_cargo',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap text-center"); }
      // columna: #
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); }
     
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
function guardaryeditar_cargo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-cargo")[0]);
 
  $.ajax({
    url: "../ajax/cargo.php?op=guardaryeditar_cargo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e);  console.log(e);            
        if (e.status == true) {
          Swal.fire("Correcto!", "Cargo registrado correctamente.", "success");
          tabla_cargos.ajax.reload(null, false);         
          limpiar_cargo();
          $("#modal-agregar-cargo").modal("hide");
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
      $("#guardar_registro_cargo").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_cargo").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_cargo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_cargo").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_cargo").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_cargo(idcargo_trabajador) {
  $(".tooltip").remove();
  $("#cargando-13-fomulario").hide();
  $("#cargando-14-fomulario").show();

  limpiar_cargo();

  $("#modal-agregar-cargo").modal("show")
  $("#idtipo_trabjador_c").val("null").trigger("change");

  $.post("../ajax/cargo.php?op=mostrar", {idcargo_trabajador: idcargo_trabajador}, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idcargo_trabajador").val(e.data.idcargo_trabajador);
      $("#nombre_cargo").val(e.data.nombre); 

      $("#cargando-13-fomulario").show();
      $("#cargando-14-fomulario").hide();
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_cargo(idcargo_trabajador, nombre) {

  crud_eliminar_papelera(
    "../ajax/cargo.php?op=desactivar",
    "../ajax/cargo.php?op=eliminar", 
    idcargo_trabajador, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_cargos.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
 
}

init();

$(function () {

  $("#form-cargo").validate({
    rules: {
      
      nombre_cargo: { required: true }
    },
    messages: {
      
      nombre_cargo:       { required: "Campo requerido", },
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
      guardaryeditar_cargo(e);      
    },
  });
});

