var tabla_bancos;

//Función que se ejecuta al inicio
function init() {
  listar_bancos();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#guardar_registro").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-bancos").submit(); }  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// abrimos el navegador de archivos
$("#imagen1_i").click(function () { $("#imagen1").trigger("click"); });
$("#imagen1").change(function (e) { addImage(e, $("#imagen1").attr("id"), "../dist/img/default/img_defecto_banco.png"); });

function imagen1_eliminar() {
  $("#imagen1").val("");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_banco.png");

  $("#imagen1_nombre").html("");
}

//Función limpiar
function limpiar_banco() {
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled send-data');
  //Mostramos los Materiales
  $("#idbancos").val("");
  $("#nombre_b").val(""); 
  $("#alias").val("");
  $("#formato_cta").val("00000000"); 
  $("#formato_cci").val("00000000"); 
  $("#formato_detracciones").val("00000000");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_banco.png");
  $("#imagen1").val("");
  $("#imagen1_actual").val("");
  $("#imagen1_nombre").html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_bancos() {

  tabla_bancos=$('#tabla-bancos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    // dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      { extend: 'copyHtml5', exportOptions: { columns: [0,5,6,7,8,9], }, footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,5,6,7,8,9], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success",  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,5,6,7,8,9], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger",  } ,
    ],
    ajax:{
      url: '../ajax/bancos.php?op=listar',
      type : "get",
      dataType : "json",						
      error: function(e){ console.log(e.responseText);	ver_errores(e); }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap text-center"); }
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
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [5], visible: false, searchable: false, },
      { targets: [6], visible: false, searchable: false, },
      { targets: [7], visible: false, searchable: false, }, 
      { targets: [8], visible: false, searchable: false, },
      { targets: [9], visible: false, searchable: false, },      
    ],
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_bancos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-bancos")[0]);
 
  $.ajax({
    url: "../ajax/bancos.php?op=guardaryeditar_bancos",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {  
      try {
        e = JSON.parse(e);  console.log(e);            
        if (e.status == true) {
          Swal.fire("Correcto!", "Banco registrado correctamente.", "success");
          tabla_bancos.ajax.reload(null, false);         
          limpiar_banco();
          $("#modal-agregar-bancos").modal("hide");
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_banco").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_banco").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_banco").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_bancos(idbancos) {

  $(".tooltip").remove();
  
  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();

  limpiar_banco(); //console.log(idbancos);

  $("#modal-agregar-bancos").modal("show")

  $.post("../ajax/bancos.php?op=mostrar_bancos", { idbancos: idbancos }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status) {
      $("#idbancos").val(e.data.idbancos);
      $("#nombre_b").val(e.data.nombre); 
      $("#alias").val(e.data.alias);
      $("#formato_cta").val(e.data.formato_cta); 
      $("#formato_cci").val(e.data.formato_cci); 
      $("#formato_detracciones").val(e.data.formato_detracciones); 

      if (e.data.icono != "") {
        $("#imagen1_i").attr("src", "../dist/docs/banco/logo/" + e.data.icono);  
        $("#imagen1_actual").val(e.data.icono);
      }

      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function ver_perfil_banco(file, url_carpeta, nombre) {
  $('.foto-banco').html(nombre);
  $(".tooltip").remove();
  $("#modal-ver-perfil-banco").modal("show");
  $('#perfil-banco').html(doc_view_extencion(file, url_carpeta, '100%'));
  $(`.jq_image_zoom`).zoom({ on:'grab' });
}

//Función para desactivar registros
function eliminar_bancos(idbancos, nombre) {

  crud_eliminar_papelera(
    "../ajax/bancos.php?op=desactivar_bancos",
    "../ajax/bancos.php?op=eliminar_bancos", 
    idbancos, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_bancos.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
 
}
init();

$(function () {

  $("#form-bancos").validate({
    rules: {
      nombre:     { required: true, minlength:2, maxlength:65},    
      alias:      { minlength:2, maxlength:65 },    
      formato_cta:{ required: true, minlength:8 },
      formato_cci:{ required: true, minlength:8 },
      formato_detracciones: { required: true, minlength:8 },
    },
    messages: {
      nombre:     { required: "Campo requerido. ", minlength:"MINIMO 2 carecteres", maxlength: "MÁXIMO 65 carecteres." },
      alias:      { minlength:"Ingrese almenos 2 carecteres", maxlength: "Máximo 65 carecteres" },
      formato_cta:{ required: "Campo requerido", minlength:"MINIMO 8 dígitos." },
      formato_cci:{ required: "Campo requerido", minlength:"MINIMO 8 dígitos." },
      formato_detracciones: { required: "Campo requerido", minlength:"MINIMO 8 dígitos." }
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
      guardaryeditar_bancos(e);    
    },
  });
});

