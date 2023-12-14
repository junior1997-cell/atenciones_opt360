var tabla, tablamateriales;

var host = window.location.host == 'localhost' || es_numero(parseFloat(window.location.host)) == true ? `${window.location.origin}/front_jdl/admin/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/admin/dist/docs/compra_insumo/comprobante_compra/` ;

var array_venta = [];

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");
  $("#mContableFinanciero").addClass("active");
  $("#lCotizacionVenta").addClass("active");

  lista_de_items();
  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Persona_por_tipo&tipo=3", '#idproveedor', null);

  lista_select2("../ajax/comprobante.php?op=select_tipo_persona", '#idtipopersona', null);  
  lista_select2("../ajax/ajax_general.php?op=select2_cargo_trabajador", '#cargo_trabajador', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════ 
  $("#guardar_registro_cotizacion").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-cotizacion").submit(); }  });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PROVEEDOR  ══════════════════════════════════════  
  $("#idtipopersona").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo", allowClear: true,   });
  $("#tipo_documento").select2({theme:"bootstrap4", placeholder: "Selec. tipo Doc.", allowClear: true, });
  $("#banco").select2({templateResult: formatBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });  
  $("#cargo_trabajador").select2({theme:"bootstrap4", placeholder: "Selecione cargo", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COTIZACION  ══════════════════════════════════════  
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione un proveedor", allowClear: true,   });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#precio_con_igv").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });
  $("#tiempo_produccion").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });
  $("#validez_cotizacion").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });
  

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function formatBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {
	$("#doc1").val("");
	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar_form_cotizacion() {
  $("#idcotizacion").val("");  
  $("#fecha_cotizacion").val("");  
  $("#serie_comprobante").val("");
  $("#numero_comprobante").val("");
  $("#impuesto").val("");
  $("#impuesto_decimal").val("");
  $("#nota").val("");

  $("#idproveedor").val("").trigger("change");
  $("#tipo_comprobante").val("Cotizacion").trigger("change");
  $("#forma_pago").val("Cotizacion").trigger("change");
  $("#precio_con_igv").val("Cotizacion").trigger("change");
  $("#tiempo_produccion").val("Cotizacion").trigger("change");
  $("#validez_cotizacion").val("Cotizacion").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_form(flag) {
	if (flag == 1)	{		 // TABLA PRINCIPAL
		$("#mostrar-tabla").show();
    $("#div-agregar-compras").hide();
    $(".btn-regresar").hide();
    $(".btn-agregar").show();
	}	else	{ // FORMULARIO COTIZACION
		$("#mostrar-tabla").hide();
    $("#div-agregar-compras").show();
    $(".btn-regresar").show();
    $(".btn-agregar").hide();

    listarmateriales();
	}
}

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-comprobante").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn bg-gradient-info", action: function ( e, dt, node, config ) { tabla.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },
      { extend: 'copyHtml5', exportOptions: { columns: [0,9,10,11,3,12,13,5,14,15,6,16,7], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [0,9,10,11,3,12,13,5,14,15,6,16,7], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [0,9,10,11,3,12,13,5,14,15,6,16,7], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `Columnas`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax: {
      url: "../ajax/comprobante.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); verer
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: botones
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: total
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-right"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 6 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 6 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );       
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [9,10,11,12,13,14,15,16], visible: false, searchable: false, }, 
      { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      
    ],
  }).DataTable();
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_cotizacion(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);  

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/compra_producto.php?op=guardaryeditarcompra", {
        method: 'POST', // or 'PUT'
        body: formData, // data can be `string` or {object}!        
      }).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){        
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");

        tabla_compra_insumo.ajax.reload(null, false);
        tabla_compra_x_proveedor.ajax.reload(null, false);

        limpiar_form_cotizacion();
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//mostramos el num_comprobante del ticket
function numTicket(){
	var idsucursal = $("#idsucursal").val();
	$.ajax({
    url: '../ajax/cotizacion_venta.php?op=mostrar_num_ticket',
    type:'get',
    data:{idsucursal: idsucursal},
    dataType:'json',
    success: function(d){
      iva=d;
      $("#num_comprobante").val( ('0000000' + iva).slice(-7) ); // "0001"
      $("#nFacturas").html( ('0000000' + iva).slice(-7) ); // "0001"
    }
	});
}

//mostramos la serie_comprobante de la ticket
function numSerieTicket(){
	var idsucursal = $("#idsucursal").val();
	$.ajax({
    url: '../ajax/cotizacion_venta.php?op=mostrar_s_ticket',
    type:'get',
    data:{idsucursal: idsucursal},
    dataType:'json',
    success: function(s){
      series=s;
      console.log("iva", series);
      $("#numeros").html( ('000' + series).slice(-3) ); // "0001"
      $("#serie_comprobante").val( ('000' + series).slice(-3) ); // "0001"
    }
	});
}

//Función para desactivar registros
function eliminar(idcomprobante, nombre,numero_comprobante) {

  crud_eliminar_papelera(
    "../ajax/comprobante.php?op=desactivar",
    "../ajax/comprobante.php?op=eliminar", 
    idcomprobante, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre} : ${numero_comprobante}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P E R S O N A  ::::::::::::::::::::::::::::::::::::::::::::::::::::
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
          <a class="nav-link" onclick="delay(function(){show_hide_btn_add('${val.idtipo_persona}')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`${data_html}`); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función limpiar
function limpiar_form_persona() {
  
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled send-data');

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

    }else if (tipo_persona=="3") { //proveedor :::::::::::

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

      $(".btn_add").html(`<i class="fas fa-plus"></i> Agregar Proveedor`);
      $("#cargo_trabajador").val(1).trigger("change");

    }else {

      $(".btn_add").html(`<i class="fas fa-plus"></i> Agregar...`);
      
    }    
  }
}

//guardar proveedor
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
          Swal.fire("Correcto!", "Persona guardado correctamente", "success");
          lista_select2("../ajax/comprobante.php?op=selecct_produc_o_provee", '#idpersona', e.data);    
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

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "" || $("#banco").select2("val") == "1" ) {

    $("#c_bancaria").prop("readonly", true);
    $("#cci").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);

      if (e.status == true) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria").prop("readonly", false);
        $("#cci").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria").inputmask(`${format_cta}`);
        $("#cci").inputmask(`${format_cci}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); } );
  }
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O D U C T O  ::::::::::::::::::::::::::::::::::::::::::::::::::::
//Función ListarArticulos
function listarmateriales() {
  tablamateriales = $("#tblamateriales").dataTable({
    // responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i>', action: function ( e, dt, node, config ) { tablamateriales.ajax.reload(); toastr_success('Exito!!', 'Actualizando tabla', 400); } }
    ],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaProductos",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') { $("td", row).eq(3).addClass('text-right'); }  
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {   
  
  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#forma_pago").on('change', function() { $(this).trigger('blur'); });
  $("#precio_con_igv").on('change', function() { $(this).trigger('blur'); });
  $("#tiempo_produccion").on('change', function() { $(this).trigger('blur'); });
  $("#validez_cotizacion").on('change', function() { $(this).trigger('blur'); });

  $("#idpersona").on('change', function() { $(this).trigger('blur'); });
  $("#banco").on('change', function() { $(this).trigger('blur'); });
  $("#idtipopersona").on('change', function() { $(this).trigger('blur'); });

  $("#form-cotizacion").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:        { required: true },
      tipo_comprobante:   { required: true },
      serie_comprobante:  { minlength: 2 },
      nota:               { minlength: 4 },
      fecha_cotizacion:   { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:        { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      serie_comprobante:  { minlength: "Minimo 2 caracteres", },
      nota:               { minlength: "Minimo 4 caracteres", },
      fecha_cotizacion:   { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':       { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_venta[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      'descuento[]':      { min: "Mínimo 0.00", required: "Campo requerido"},
      // 'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
      // 'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"},
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

    submitHandler: function (form) {
      guardar_y_editar_cotizacion(form);
    },
  });  

  $("#form-persona").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento:  { required: true },
      num_documento:   { required: true, minlength: 6, maxlength: 20 },
      nombre:          { required: true, minlength: 6, maxlength: 100 },
      direccion:       { minlength: 5, maxlength: 150 },
      telefono:        { minlength: 8 },
      c_bancaria:      { minlength: 6,  },
      banco:           { required: true},
      idtipopersona:   { required: true},
      cci:             { minlength: 6,  },
      titular_cuenta:  { minlength: 4 },
    },
    messages: {
      tipo_documento:{ required: "Por favor selecione un tipo de documento", },
      num_documento: { required: "Campo requerido", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre:        { required: "Campo requerido", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion:     { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono:      { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria:    { minlength: "MÍNIMO 6 caracteres.", },
      banco:          { required: "Campo requerido.", },
      idtipopersona:  { required: "Campo requerido.", },
      cci:           { minlength: "MÍNIMO 6 caracteres.",  },  
      titular_cuenta:{ minlength: 'MÍNIMO 4 caracteres.' },
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
      guardarpersona(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin 
  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#forma_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#precio_con_igv").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tiempo_produccion").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#validez_cotizacion").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#idpersona").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#idtipopersona").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  
  // restringimos la fecha para no elegir mañana
  no_select_tomorrow('#fecha_i');
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function extrae_ruc() {
  if ($('#proveedor').select2("val") == null || $('#proveedor').select2("val") == '') { }  else{    
    var ruc = $('#proveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}

// ver imagen grande del producto agregado a la compra
function ver_img_producto(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").remove();
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}


init();