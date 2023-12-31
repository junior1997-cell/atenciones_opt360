var tabla; 
//Función que se ejecuta al inicio
function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");
  $("#mRecurso").addClass("active");
  $("#lClienteProveedor").addClass("active");
  
  lista_de_items();
  tbla_principal('todos');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);
  
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_persona").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-persona").submit(); }  });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#banco").select2({templateResult: formatBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });
  $("#tipo_documento").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  $("#cargo_trabajador").select2({theme:"bootstrap4", placeholder: "Selecione cargo", allowClear: true, });

  no_select_over_18('#nacimiento');

  // Formato para telefono
  $("[data-mask]").inputmask();
}

init();

function formatBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

function foto1_eliminar() {

	$("#foto1").val("");

	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto1_nombre").html("");
}

//Función limpiar
function limpiar_form_persona() {
  
  $("#guardar_registro_persona").html('Guardar Cambios').removeClass('disabled send-data');

  $("#idpersona").val(""); 
  $("#tipo_documento").val("null").trigger("change");
  $("#cargo_trabajador").val("1").trigger("change");

  $("#num_documento").val(""); 
  $("#nombre").val(""); 
  $("#input_socio").val("0"); 
  $("#email").val(""); 
  $("#telefono").val(""); 
  $("#direccion").val(""); 

  $("#banco").val("").trigger("change");
  $("#cta_bancaria").val(""); 
  $("#cci").val(""); 
  $("#titular_cuenta").val("");    

  $("#socio").prop('checked', false);
  $(".sino").html('(NO)');

  $("#nacimiento").val("");
  $("#edad").val("");
  $(".edad").html("0.00");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function lista_de_items() { 

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/persona.php?op=tipo_persona", function (e, status) {
    
    e = JSON.parse(e); //console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tbla_principal('${val.idtipo_persona}')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link active" onclick="delay(function(){tbla_principal('todos')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="true">Todos</a>
        </li>
        ${data_html}
      `); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función Listar
function tbla_principal(tipo_persona) {
  show_hide_btn_add(tipo_persona);

  tabla=$('#tabla-persona').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    // dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,7,8,9,3,4,10,11,12], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,7,8,9,3,4,10,11,12], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,7,8,9,3,4,10,11,12], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: `../ajax/persona.php?op=tbla_principal&tipo_persona=${tipo_persona}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: botones
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      // columna: telefono
      if (data[4] != '') { $("td", row).eq(4).addClass('text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [8,9,10,11,12,13,14], visible: false, searchable: false, }, 
    ],
  }).DataTable();

}

function show_hide_btn_add(tipo_persona) {
  $("#sueldo_mensual").val("0.00");
  $(".campos_trabajador").hide();

  if (tipo_persona=="todos") {
    $("#id_tipo_persona").val("");
    $(".class_btn").hide();    
  }else{

    $("#id_tipo_persona").val(tipo_persona);
    $(".class_btn").show();

    if (tipo_persona=="2") { // trabajador :::::::::::
      $(".div_tipo_doc").show();
      $(".div_num_doc").show();
      $(".div_nombre").show();
      $(".div_telefono").show();
      $(".div_correo").show();
      $(".div_fecha_nacimiento").show();
      $(".div_edad").show();
      $(".div_banco").show();
      $(".div_cta").show();
      $(".div_cci").show();
      $(".div_titular_cuenta").show().removeClass("col-lg-8").addClass("col-lg-4");
      $(".div_cargo").show();
      $(".div_sueldo_mensual").show();
      $(".div_sueldo_diario").show();
      $(".div_direccion").show();
      $(".btn_add").html(`<i class="fas fa-plus"></i> Agregar Trabajador`);

      $("#cargo_trabajador").val(null).trigger("change");

    }else if (tipo_persona=="3" || tipo_persona=="4") { //proveedor :::::::::::

      $(".div_tipo_doc").show();
      $(".div_num_doc").show();
      $(".div_nombre").show();
      $(".div_telefono").show();
      $(".div_correo").show();
      $(".div_fecha_nacimiento").hide();
      $(".div_edad").hide();
      $(".div_banco").show();
      $(".div_cta").show();
      $(".div_cci").show();
      $(".div_titular_cuenta").show().removeClass("col-lg-4").addClass("col-lg-8");
      $(".div_cargo").hide();
      $(".div_sueldo_mensual").hide();
      $(".div_sueldo_diario").hide();
      $(".div_direccion").show();

      $(".btn_add").html(`<i class="fas fa-plus"></i> Agregar ${tipo_persona=="3" ? 'Proveedor' : (tipo_persona=="4" ? 'Cliente' : '' )} `);
      $("#cargo_trabajador").val(1).trigger("change");

    }else {

      $(".btn_add").html(`<i class="fas fa-plus"></i> Agregar...`);
      
    }    
  }
}

//Función para guardar o editar
function guardar_y_editar_persona(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-persona")[0]);

  $.ajax({
    url: "../ajax/persona.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e); 
        if (e.status == true) {	
          Swal.fire("Correcto!", "persona guardado correctamente", "success");
          tabla.ajax.reload(null, false);          
          limpiar_form_persona();
          $("#modal-agregar-persona").modal("hide");
          
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_persona").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_persona").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_persona").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_persona").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_persona_div").show();
    },
    complete: function () {
      $("#barra_progress_persona").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_persona_div").hide();
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// ver detallles del registro
function verdatos(idpersona){

  $(".tooltip").remove();

  $('#datospersona').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; 

  var imagen_perfil =''; btn_imagen_perfil=''; 

  $("#modal-ver-persona").modal("show")

  $.post("../ajax/persona.php?op=verdatos", { idpersona: idpersona }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.status == true) {
      
    
      if (e.data.imagen_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/persona/perfil/${e.data.imagen_perfil}" alt="" class="img-thumbnail w-130px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/persona/perfil/${e.data.imagen_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/persona/perfil/${e.data.imagen_perfil}" download="PERFIL ${e.data.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }

      verdatos=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2" class="text-center">${imagen_perfil}<br>${btn_imagen_perfil} </th>
                  <td> <b>Nombre: </b>${e.data.nombres}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>DNI: </b>${e.data.numero_documento}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Dirección</th>
                  <td>${e.data.direccion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Correo</th>
                  <td>${e.data.email}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Teléfono</th>
                  <td>${e.data.telefono}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha Nac.</th>
                  <td>${e.data.fecha_nacimiento}</td>
                </tr>
                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Titular cuenta </th>
                  <td>${e.data.titular_cuenta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Banco </th>
                  <td>${e.data.banco}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cuenta bancaria </th>
                  <td>${e.data.cuenta_bancaria}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>cci </th>
                  <td>${e.data.cci}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo mensual </th>
                  <td>${e.data.sueldo_mensual}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo diario </th>
                  <td>${e.data.sueldo_diario}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datospersona").html(verdatos);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

// mostramos los datos para editar
function mostrar(idpersona) {
  $(".tooltip").remove();
  limpiar_form_persona();  

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-persona").modal("show")

  $.post("../ajax/persona.php?op=mostrar", { idpersona: idpersona }, function (e, status) {

    e = JSON.parse(e);  console.log(e);   

    if (e.status == true) {       

      $("#tipo_documento").val(e.data.tipo_documento).trigger("change");
      $("#cargo_trabajador").val(e.data.idcargo_trabajador).trigger("change");

      $("#nombre").val(e.data.nombres);
      $("#num_documento").val(e.data.numero_documento);
      $("#direccion").val(e.data.direccion);
      $("#telefono").val(e.data.celular);
      $("#email").val(e.data.correo);
      $("#nacimiento").val(e.data.fecha_nacimiento).trigger("change");
      $("#edad").val(e.data.edad); 
      $("#titular_cuenta").val(e.data.titular_cuenta);
      $("#idpersona").val(e.data.idpersona);
      $("#ruc").val(e.data.ruc);   
    
      $("#cta_bancaria").val(e.data.cuenta_bancaria).trigger("change"); 
      $("#cci").val(e.data.cci).trigger("change"); 
      $("#banco").val(e.data.idbancos).trigger("change"); 

      $("#sueldo_mensual").val(e.data.sueldo_mensual);
      $("#sueldo_diario").val(e.data.sueldo_diario);  

      $("#id_tipo_persona").val(e.data.idtipo_persona); 
      $("#sueldo_mensual").val(e.data.sueldo_mensual);
      $("#sueldo_diario").val(e.data.sueldo_diario);  
      
      if (e.data.foto_perfil!="") {
        $("#foto1_i").attr("src", "../dist/docs/persona/perfil/" + e.data.foto_perfil);
        $("#foto1_actual").val(e.data.foto_perfil);
      }
      calcular_edad('#nacimiento','.edad','#edad'); 

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_persona(idpersona, nombre) {

  crud_eliminar_papelera(
    "../ajax/persona.php?op=desactivar",
    "../ajax/persona.php?op=eliminar", 
    idpersona, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  ); 
}

/* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */

function recuperar_banco() {
  
  $.post("../ajax/persona.php?op=recuperar_banco", function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      toastr_success('oka', 'se realzo toda la transaccion', 700);
      tabla.ajax.reload(null, false); 
    } else {
      ver_errores(e);
    }
    $('#recuperar_banco').addClass('disabled');
  });
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $("#tipo_documento").on('change', function() { $(this).trigger('blur'); });
  $("#banco").on('change', function() { $(this).trigger('blur'); });
  $("#cargo_trabajador").on('change', function() { $(this).trigger('blur'); });

  $("#form-persona").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento:  { required: true, minlength: 6, maxlength: 20 },
      nombre:         { required: true, minlength: 6, maxlength: 100 },
      email:          { email: true, minlength: 10, maxlength: 50 },
      direccion:      { minlength: 5, maxlength: 200 },
      telefono:       { minlength: 8 },
      cta_bancaria:   { minlength: 10,},
      banco:          { required: true},
      sueldo_mensual: { required: true},
    },
    messages: {
      tipo_documento: { required: "Campo requerido.", },
      num_documento:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 200 caracteres.", },
      telefono:       { minlength: "MÍNIMO 8 caracteres.", },
      cta_bancaria:   { minlength: "MÍNIMO 10 caracteres.", },
      banco:          { required: "Campo requerido.", },
      sueldo_mensual: { required: "Campo requerido.", }
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
      guardar_y_editar_persona(e);
    },
  });

  $("#tipo_documento").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#cargo_trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "" || $("#banco").select2("val") == '1') {

    $("#cta_bancaria").prop("readonly",true);   $("#cci").prop("readonly",true);
  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>'); $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    $("#cta_bancaria").prop("readonly",false);   $("#cci").prop("readonly",false);

    $.post("../ajax/ajax_general.php?op=formato_banco", { idbanco: $("#banco").select2("val") }, function (e, status) {

      e = JSON.parse(e);  console.log(e); 

      if (e.status) {
        $(".chargue-format-1").html('Cuenta Bancaria'); $(".chargue-format-2").html('CCI');

        var format_cta = decifrar_format_banco(e.data.formato_cta); var format_cci = decifrar_format_banco(e.data.formato_cci);

        $("#cta_bancaria").inputmask(`${format_cta}`);

        $("#cci").inputmask(`${format_cci}`);
      } else {
        ver_errores(e);
      }      

    }).fail( function(e) { ver_errores(e); } );   
  }  
}

function habilitando_socio() {  
  if ($("#socio").val()==null || $("#socio").val()=="" || $('#socio').is(':checked') ) {
    $("#input_socio").val('0'); $(".sino").html('(NO)');
  }else{
    $("#input_socio").val('1'); $(".sino").html('(SI)');
  }
}

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual').val()

  var sueldo_diario=(sueldo_mensual/30).toFixed(1);

  var sueldo_horas=(sueldo_diario/8).toFixed(1);

  $("#sueldo_diario").val(sueldo_diario);

}

// ver imagen grande de la persona
function ver_img_persona(file, nombre) {
  $('.foto-persona').html(nombre);
  $(".tooltip").remove();
  $("#modal-ver-perfil-persona").modal("show");
  $('#perfil-persona').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}



