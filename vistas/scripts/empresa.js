var tabla_empresa;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");
  $("#mRecurso").addClass("active");

  listar_empresa();  

  // ══════════════════════════════════════ INITIALIZE SELECT2  ══════════════════════════════════════  
  $("#estado_certificado").select2({ theme: "bootstrap4", placeholder: "Selecione", allowClear: true,   });
  // Formato para telefono
  $("[data-mask]").inputmask();
}

// abrimos el navegador de archivos
$("#foto1_i").click(function () { $("#foto1").trigger("click"); });
$("#foto1").change(function (e) { addImage(e, $("#foto1").attr("id"), "../dist/img/default/img_defecto.png"); });

function foto1_eliminar() {
  $("#foto1").val("");
  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
  $("#foto1_nombre").html("");
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

function doc1_eliminar() {
	$("#doc1").val("");
	$("#doc1_ver").html('<img src="../dist/img/default/img_defecto_3.png" alt="" width="50%" >');
	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar_form_empresa() {
  
  //Mostramos los Materiales
  $("#iddatos_empresa").val("");
  $("#nombre_empresa").val(""); 
  $("#tipo_documento").val("RUC"); 
  $("#numero_documento").val("");
  $("#direccion").val(""); 
  $("#departamento").val(""); 
  $("#provincia").val(""); 
  $("#distrito").val(""); 
  $("#ubigeo").val(""); 
  $("#pais").val("PERU"); 
  $("#telefono").val(""); 
  $("#correo").val(""); 

  $("#nombre_impuesto").val(""); 
  $("#monto_impuesto").val(""); 
  $("#moneda").val(""); 
  $("#simbolo").val(""); 

  $("#usuario_sol").val("");
  $("#clave_sol").val("");

  $("#estado_certificado").val("").trigger('change');
  $("#clave_certificado").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/img/default/img_defecto_3.png" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");  

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_form(flag) {
  if (flag == 1) { // tabla
    $(".btn-regresar").hide("");
    $(".btn-agregar").show("");

    $(".div_col_empresa").addClass("col-lg-6 col-xl-6").removeClass("col-lg-12 col-xl-12"); 
    $(".div_tabla_empresa").show(); 
    $(".div_form_empresa").hide(); 
  } else if (flag == 2) { // formulario
    $(".btn-regresar").show("");
    $(".btn-agregar").hide("");

    $(".div_col_empresa").addClass("col-lg-12 col-xl-12").removeClass("col-lg-6 col-xl-6"); 
    $(".div_tabla_empresa").hide(); 
    $(".div_form_empresa").show(); 
  }
}

//Función Listar
function listar_empresa() {

  tabla_empresa=$('#tabla-empresa').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla_empresa.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,2,3,4], },footer: true, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray"  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,2,3,4], }, footer: true, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,2,3,4], }, footer: false, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", } ,
    ],
    ajax:{
      url: '../ajax/empresa.php?op=tbla_empresa',
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
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[
      // { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
    ]
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_empresa(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-empresa")[0]);
 
  $.ajax({
    url: "../ajax/empresa.php?op=guardar_y_editar_empresa",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "empresa registrado correctamente.", "success");
          tabla_empresa.ajax.reload(null, false);         
          show_hide_form(1);
        }else{
          ver_errores(e);				 
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
      $("#guardar_registro_empresa").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_empresa").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_empresa").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_empresa").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_empresa").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_empresa(idempresa) {
  show_hide_form(2);  
  $("#cargando-16-fomulario").hide();
  $("#cargando-17-fomulario").show();
  limpiar_form_empresa();

  $.post("../ajax/empresa.php?op=mostrar_empresa", { idempresa: idempresa }, function (e, status) {

    e = JSON.parse(e);  console.log(e);      
    if (e.status == true) {     
      
      $("#iddatos_empresa").val(e.data.	iddatos_empresa);
      $("#nombre_empresa").val(e.data.nombre_empresa); 
      $("#tipo_documento").val(e.data.tipo_documento); 
      $("#numero_documento").val(e.data.numero_documento);
      $("#direccion").val(e.data.direccion); 
      $("#departamento").val(e.data.departamento); 
      $("#provincia").val(e.data.provincia); 
      $("#distrito").val(e.data.distrito); 
      $("#ubigeo").val(e.data.ubigeo); 
      $("#pais").val(e.data.pais); 
      $("#telefono").val(e.data.telefono); 
      $("#correo").val(e.data.correo); 
    
      $("#nombre_impuesto").val(e.data.nombre_impuesto); 
      $("#monto_impuesto").val(e.data.monto_impuesto); 
      $("#moneda").val(e.data.moneda); 
      $("#simbolo").val(e.data.simbolo); 
    
      $("#usuario_sol").val(e.data.usuario_sol);
      $("#clave_sol").val(e.data.clave_sol);

      $("#estado_certificado").val(e.data.estado_certificado).trigger('change');
      $("#clave_certificado").val(e.data.clave_certificado);

      if (e.data.logo != "") {        
        $("#foto1_i").attr("src", "../dist/docs/empresa/img_perfil/" + e.data.logo);  
        $("#foto1_actual").val(e.data.logo);
      }

      if (e.data.ruta_certificado == "" || e.data.ruta_certificado == null  ) { } else {
        $("#doc_old_1").val(e.data.ruta_certificado); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Certificado-digital.${extrae_extencion(e.data.ruta_certificado)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.ruta_certificado,'admin/public/FACT_WebService/Facturacion/src', '100%', '210' ));            
      }

      $("#cargando-16-fomulario").show();
      $("#cargando-17-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function buscar_empresa() {
  var num_ruc = $('#numero_documento').val(); console.log(num_ruc.length);
  if (num_ruc.length == 11 || num_ruc.length == '11') {
    var url = `https://dniruc.apisperu.com/api/v1/ruc/${num_ruc}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Ik1hbnVlbF8xM18xOTk4QGhvdG1haWwuY29tIn0.pNHFyJ3fT4JgofrxzINaJWlqh3_fC9bCzfwSP4N_dMo`;
    $('.bnt-charge-sunat').html(`<i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary"></i>`);
    $.ajax({
      type: 'GET',
      url: url,
      success: function (e) {
        console.log(e);
        if (e.success == false) {
          toastr_warning('Ruc Inválido', '¡No Existe RUC!', 700);
        } else {        
          $('#nombre_empresa').val(e.razonSocial);
          $('#direccion').val(e.direccion);      
          $('#departamento').val(e.departamento);  
          $('#provincia').val(e.provincia);  
          $('#distrito').val(e.distrito);     
          $('#ubigeo').val(e.ubigeo);          
          $('#numero_documento').removeClass('input-no-valido');
        }
        $('.bnt-charge-sunat').html(`<i class="fas fa-search text-primary" ></i>`);
      }, 
      complete: function () { }, 
      error: function (e) { ver_errores(e);  }
    });
  } else {
    toastr_error('Error', 'Ingresa 11 digitos', 700);
    $('#numero_documento').addClass('input-no-valido');
  }
  
}

init();

$(function () {

  $("#estado_certificado").on('change', function() { $(this).trigger('blur'); });

  $("#form-empresa").validate({
    rules: {
      nombre_empresa:   { required: true, maxlength: 250, },
      tipo_documento:   { required: true, maxlength: 20, },
      numero_documento: { required: true, maxlength: 20, },
      pais:             { required: true, maxlength: 45, },
      direccion:        { required: true, maxlength: 250, },
      departamento:     { required: true, maxlength: 45, },
      provincia:        { required: true, maxlength: 45, },
      distrito:         { required: true, maxlength: 45, },
      telefono:         { },
      correo:           { maxlength:100 },
      nombre_impuesto:  { required: true, maxlength: 10, minlength:2 },
      monto_impuesto:   { required: true, min:0, max:100 },
      moneda:           { required: true, maxlength: 10, minlength:2 },
      simbolo:          { required: true, maxlength: 10, minlength:1 },

      usuario_sol:      { maxlength: 30, minlength:3 },
      clave_sol:        { maxlength: 30, minlength:3 },

      estado_certificado:{ required: true },
      clave_certificado:{ maxlength: 30, minlength:3 },
    },
    messages: {
      nombre_empresa:   { required: "Campo requerido.", maxlength: "Máximo 250 caracteres.", },
      tipo_documento:   { required: "Campo requerido.",maxlength: "Máximo 20 caracteres.", },
      numero_documento: { required: "Campo requerido.",maxlength: "Máximo 20 caracteres.", },
      pais:             { required: "Campo requerido.",maxlength: "Máximo 45 caracteres.", },
      direccion:        { required: "Campo requerido.", maxlength: "Máximo 250 caracteres.", },
      departamento:     { required: "Campo requerido.", maxlength: "Máximo 45 caracteres.", },
      provincia:        { required: "Campo requerido.", maxlength: "Máximo 45 caracteres.", },
      distrito:         { required: "Campo requerido.", maxlength: "Máximo 45 caracteres.", },
      telefono:         { pattern: "Formato no valido" },
      correo:           { maxlength: "Máximo 100 caracteres.", email: 'Ingrese un correo valido' },
      nombre_impuesto:  { required: "Campo requerido.", maxlength: "Máximo 10 caracteres.", minlength: "MÍNIMO 2 caracteres.", },
      monto_impuesto:   { required: "Campo requerido.", min:"Números positivos", max: "Número máximo 100." },
      moneda:           { required: "Campo requerido.", maxlength: "Máximo 10 caracteres.", minlength: "MÍNIMO 2 caracteres.", },
      simbolo:          { required: "Campo requerido.", maxlength: "Máximo 10 caracteres.", minlength: "MÍNIMO 1 caracteres.", },

      usuario_sol:      { maxlength: "Máximo 30 caracteres.", minlength: "MÍNIMO 3 caracteres.", },
      clave_sol:        { maxlength: "Máximo 30 caracteres.", minlength: "MÍNIMO 3 caracteres.", },

      estado_certificado:{ required: "Campo requerido.", },
      clave_certificado: { maxlength: "Máximo 30 caracteres.", minlength: "MÍNIMO 3 caracteres.", },
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
      guardaryeditar_empresa(e);      
    },

  });

  $("#estado_certificado").rules("add", { required: true, messages: { required: "Campo requerido" } });
});

function ver_password(click, input, span ) {
  var x = document.getElementById(input);
  if (x.type === "password") {
    x.type = "text";  $(span).html(`<i class="fa-solid fa-eye-slash text-primary"></i>`); 
    $(click).attr('data-original-title', 'Ocultar contraseña');
  } else {
    x.type = "password";   $(span).html(`<i class="fa-solid fa-eye text-primary"></i>`);
    $(click).attr('data-original-title', 'Ver contraseña');
  }

  $('[data-toggle="tooltip"]').tooltip();
}

function ver_perfil_empresa(file, url_carpeta, nombre) { console.log(url_carpeta);
  $('.foto-banco').html(nombre);
  $(".tooltip").remove();
  $("#modal-ver-perfil-banco").modal("show");
  $('#perfil-banco').html(doc_view_extencion(file, url_carpeta, '100%', 'auto',false, false));
  $(`.jq_image_zoom`).zoom({ on:'grab' });
}