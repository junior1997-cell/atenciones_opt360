var char_linea_subcontrato;

var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };
var mode = 'index';
var intersect = true;

function init() {

  $('#mEscritorio').addClass("active");

  // tablero();  

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════

  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#valorizacion_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro valorizacion", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function tablero() {   

  $.post("../ajax/escritorio.php?op=tablero",  function (e, status) {

    e = JSON.parse(e);  //console.log(e);

    if (e.status == true) {
      $("#cantidad_box_producto").html("Tours : "+e.data.total_tours);
      $("#cantidad_box_agricultor").html("Paquetes : "+e.data.total_paquete);
      $("#cantidad_box_trabajador").html("S/. "+formato_miles(e.data.total_ventas));

      $("#cantidad_box_visita").html(e.data.visitas_pag.nombre_vista+" "+e.data.visitas_pag.cantidad);
      $(".vista").html(e.data.visitas_pag.nombre_vista);

    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
}


init();

//=========================CHART PASTEL=======================
$(function () {
  $.post("../ajax/escritorio.php?op=vistas_pagina_web",  function (e, status) {

    e = JSON.parse(e);  console.log(e);
    var A_labels = [];
    var A_data = [];
    if (e.status == true) {    
      
      e.data.char_donut.forEach((val, index) => { A_labels.push(val.nombre_vista); A_data.push(val.total);  });

      // ::::::::::::::::::::::::::::: CHART RADAR :::::::::::::::::::::::::::::::::::
      var $radarChartCanvas = $('#radar-chart-visita-por-dia');
      const data = {
        labels: [ 'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado' ],
        datasets: [
          {
            label: 'Home',  data: e.data.chart_radar,  fill: true,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: '#f56954', pointBackgroundColor: '#f56954', pointBorderColor: '#fff', pointHoverBackgroundColor: '#fff', pointHoverBorderColor: '#f56954'
          }, 
          {
            label: 'Nosotros', data: [1, 8, 9, 19, 7, 3, 3], fill: true,
            backgroundColor: 'rgba(0, 166, 90, 0.2)',
            borderColor: '#00a65a',  pointBackgroundColor: '#00a65a',  pointBorderColor: '#fff',  pointHoverBackgroundColor: '#fff', pointHoverBorderColor: '#00a65a'
          },
          {
            label: 'Nosotros', data: [6, 8, 9, 1, 3, 2, 7], fill: true,
            backgroundColor: 'rgba(0, 166, 90, 0.2)',
            borderColor: '#f39c12',  pointBackgroundColor: '#f39c12',  pointBorderColor: '#fff',  pointHoverBackgroundColor: '#fff', pointHoverBorderColor: '#f39c12'
          }
        ]
      };
      new Chart($radarChartCanvas, {
        type: 'radar',
        data: data,
        options: { elements: { line: { borderWidth: 3 } }, donutOptions  },
      });

      // ::::::::::::::::::::::::::::: Grafico Barras :::::::::::::::::::::::::::::

      var $barras_v_x_m = $('#barras-chart-visita-por-mes')
      // eslint-disable-next-line no-unused-vars
      var barras_v_x_m = new Chart($barras_v_x_m, {
        type: 'bar',
        data: {
          labels: [ 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
          datasets: [
            { backgroundColor: '#28a745', borderColor: '#28a745', data: e.data.chart_barra, label: 'Total visitas' },             
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {  mode: mode, intersect: intersect },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true },
          scales: {
            yAxes: [{ display: true, gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' }, }],
            xAxes: [{ display: true, gridLines: { display: false }, ticks: ticksStyle }]
          }
        }
      });

      // ::::::::::::::::::::::::::::: CHART RADAR :::::::::::::::::::::::::::::::::::
      var donutChartCanvas = $('#donut-chart-visita-por-pagina').get(0).getContext('2d');
      var donutData    = { labels: A_labels, datasets: [ { data: A_data, backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],  } ] }
      var donutOptions = {  maintainAspectRatio : false,  responsive : true, }
      //Create pie or douhnut chart - You can switch between pie and douhnut using the method below.
      new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
      });

      // $('#radar-chart-visita-por-dia').css({'height': '450px', 'width': 'auto', 'display': 'inline' });
    } else {
      ver_errores(e);
    } 

  }).fail( function(e) { ver_errores(e); } );
})


// :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  S U B C O N T R A T O  ::::::::::::::::::::::::
$(function () {
  'use strict'

  

  function chart_producto() {   

    $.post("../ajax/escritorio.php?op=chart_producto",  function (e, status) {
  
      e = JSON.parse(e);  //console.log(e);
  
      if (e.status == true) {

        // ================================== Grafico Lineas ==================================

        var $visitorsChart = $('#venta-tours-chart')
        // eslint-disable-next-line no-unused-vars
        var visitorsChart = new Chart($visitorsChart, {
          data: {
            labels: [ 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGo', 'SEP', 'OCT', 'NOV', 'DIC'],
            datasets: [
              {
                type: 'line',
                data: e.data.total_venta,
                backgroundColor: 'transparent',
                borderColor: '#28a745',
                pointBorderColor: '#28a745',
                pointBackgroundColor: '#28a745',
                fill: false,
                label: 'Total venta',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor    : '#28a745'
              },
              {
                type: 'line',
                data: e.data.total_pagos,
                backgroundColor: 'tansparent',
                borderColor: '#ced4da',
                pointBorderColor: '#ced4da',
                pointBackgroundColor: '#ced4da',
                fill: false,
                label: 'Total pago',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor    : '#ced4da'
              }
            ]
          },
          options: {
            maintainAspectRatio: false,
            tooltips: { mode: mode, intersect: intersect },
            hover: { mode: mode, intersect: intersect },
            legend: { display: true },
            scales: {
              yAxes: [{
                // display: false,
                gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
                ticks: $.extend({ beginAtZero: true, callback: function (value) { if (value >= 1000) { value /= 1000; value += 'k';  }  return 'S/ ' + value; } }, ticksStyle)
              }],
              xAxes: [{
                display: true,
                gridLines: { display: false },
                ticks: ticksStyle
              }]
            }
          }
        });

        // ================================== Grafico Barras ==================================

        var $salesChart = $('#venta-paquete-chart')
        // eslint-disable-next-line no-unused-vars
        var salesChart = new Chart($salesChart, {
          type: 'bar',
          data: {
            labels: [ 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
            datasets: [
              { backgroundColor: '#28a745', borderColor: '#28a745', data: e.data.total_compra, label: 'Total compra' },
              { backgroundColor: '#000', borderColor: '#000', data: e.data.total_deposito, label: 'Total pago' },
              { backgroundColor: '#dc3545', borderColor: '#dc3545', data: e.data.total_kilos_pergamino, label: 'Kilos pergamino' },
              { backgroundColor: '#ffc107', borderColor: '#ffc107', data: e.data.total_kilos_coco, label: 'Kilos coco' },
            ]
          },
          options: {
            maintainAspectRatio: false,
            tooltips: {  mode: mode, intersect: intersect },
            hover: { mode: mode, intersect: intersect },
            legend: { display: true },
            scales: {
              yAxes: [{
                // display: false,
                gridLines: {
                  display: true,
                  lineWidth: '4px',
                  color: 'rgba(0, 0, 0, .2)',
                  zeroLineColor: 'transparent'
                },
                ticks: $.extend({
                  beginAtZero: true,
                  // Include a dollar sign in the ticks
                  callback: function (value) { if (value >= 1000) { value /= 1000; value += 'k';  }  return 'S/ ' + value; }
                }, ticksStyle)
              }],
              xAxes: [{
                display: true,
                gridLines: {
                  display: false
                },
                ticks: ticksStyle
              }]
            }
          }
        });

        $("#btn-download-chart-linea").on('click', function () {       
          var a = document.createElement('a');
          a.href = visitorsChart.toBase64Image();
          a.download = 'ventas_y_pagos_por_mes.png';
          // Trigger the download
          a.click();
        });

        $("#btn-download-chart-barra").on('click', function () {       
          var a = document.createElement('a');
          a.href = salesChart.toBase64Image();
          a.download = 'Compras_y_kilos_por_mes.png';
          // Trigger the download
          a.click();
        });
      } else {
        ver_errores(e);
      } 
  
    }).fail( function(e) { ver_errores(e); } );
  }       
  
  // Ejecutamos los CHARTS
  // chart_producto();
  
})