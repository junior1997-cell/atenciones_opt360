<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  session_start();

  if (!isset($_SESSION["nombre"])){

    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));

  }else{ ?>

    <!DOCTYPE html> 
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title> Compras Producto | Admin JDL </title>

        <?php $title = "Compras Producto"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
        <link rel="stylesheet" href="../dist/css/leyenda.css" />
        
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <div class="wrapper">

          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['comprobante']==1){
            //require 'enmantenimiento.php';
            ?>
            <!--Contenido-->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0"><i class="nav-icon fas fa-cart-plus"></i> Almacén - Aumenta tu stock</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="compra_insumos.php">Home</a></li>
                        <li class="breadcrumb-item active">Compras</li>
                      </ol>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
              </div>
              <!-- /.content-header -->

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <!-- Start Main Top -->
                        <div class="main-top">
                          <div class="container-fluid border-bottom">
                            <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                <div class="card-header">
                                  <h3 class="card-title">
                                    <!--data-toggle="modal" data-target="#modal-agregar-compra"  onclick="limpiar();"-->
                                    <button type="button" class="btn bg-gradient-primary" id="btn_agregar" onclick="ver_form_add(); limpiar_form_compra();">
                                      <i class="fas fa-plus-circle"></i> Agregar
                                    </button>                                    
                                    <button type="button" class="btn bg-gradient-warning" id="regresar" style="display: none;" onclick="regresar();">
                                      <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="button" id="btn-pagar" class="btn bg-gradient-primary" style="display: none;" data-toggle="modal"  data-target="#modal-agregar-pago" onclick="limpiar_form_pago_compra();">
                                      <i class="fas fa-dollar-sign"></i> Agregar Pago
                                    </button>                                     
                                  </h3>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                        <!-- End Main Top -->

                        <!-- /.card-header -->
                        <div class="card-body">
                          <!-- TABLA - COMPRAS -->
                          <div id="div_tabla_compra">
                            <h5><b>Lista de ingresos</b></h5>
                            <!-- filtros -->
                            <div class="filtros-inputs row mb-4">

                              <!-- filtro por: fecha inicial -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">    
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha inicio </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-inicio" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_inicio" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div>                                
                              </div>

                              <!-- filtro por: fecha final -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">                                
                                <div class="form-group">
                                  <!-- <label for="filtro_fecha_inicio" >Fecha fin </label> -->
                                  <div class="input-group date"  >
                                    <div class="input-group-append cursor-pointer click-btn-fecha-fin" >
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" class="form-control"  id="filtro_fecha_fin" onchange="cargando_search(); delay(function(){filtros()}, 50 );" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask autocomplete="off" />                                    
                                  </div>
                                </div> 
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                  <select id="filtro_proveedor" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                  </select>
                                </div>
                                
                              </div>

                              <!-- filtro por: proveedor -->
                              <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                <div class="form-group">
                                  <!-- <label for="filtros" >Tipo comprobante </label> -->
                                  <select id="filtro_tipo_comprobante" class="form-control select2" onchange="cargando_search(); delay(function(){filtros()}, 50 );" style="width: 100%;"> 
                                    <option value="0">Todos</option>
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                                
                              </div>
                            </div>
                            <!-- /.filtro -->
                            
                            <table id="tabla-compra" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="9" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th>Total</th>
                                  <th>Descripción</th>

                                  <th>Tipo comprobante</th>
                                  <th>Num. Comprobante</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Proveedor</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo y Número Comprobante">Tipo</th>
                                  <th class="px-2">S/. 0.00</th>
                                  <th>Descripción</th>

                                  <th>Tipo comprobante</th>
                                  <th>Num. Comprobante</th>
                                </tr>
                              </tfoot>
                            </table>
                            <!-- /. table-compra -->
                            <br />
                            <h4><b>Lista de Compras Por Proveedor</b></h4>
                            <table id="tabla-compra-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th> 
                                  <th>Proveedor</th>
                                  <th>Cant</th>
                                  <th>Cel.</th>
                                  <th class="px-2">Total</th>
                                </tr>
                              </tfoot>
                            </table>
                            <!-- /.table-compras-por-proveedor -->
                          </div>

                          <!-- TABLA - COMPRAS POR PROVEEDOR -->
                          <div id="div_tabla_compra_proveedor" style="display: none;">
                            <h5><b>Lista de compras Por Facturas</b></h5>
                            <table id="detalles-tabla-compra-prov" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>Descripcion</th>                                  
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="">#</th>
                                  <th class="">Acciones</th>
                                  <th>Fecha</th>
                                  <th>Comprobante</th>
                                  <th data-toggle="tooltip" data-original-title="Número Comprobante">Num. Comprobante</th>
                                  <th>Total</th>
                                  <th>Descripcion</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- FORM - AGREGAR COMPRA-->
                          <div id="agregar_compras" style="display: none;">
                            <div class="modal-body p-0px mb-2">
                              <!-- form start -->
                              <form id="form-compras" name="form-compras" method="POST">
                                 
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id compra_producto  -->
                                  <input type="hidden" name="idcompra_producto" id="idcompra_producto" /> 
                                  <input type="hidden" name="num_doc" id="num_doc" /> 

                                  <!-- Tipo de Empresa -->
                                  <div class="col-lg-7">
                                    <div class="form-group">
                                      <label for="idproveedor">Proveedor <sup class="text-danger">(único*)</sup></label>
                                      <select id="idproveedor" name="idproveedor" class="form-control select2" data-live-search="true" required title="Seleccione cliente" onchange="extrae_ruc('#idproveedor', '#num_doc');"> </select>
                                    </div>
                                  </div>

                                  <!-- adduser -->
                                  <div class="col-lg-1">
                                    <div class="form-group">
                                    <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                      <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                        <button type="button" class="btn btn-success p-x-6px" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                      <button type="button" class="btn btn-warning p-x-6px btn-editar-proveedor" data-toggle="tooltip" data-original-title="Editar:" onclick="mostrar_para_editar_proveedor();">
                                        <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <!-- fecha -->
                                  <div class="col-lg-4" >
                                    <div class="form-group">
                                      <label for="fecha_compra">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" placeholder="Fecha" />
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-lg-4" id="content-tipo-comprobante">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"  onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div> 

                                  <!-- serie_comprobante-->
                                  <div class="col-lg-2" id="content-serie-comprobante">
                                    <div class="form-group">
                                      <label for="serie_comprobante">N° de Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- IGV-->
                                  <div class="col-lg-1" id="content-igv">
                                    <div class="form-group">
                                      <label for="val_igv">IGV <sup class="text-danger">*</sup></label>
                                      <input type="text" name="val_igv" id="val_igv" class="form-control" value="0.18" onkeyup="modificarSubtotales();" />
                                    </div>
                                  </div>

                                  <!-- Descripcion-->
                                  <div class="col-lg-5" id="content-descripcion">
                                    <div class="form-group">
                                      <label for="descripcion">Descripción </label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="1"></textarea>
                                    </div>
                                  </div>                                  

                                  <!--Boton agregar material-->
                                  <div class="row col-lg-12 justify-content-between">
                                    <div class="col-lg-4 col-xs-12">
                                      <div class="row">
                                        <div class="col-lg-6">
                                            <label for="" style="color: white;">.</label> <br />
                                            <a data-toggle="modal" data-target="#modal-elegir-material">
                                              <button id="btnAgregarArt" type="button" class="btn btn-primary btn-block"><span class="fa fa-plus"></span> Agregar Productos</button>
                                            </a>
                                        </div>
                                      </div>
                                    </div>

                                  </div>

                                  <!--tabla detalles plantas-->
                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive row-horizon disenio-scroll">
                                    <br />
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                      <thead style="background-color: #28a745b5;">
                                        <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                                        <th>Producto</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                        <th class="hidden" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U </th>
                                        <th class="hidden">IGV</th>
                                        <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U <small>(unitario)</small></th>
                                        <th data-toggle="tooltip" data-original-title="Precio Venta">P/V <small>(venta)</small></th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                      </thead>
                                      <tfoot>
                                        <td colspan="5" id="colspan_subtotal"></td>
                                        <th class="text-right">
                                          <h6 class="tipo_gravada">GRAVADA</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_compra">S/ 0.00</h6>
                                          <input type="hidden" name="subtotal_compra" id="subtotal_compra" />
                                          <input type="hidden" name="tipo_gravada" id="tipo_gravada" />

                                          <h6 class="font-weight-bold igv_compra">S/ 0.00</h6>
                                          <input type="hidden" name="igv_compra" id="igv_compra" />
                                          
                                          <h5 class="font-weight-bold total_venta">S/ 0.00</h5>
                                          <input type="hidden" name="total_venta" id="total_venta" />
                                          
                                        </th>
                                      </tfoot>
                                      <tbody></tbody>
                                    </table>
                                  </div>                                    
                                </div>

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>                                 
                                 
                                <button type="submit" style="display: none;" id="submit-form-compras">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between pl-0 pb-0 ">
                              <button type="button" class="btn btn-danger" onclick="regresar();" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_compras">Guardar Cambios</button>
                            </div>
                          </div>

                          <!-- TABLA - FACTURAS COMPRAS-->
                          <div id="factura_compras" style="display: none;">
                            <h5><b>Lista de compras Por Facturas</b></h5>

                            <!--<div style="text-align:center;"> <h4 style="background: aliceblue;">Costo parcial: <b id="total_costo" style="color: #e52929;"></b> </h5> </div>-->
                            <table id="tabla_facturas" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>Aciones</th>
                                  <th>Código</th>
                                  <th>Fecha Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th>Monto</th>
                                  <th>Descripción</th>
                                  <th>Factura</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th>Aciones</th>
                                  <th>Código</th>
                                  <th>Fecha Emisión</th>
                                  <th>Sub total</th>
                                  <th>IGV</th>
                                  <th id="monto_total_f" style="color: #ff0000; background-color: #f3e700;"></th>
                                  <th>Descripción</th>
                                  <th>Factura</th>
                                  <th>Estado</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- TABLA - PAGOS SIN DETRACCION -->
                          <div id="pago_compras" style="display: none;">
                            <h5>pago Compras</h5>
                            <div style="text-align: center;">
                              <div>
                                <h4>Total a pagar: <b id="total_compra"></b></h4>
                              </div>
                              <table id="tabla-pagos-proveedor" class="table table-bordered table-striped display" style="width: 100% !important;">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    <th data-toggle="tooltip" data-original-title="Forma Pago">Forma</th>
                                    <th>Beneficiario</th>
                                    <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                    <th>Monto</th>
                                    <th>Vaucher</th>
                                    <th>Estado</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                  <tr>
                                    <th>#</th>
                                    <th>Aciones</th>
                                    <th>Forma</th>
                                    <th>Beneficiario</th>                                     
                                    <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                    <th>Descripción</th>
                                    <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                    <th style="color: #ff0000; background-color: #45c920;">
                                      <b id="monto_total"></b> <br />
                                      <b id="porcentaje" style="color: black;"></b>
                                    </th>
                                    <th>Vaucher</th>
                                    <th>Estado</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>

                          <!-- TABLA - PAGOS CON DETRACCION-->
                          <div id="pagos_con_detraccion" style="display: none;">
                            <h5>pagos con detracccion</h5>
                            <div style="text-align: center;">
                              <div>
                                <h4>Total a pagar: <b id="ttl_monto_pgs_detracc"></b></h4>
                              </div>
                              <br />

                              <div style="background-color: aliceblue;">
                                <h5>
                                  Proveedor S/
                                  <b id="t_proveedor"></b>
                                  <input type="hidden" class="t_proveedor" />
                                  <i class="fas fa-arrow-right fa-xs"></i>
                                  <b id="t_provee_porc"></b>
                                  <b>%</b>
                                </h5>
                              </div>
                            </div>
                            <!--tabla 1 t_proveedor, t_provee_porc,t_detaccion, t_detacc_porc -->
                            <table id="tbl-pgs-detrac-prov-cmprs" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Acciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                  <th>Monto</th>
                                  <th>Vaucher</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th>
                                  <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                  <th style="color: #ff0000; background-color: #45c920;">
                                    <b id="monto_total_prov"></b> <br />
                                    <b id="porcnt_prove" style="color: black;"></b>
                                  </th>
                                  <th>Vaucher</th>
                                  <th>Estado</th>
                                </tr>
                                <tr>
                                  <td colspan="6"></td>
                                  <td style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                  <th style="color: #ff0000; background-color: #f3e700;">
                                    <b id="saldo_p"></b> <br />
                                    <b id="porcnt_sald_p" style="color: black;"></b>
                                  </th>
                                  <td colspan="2"></td>
                                </tr>
                              </tfoot>
                            </table>

                            <!--Tabla 2-->
                            <br />
                            <div style="text-align: center;">
                              <div style="background-color: aliceblue;">
                                <h5>
                                  Detracción S/
                                  <b id="t_detaccion"></b>
                                  <input type="hidden" class="t_detaccion" />
                                  <i class="fas fa-arrow-right fa-xs"></i>
                                  <b id="t_detacc_porc"></b>
                                  <b>%</b>
                                </h5>
                              </div>
                            </div>
                            <table id="tbl-pgs-detrac-detracc-cmprs" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Acciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th> 
                                  <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                  <th>Monto</th>
                                  <th>Vaucher</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Aciones</th>
                                  <th>Forma pago</th>
                                  <th>Beneficiario</th> 
                                  <th data-toggle="tooltip" data-original-title="Fecha Pago">Fecha P.</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Número Operación">Número Op.</th>
                                  <th style="color: #ff0000; background-color: #45c920;">
                                    <b id="monto_total_detracc"></b> <br />
                                    <b id="porcnt_detrcc" style="color: black;"></b>
                                  </th>
                                  <th>Vaucher</th>
                                  <th>Estado</th>
                                </tr>
                                <tr>
                                  <td colspan="6"></td>
                                  <td style="font-weight: bold; font-size: 20px; text-align: center;">Saldo</td>
                                  <th style="color: #ff0000; background-color: #f3e700;">
                                    <b id="saldo_d"></b> <br />
                                    <!-- <input type="hidden" class="saldo_d">-->
                                    <b id="porcnt_sald_d" style="color: black;"></b>
                                  </th>
                                  <td colspan="2"></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->

                  <!-- Modal agregar proveedores - charge-11 -->
                  <div class="modal fade" id="modal-agregar-proveedor">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title ">Agregar proveedor</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-proveedor" name="form-proveedor" method="POST">
                            <div class="card-body">

                              <div class="row" id="cargando-11-fomulario">
                                <!-- id persona -->
                                <input type="hidden" name="idpersona_per" id="idpersona_per" />
                                <!-- tipo persona  -->
                                <input type="hidden" name="id_tipo_persona_per" id="id_tipo_persona_per" value="3" />
                                <input type="hidden" name="cargo_trabajador_per" id="cargo_trabajador_per" value="1">
                                <input type="hidden" name="sueldo_mensual_per" id="sueldo_mensual_per">
                                <input type="hidden" name="sueldo_diario_per" id="sueldo_diario_per">
                                <input type="hidden" name="input_socio_per" id="input_socio_per" value="0"  >

                                <!-- Tipo de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-2">
                                  <div class="form-group">
                                    <label for="tipo_documento_per">Tipo Doc.</label>
                                    <select name="tipo_documento_per" id="tipo_documento_per" class="form-control" placeholder="Tipo de documento">
                                      <option selected value="DNI">DNI</option>
                                      <option value="RUC">RUC</option>
                                      <option value="CEDULA">CEDULA</option>
                                      <option value="OTRO">OTRO</option>
                                    </select>
                                  </div>
                                </div>
                                
                                <!-- N° de documento -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                  <div class="form-group">
                                    <label for="num_documento_per">N° de documento</label>
                                    <div class="input-group">
                                      <input type="number" name="num_documento_per" class="form-control" id="num_documento_per" placeholder="N° de documento" />
                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_per');">
                                        <span class="input-group-text" style="cursor: pointer;">
                                          <i class="fas fa-search text-primary" id="search_per"></i>
                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_per" style="display: none;"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Nombre -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                                  <div class="form-group">
                                    <label for="nombre_per">Nombres/Razon Social</label>
                                    <input type="text" name="nombre_per" class="form-control" id="nombre_per" placeholder="Nombres y apellidos" />
                                  </div>
                                </div>
                                
                                <!-- Telefono -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                                  <div class="form-group">
                                    <label for="telefono_per">Teléfono</label>
                                    <input type="text" name="telefono_per" id="telefono_per" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                  </div>
                                </div>

                                <!-- Correo electronico -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="email_per">Correo electrónico</label>
                                    <input type="email" name="email_per" class="form-control" id="email_per" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                  </div>
                                </div>

                                <!-- fecha de nacimiento -->
                                <div class="col-12 col-sm-10 col-md-6 col-lg-3">
                                  <div class="form-group">
                                    <label for="nacimiento_per">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" name="nacimiento_per" id="nacimiento_per" placeholder="Fecha de Nacimiento"
                                      onclick="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');" onchange="calcular_edad('#nacimiento_per', '#edad_per', '.edad_per');" />
                                    <input type="hidden" name="edad_per" id="edad_per" />
                                  </div>
                                </div>

                                <!-- edad -->
                                <div class="col-12 col-sm-2 col-md-6 col-lg-1">
                                  <div class="form-group">
                                    <label for="edad_per">Edad</label>
                                    <p class="edad_per" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                  </div>
                                </div>

                                <!-- banco -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="banco">Banco</label>
                                    <select name="banco" id="banco" class="form-control select2 banco" style="width: 100%;" onchange="formato_banco();">
                                      <!-- Aqui listamos los bancos -->
                                    </select>
                                  </div>
                                </div>

                                <!-- Cuenta bancaria -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="cta_bancaria" class="chargue-format-1">Cuenta Bancaria</label>
                                    <input type="text" name="cta_bancaria" class="form-control" id="cta_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- CCI -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="cci" class="chargue-format-2">CCI</label>
                                    <input type="text" name="cci" class="form-control" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                                  </div>
                                </div>

                                <!-- Titular de la cuenta -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                  <div class="form-group">
                                    <label for="titular_cuenta_per">Titular de la cuenta</label>
                                    <input type="text" name="titular_cuenta_per" class="form-control" id="titular_cuenta_per" placeholder="Titular de la cuenta" />
                                  </div>
                                </div>                                 

                                <!-- Direccion -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 classdirecc">
                                  <div class="form-group">
                                    <label for="direccion_per">Dirección</label>
                                    <input type="text" name="direccion_per" class="form-control" id="direccion_per" placeholder="Dirección" />
                                  </div>
                                </div>

                                <!-- imagen perfil -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                  <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                  <label for="foto1">Foto de perfil</label> <br />
                                  <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png" class="img-thumbnail" id="foto1_i" style="cursor: pointer !important;" width="auto" />
                                  <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                  <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                  <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                </div>

                                <!-- Progress -->
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="progress" id="div_barra_progress" style="display: none !important;">
                                      <div id="barra_progress" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row" id="cargando-12-fomulario" style="display: none;" >
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                                    
                            </div>
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-proveedor">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_proveedor">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL - TABLA PRODUCTO -->
                  <div class="modal fade" id="modal-elegir-material">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title "> 
                            <a data-toggle="modal" data-target="#modal-agregar-productos">
                              <button id="btnAgregarArt" type="button" class="btn btn-success" onclick="limpiar_producto()"><span class="fa fa-plus"></span> Crear Productos</button>
                            </a>
                            Seleccionar producto
                          </h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body table-responsive">
                          <table id="tblamateriales" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                            <thead>
                              <th data-toggle="tooltip" data-original-title="Opciones">Op.</th>
                              <th>Nombre Producto</th>
                              <th>Stock</th>
                              <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U.</th>
                              <th>Descripción</th>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>                  

                  <!-- MDOAL - agregar Pagos - charge-3 -->
                  <div class="modal fade" id="modal-agregar-pago">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar Pago</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-pago-compra" name="form-pago-compra" method="POST">
                             
                            <div class="row" id="cargando-3-fomulario">
                              <!-- id proveedor -->
                              <input type="hidden" name="idproveedor_pago" id="idproveedor_pago" />
                              <!-- idcompras_proyecto -->
                              <input type="hidden" name="idcompra_proyecto_p" id="idcompra_proyecto_p" />
                              <!-- id compras -->
                              <input type="hidden" name="idpago_compras" id="idpago_compras" />
                              <!-- Beneficiario -->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="beneficiario_pago">Beneficiario</label>
                                  <input class="form-control" type="hidden" id="beneficiario_pago" name="beneficiario_pago" />
                                  <br />
                                  <b id="h4_mostrar_beneficiario" style="font-size: 16px; color: red;"> Jheyfer Arevealo Velasco</b>
                                </div>
                              </div>
                              <!--Forma de pago -->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="forma_pago">Forma Pago</label>
                                  <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;" onchange="validar_forma_de_pago();">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Crédito">Crédito</option>
                                  </select>
                                </div>
                              </div>
                              <!--tipo de pago -->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="tipo_pago">Tipo Pago</label>
                                  <select name="tipo_pago" id="tipo_pago" class="form-control select2" style="width: 100%;" onchange="captura_op();">
                                    <option value="Proveedor">Proveedor</option>
                                    <option value="Detraccion">Detracción</option>
                                  </select>
                                </div>
                              </div>
                              <!-- Cuenta de destino-->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="cuenta_destino_pago">Cuenta destino </label>
                                  <input type="text" name="cuenta_destino_pago" id="cuenta_destino_pago" class="form-control" placeholder="Cuenta destino" />
                                </div>
                              </div>
                              <!-- banco -->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="banco_pago">Banco</label>
                                  <select name="banco_pago" id="banco_pago" class="form-control select2" style="width: 100%;">
                                  </select>
                                </div>
                              </div>
                              <!-- Titular Cuenta-->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="titular_cuenta_pago">Titular Cuenta </label>
                                  <input type="text" name="titular_cuenta_pago" id="titular_cuenta_pago" class="form-control" placeholder="Titular Cuenta" />
                                </div>
                              </div>

                              <!-- Fecha Inicio-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="fecha_pago">Fecha Pago </label>
                                  <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" />
                                </div>
                              </div>
                              <!-- Monto-->
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="monto_pago">Monto </label>
                                  <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" placeholder="Ingrese monto" onkeyup="validando_excedentes();" onchange="validando_excedentes();" />
                                </div>
                              </div>
                              <!-- Número de Operación-->
                              <div class="col-lg-6 validar_fp">
                                <div class="form-group">
                                  <label for="numero_op_pago">Número de operación </label>
                                  <input type="number" name="numero_op_pago" id="numero_op_pago" class="form-control" placeholder="Número de operación" />
                                </div>
                              </div>
                              <!-- Descripcion-->
                              <div class="col-lg-12">
                                <div class="form-group">
                                  <label for="descripcion_pago">Descripción </label> <br />
                                  <textarea name="descripcion_pago" id="descripcion_pago" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                              <!--vaucher-->                              
                              <div class="col-md-6 col-lg-6">
                                <div class="col-lg-12 borde-arriba-naranja mt-2 mb-2"></div>
                                <label for="doc3_i" >Comprobante <b class="text-danger">(Imagen o PDF)</b> </label>  
                                <div class="row text-center">                               
                                  <!-- Subir documento -->
                                  <div class="col-6 col-md-6 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc3_i">
                                      <i class="fas fa-upload"></i> Subir.
                                    </button>
                                    <input type="hidden" id="doc_old_3" name="doc_old_3" />
                                    <input style="display: none;" id="doc3" type="file" name="doc3" accept="application/pdf, image/*" class="docpdf" /> 
                                  </div>
                                  <!-- Recargar -->
                                  <div class="col-6 col-md-6 text-center comprobante">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(3, 'compra' ,'comprobante_pago'); reload_zoom();">
                                    <i class="fas fa-redo"></i> Recargar.
                                  </button>
                                  </div>                                  
                                </div>
                                <div id="doc3_ver" class="text-center mt-4">
                                  <img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >
                                </div>
                                <div class="text-center" id="doc3_nombre"><!-- aqui va el nombre del pdf --></div>
                              </div>

                            </div>

                            <div class="row" id="cargando-4-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                             
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-pago">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_pago_compra();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_pago">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL - DETALLE compras - charge-5 -->
                  <div class="modal fade" id="modal-ver-compras">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Detalle Compra</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <div class="row detalle_de_compra" id="cargando-5-fomulario">                            
                            <!--detalle de la compra-->
                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <a type="button" class="btn btn-success float-right" id="excel_compra" target="_blank" ><i class="far fa-file-excel"></i> Excel</a>
                          <a type="button" class="btn btn-info" id="print_pdf_compra" target="_blank" ><i class="fas fa-print"></i> Imprimir/PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL -  AGREGAR comprobantes - charge-7 -->                   
                  <div class="modal fade bg-color-02020263" id="modal-comprobantes-compra">
                    <div class="modal-dialog  modal-dialog-scrollable modal-md shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                      <div class="modal-content">
                        <div class="modal-header"> 
                          <h4 class="modal-title titulo-comprobante-compra">Comprobantes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body ">
                          <!-- form start -->
                          <form id="form-comprobante" name="form-comprobante" method="POST" >
                             
                            <div class="row mx-2" id="cargando-7-fomulario">
                              <!-- id Comprobante -->
                              <input type="hidden" name="id_compra_proyecto" id="id_compra_proyecto" />
                              <input type="hidden" name="idfactura_compra_insumo" id="idfactura_compra_insumo" />

                              <!-- Doc  -->
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                                <div class="row">
                                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pl-0 mb-3 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-file-upload"></i> Subir.</button>
                                    <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                    <input style="display: none;" id="doc1" type="file" name="doc1" class="docpdf" accept="application/pdf, image/*" />
                                  </div>
                                  <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pr-0 mb-3 text-center">
                                    <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'compra_insumo', 'comprobante_compra', '100%', '320'); reload_zoom();"><i class="fa fa-eye"></i> Recargar.</button>
                                  </div>                                                                     
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mt-1" id="doc1_ver"> 
                                    <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />                           
                                  </div>                                                                
                                  <div class="col-12 col-sm-12 col-md-7 col-lg-12 col-xl-12 text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>                                                                   
                                </div>
                              </div>
                              <!-- barprogress -->
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 mb-3" style="margin-top: 20px;">
                                <div class="progress" id="barra_progress_comprobante_div">
                                  <div id="barra_progress_comprobante" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                    0%
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="cargando-8-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">
                                <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                <br />
                                <h4>Cargando...</h4>
                              </div>
                            </div>
                             
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-comprobante-compra"></button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success btn-sm float-right" id="guardar_registro_comprobante_compra" >Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- MODAL - ver-vaucher-pagos -->
                  <div class="modal fade" id="modal-ver-vaucher">
                    <div class="modal-dialog modal-dialog-scrollable modal-xm">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #ce834926;">
                          <h4 class="modal-title">Voucher</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-center ver-comprobante-pago"> 
                          
                        </div>
                      </div>
                    </div>
                  </div> 

                  <!-- MODAL - VER PERFIL INSUMO-->
                  <div class="modal fade bg-color-02020280" id="modal-ver-perfil-insumo">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                      <div class="modal-content bg-color-0202022e shadow-none border-0">
                        <div class="modal-header">
                          <h4 class="modal-title text-white foto-insumo">Foto Insumo</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body"> 
                          <div id="perfil-insumo" class="text-center">
                            <!-- vemos los datos del trabajador -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>   

                  <!-- MODAL - AGREGAR Productos - charge-9 -->                 
                  <div class="modal fade bg-color-02020263" id="modal-agregar-productos">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title name-modal-title-agregar">Agregar Producto</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="text-danger" aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <div class="modal-body">
                          <!-- form start -->
                          <form id="form-producto" name="form-producto" method="POST">
                            <div class="card-body">
                              <div class="row" id="cargando-9-fomulario">
      
                                <!-- id proveedores -->
                                <input type="hidden" name="idproducto_pro" id="idproducto_pro" />
                                <!-- cont registro -->
                                <input type="hidden" name="cont" id="cont" />
                                
                                <!-- Nombre -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                  <div class="form-group">
                                    <label for="nombre_producto_pro">Nombre <sup class="text-danger">(unico*)</sup></label>
                                    <input type="text" name="nombre_producto_pro" class="form-control" id="nombre_producto_pro" placeholder="Nombre del Insumo." />
                                  </div>
                                </div>

                                <!-- Categoria -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="categoria_producto_pro">Categoria <sup class="text-danger">(unico*)</sup></label>
                                    <select name="categoria_producto_pro" id="categoria_producto_pro" class="form-control select2" style="width: 100%;"> 
                                    </select>
                                  </div>
                                </div>                                  

                                <!-- Unnidad de medida-->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6" >
                                  <div class="form-group">
                                    <label for="unidad_medida_pro">Unidad medida <sup class="text-danger">(unico*)</sup></label>
                                    <select name="unidad_medida_pro" id="unidad_medida_pro" class="form-control select2" style="width: 100%;"> </select>
                                  </div>
                                </div>

                                <!-- Marca -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="marca_pro">Marca <sup class="text-danger">(unico*)</sup></label>
                                    <input type="text" name="marca_pro" id="marca_pro" class="form-control"  placeholder="Marca." />
                                  </div>
                                </div>

                                <!-- ContenidoNeto -->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="contenido_neto_pro">Contenido Neto <sup class="text-danger">(unico*)</sup></label>
                                    <input type="text" name="contenido_neto_pro" id="contenido_neto_pro" class="form-control" placeholder="Contenido Neto." />
                                  </div>
                                </div>

                                <!--Precio U-->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                  <div class="form-group">
                                    <label for="precio_unitario_pro">Precio <sup class="text-danger">*</sup></label>
                                    <input type="text" name="precio_unitario_pro" id="precio_unitario_pro" value="0" class="form-control" readonly />
                                  </div>
                                </div>

                                <!-- Stock -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6x">
                                  <div class="form-group">
                                    <label for="stock_pro">Stock: </label>
                                    <input type="text" name="stock_pro" id="stock_pro" value="0" class="form-control" readonly="readonly">                                  
                                  </div>
                                </div>

                                <!-- Descripcion -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                  <div class="form-group">
                                    <label for="descripcion_pro">Descripción </label> <br />
                                    <textarea name="descripcion_pro" id="descripcion_pro" class="form-control" rows="2"></textarea>
                                  </div>
                                </div>

                                <!--imagen-material-->
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                  <label for="foto2">Imagen</label>
                                  <div style="text-align: center;">
                                    <img onerror="this.src='../dist/img/default/img_defecto_producto.png';" src="../dist/img/default/img_defecto_producto.png" class="img-thumbnail"
                                      id="foto2_i" style="cursor: pointer !important; height: 100% !important;" width="auto" />
                                    <input style="display: none;" type="file" name="foto2" id="foto2" accept="image/*" />
                                    <input type="hidden" name="foto2_actual" id="foto2_actual" />
                                    <div class="text-center" id="foto2_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                  </div>
                                </div>
                                
                                <!-- barprogress -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                  <div class="progress" id="barra_progress_div">
                                    <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                      0%
                                    </div>
                                  </div>
                                </div>

                              </div>

                              <div class="row" id="cargando-10-fomulario" style="display: none;">
                                <div class="col-lg-12 text-center">
                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                  <br />
                                  <h4>Cargando...</h4>
                                </div>
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <button type="submit" style="display: none;" id="submit-form-producto">Submit</button>
                          </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_producto();">Close</button>
                          <button type="submit" class="btn btn-success" id="guardar_registro_material">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </section>
              <!-- /.content -->
            </div>
            <!--Fin-Contenido-->

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        
        <?php require 'script.php'; ?>

        <!-- ZIP -->
        <script src="../plugins/jszip/jszip.js"></script>
        <script src="../plugins/jszip/dist/jszip-utils.js"></script>
        <script src="../plugins/FileSaver/dist/FileSaver.js"></script>
        
        <script type="text/javascript" src="scripts/compra_producto.js"></script>         

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>
    <?php    
  }

  ob_end_flush();
?>
