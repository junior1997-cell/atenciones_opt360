<?php
  //Activamos el almacenamiento en el buffer
  ob_start();

  session_start();
  if (!isset($_SESSION["nombre"])){
    header("Location: index.php?file=".basename($_SERVER['PHP_SELF']));
  }else{
    ?>

    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Cotizacion | Admin JDL</title>
        
        <?php $title = "Otros Ingresos"; require 'head.php'; ?>
          
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['comprobante']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Cotizacion de venta</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="cotizacion_venta.php">Home</a></li>
                        <li class="breadcrumb-item active">Cotizacion</li>
                      </ol>
                    </div>
                  </div>
                </div>
                <!-- /.container-fluid -->
              </section>

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title btn-regresar" style="display: none;">
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form_cotizacion(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-primary" onclick="limpiar_form_cotizacion(); show_hide_form(2);"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente las cotizaciones.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="mostrar-tabla">
                            <table id="tabla-comprobante" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Persona</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comp.</th>
                                  <th>Fecha</th>
                                  <th>Total</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">CFDI</th>

                                  <th>Nombre</th>
                                  <th>Tipo doc.</th>
                                  <th>Num. doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                  <th>Subtotal</th>
                                  <th>IGV</th>
                                  <th>Gravada</th>

                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="">Acciones</th>
                                  <th class="">Persona</th>
                                  <th data-toggle="tooltip" data-original-title="Forma Pago">Forma P.</th>
                                  <th data-toggle="tooltip" data-original-title="Tipo Comprobante">Tipo Comp.</th>
                                  <th>Fecha</th>
                                  <th class="text-nowrap px-2">0.00</th>
                                  <th>Descripción</th>
                                  <th data-toggle="tooltip" data-original-title="Comprobante">CFDI</th>

                                  <th>Nombre</th>
                                  <th>Tipo doc.</th>
                                  <th>Num. doc.</th>
                                  <th>Tipo Comprobante</th>
                                  <th>Num. Comprobante</th>
                                  <th>Subtotal</th>
                                  <th>IGV</th>
                                  <th>Gravada</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                          <!-- FORM - AGREGAR COMPRA-->
                          <div id="div-agregar-compras" style="display: none;">
                            <div class="modal-body p-0px mb-2">
                              <!-- form start -->
                              <form id="form-cotizacion" name="form-cotizacion" method="POST">
                                 
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id compra_producto  -->
                                  <input type="hidden" name="idcotizacion" id="idcotizacion" />                                   

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
                                      <label for="fecha_cotizacion">Fecha <sup class="text-danger">*</sup></label>
                                      <input type="date" name="fecha_cotizacion" id="fecha_cotizacion" class="form-control" placeholder="Fecha" />
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-lg-4" >
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante <sup class="text-danger">(único*)</sup></label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2"   placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Cotizacion" selected>Cotizacion</option>
                                      </select>
                                    </div>
                                  </div> 

                                  <!-- serie_comprobante-->
                                  <div class="col-lg-2" >
                                    <div class="form-group">
                                      <label for="serie_comprobante">Serie <sup class="text-danger">(único*)</sup></label>
                                      <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" placeholder="Serie de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- numero_comprobante-->
                                  <div class="col-lg-2" >
                                    <div class="form-group">
                                      <label for="numero_comprobante">Número <sup class="text-danger">(único*)</sup></label>
                                      <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" placeholder="N° de Comprobante" />
                                    </div>
                                  </div>

                                  <!-- ¿Precios con IGV? -->
                                  <div class="col-lg-2">
                                    <div class="form-group">
                                      <label for="precio_con_igv">¿Precios con IGV? </label>
                                      <select name="precio_con_igv" id="precio_con_igv" class="form-control select2"  placeholder="Seleccionar" onchange="default_val_igv(); modificarSubtotales(); ocultar_comprob();">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                      </select>
                                    </div>
                                  </div> 

                                  <!-- IGV-->
                                  <div class="col-lg-2" >
                                    <div class="form-group">
                                      <label for="impuesto">IGV <sup class="text-danger">*</sup></label>
                                      <input type="text" name="impuesto" id="impuesto" class="form-control" value="18" onkeyup="modificarSubtotales();" />
                                      <input type="hidden" name="impuesto_decimal" id="impuesto_decimal" class="form-control" value="0.18" />
                                    </div>
                                  </div>                                  
                                  
                                  <!-- Forma de pago -->
                                  <div class="col-lg-4" >
                                    <div class="form-group">
                                      <label for="forma_pago">Forma de Pago: </label>
                                      <select name="forma_pago" id="forma_pago" class="form-control select2"    placeholder="Seleccinar">
                                        <option value="Contado">Contado</option>
                                        <option value="Crédito a 7 días">Crédito a 7 días</option>
                                        <option value="Crédito a 15 días">Crédito a 15 días</option>
                                        <option value="Crédito a 30 días">Crédito a 30 días</option>
                                        <option value="Crédito a 45 días">Crédito a 45 días</option>
                                        <option value="Crédito a 60 días">Crédito a 60 días</option>
                                        <option value="Crédito a 90 días">Crédito a 90 días</option>
                                        <option value="Crédito a 120 días">Crédito a 120 días</option>
                                        <option value="50% anticipado y 50% antes de salir de planta">50% anticipado y 50% antes de salir de planta</option>
                                        <option value="Contraentrega">Contraentrega</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Yape">Yape</option>
                                        <option value="Plin">Plin</option>
                                        <option value="Reposición">Reposición</option>
                                        <option value="Control de Calidad">Control de Calidad</option>
                                      </select>
                                    </div>
                                  </div>                                   

                                  <!-- Tiempo de producción -->
                                  <div class="col-lg-4" >
                                    <div class="form-group">
                                      <label for="tiempo_produccion">Tiempo de producción </label>
                                      <select name="tiempo_produccion" id="tiempo_produccion" class="form-control select2"  placeholder="Seleccionar">
                                        <option value="2 días despues de contar con la aprobación y abono">2 días despues de contar con la aprobación y abono</option>
                                        <option value="3 días despues de contar con la aprobación y abono">3 días despues de contar con la aprobación y abono</option>
                                        <option value="4 días despues de contar con la aprobación y abono">4 días despues de contar con la aprobación y abono</option>
                                        <option value="5 días despues de contar con la aprobación y abono">5 días despues de contar con la aprobación y abono</option>
                                        <option value="6 días despues de contar con la aprobación y abono">6 días despues de contar con la aprobación y abono</option>
                                      </select>
                                    </div>
                                  </div>                                     

                                  <!-- Validez de la cotización: -->
                                  <div class="col-lg-4" >
                                    <div class="form-group">
                                      <label for="validez_cotizacion">Validez de la cotización: </label>
                                      <select name="validez_cotizacion" id="validez_cotizacion" class="form-control select2"  placeholder="Seleccionar">
                                        <option value="3 Días calendario">3 Días calendario</option>
                                        <option value="7 Días calendario">7 Días calendario</option>
                                        <option value="15 Días calendario">15 Días calendario</option>
                                        <option value="30 Días calendario">30 Días calendario</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- Descripcion-->
                                  <div class="col-lg-8" >
                                    <div class="form-group">
                                      <label for="nota">Nota </label> <br />
                                      <textarea name="nota" id="nota" class="form-control" rows="1"></textarea>
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
                                        <th data-toggle="tooltip" data-original-title="Precio Unitario">P/U <small>(venta)</small></th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                      </thead>
                                      <tfoot>
                                        <td colspan="5" id="colspan_subtotal"></td>
                                        <th class="text-right">
                                          <h6 >SUBTOTAL</h6>
                                          <h6 >DESCUENTO</h6>
                                          <h6 class="val_igv">IGV (18%)</h6>
                                          <h5 class="font-weight-bold">TOTAL</h5>
                                        </th>
                                        <th class="text-right"> 
                                          <h6 class="font-weight-bold subtotal_cotizacion">S/ 0.00</h6>
                                          <input type="hidden" name="subtotal_cotizacion" id="subtotal_cotizacion" />

                                          <h6 class="font-weight-bold descuento_cotizacion">S/ 0.00</h6>
                                          <input type="hidden" name="descuento_cotizacion" id="descuento_cotizacion" />

                                          <h6 class="font-weight-bold igv_cotizacion">S/ 0.00</h6>
                                          <input type="hidden" name="igv_cotizacion" id="igv_cotizacion" />
                                          
                                          <h5 class="font-weight-bold total_cotizacion">S/ 0.00</h5>
                                          <input type="hidden" name="total_cotizacion" id="total_cotizacion" />
                                          
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
                                 
                                <button type="submit" style="display: none;" id="submit-form-cotizacion">Submit</button>
                              </form>
                            </div>

                            <div class="modal-footer justify-content-between pl-0 pb-0 ">
                              <button type="button" class="btn btn-danger" onclick="show_hide_form(1);" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-success" style="display: none;" id="guardar_registro_cotizacion">Guardar Cambios</button>
                            </div>
                          </div>
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
                
                <!-- MODAL - AGREGAR PERSONA - chargue 3-->
                <div class="modal fade" id="modal-agregar-persona">
                  <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title btn_add">Agregar persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="card-body px-1 py-1">
                          <div class="row">                              
                            <div class=" col-12 col-sm-12">
                              <div class="card card-primary card-outline card-outline-tabs mb-0">
                                <div class="card-header p-0 border-bottom-0">
                                  <ul class="nav nav-tabs lista-items" id="tabs-for-tab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a>
                                    </li>           
                                  </ul> 

                                </div>
                                <div class="card-body" > 
                                  <div class="tab-content" id="tabs-for-tabContent">
                                    <!-- FORMULARIO - PERSONA -->
                                    <div class="tab-pane fade show active" id="tabs-for-activo-fijo" role="tabpanel" aria-labelledby="tabs-for-activo-fijo-tab">
                                      <div class="row">                                                                               
                                        <div class="col-12">
                                          <!-- form start ::::::::::::::::::::::::::::::::::::::::::::::-->
                                          <form id="form-persona" name="form-persona" method="POST">
                                            <div class="card-body">

                                              <div class="row" id="cargando-3-fomulario">
                                                <!-- id persona -->
                                                <input type="hidden" name="idpersona" id="idpersona" />
                                                <!-- tipo persona  -->
                                                <input type="hidden" name="id_tipo_persona" id="id_tipo_persona" />

                                                <!-- Tipo de documento -->
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-2 div_tipo_doc">
                                                  <div class="form-group">
                                                    <label for="tipo_documento">Tipo Doc.</label>
                                                    <select name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                                      <option selected value="DNI">DNI</option>
                                                      <option value="RUC">RUC</option>
                                                      <option value="CEDULA">CEDULA</option>
                                                      <option value="OTRO">OTRO</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <!-- N° de documento -->
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 div_num_doc">
                                                  <div class="form-group">
                                                    <label for="num_documento">N° de documento</label>
                                                    <div class="input-group">
                                                      <input type="number" name="num_documento" class="form-control" id="num_documento" placeholder="N° de documento" />
                                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('');">
                                                        <span class="input-group-text" style="cursor: pointer;">
                                                          <i class="fas fa-search text-primary" id="search"></i>
                                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge" style="display: none;"></i>
                                                        </span>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                <!-- Nombre -->
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-5 div_nombre">
                                                  <div class="form-group">
                                                    <label for="nombre">Nombres/Razon Social</label>
                                                    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres y apellidos" />
                                                  </div>
                                                </div>

                                                <!-- Telefono -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 div_telefono">
                                                  <div class="form-group">
                                                    <label for="telefono">Teléfono</label>
                                                    <input type="text" name="telefono" id="telefono" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                                  </div>
                                                </div>

                                                <!-- Correo electronico -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_correo">
                                                  <div class="form-group">
                                                    <label for="email">Correo electrónico</label>
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
                                                  </div>
                                                </div>

                                                <!-- fecha de nacimiento -->
                                                <div class="col-12 col-sm-10 col-md-6 col-lg-3 div_fecha_nacimiento">
                                                  <div class="form-group">
                                                    <label for="nacimiento">Fecha Nacimiento</label>
                                                    <input
                                                      type="date"
                                                      class="form-control"
                                                      name="nacimiento"
                                                      id="nacimiento"
                                                      placeholder="Fecha de Nacimiento"
                                                      onclick="calcular_edad('#nacimiento', '#edad', '.edad');"
                                                      onchange="calcular_edad('#nacimiento', '#edad', '.edad');"
                                                    />
                                                    <input type="hidden" name="edad" id="edad" />
                                                  </div>
                                                </div>

                                                <!-- edad -->
                                                <div class="col-12 col-sm-2 col-md-6 col-lg-1 div_edad">
                                                  <div class="form-group">
                                                    <label for="edad">Edad</label>
                                                    <p class="edad" style="border: 1px solid #ced4da; border-radius: 4px; padding: 5px;">0 años.</p>
                                                  </div>
                                                </div>

                                                <!-- Titular de la cuenta -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_titular_cuenta">
                                                  <div class="form-group">
                                                    <label for="titular_cuenta">Titular de la cuenta</label>
                                                    <input type="text" name="titular_cuenta" class="form-control" id="titular_cuenta" placeholder="Titular de la cuenta" />
                                                  </div>
                                                </div>

                                                <!-- banco -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_banco">
                                                  <div class="form-group">
                                                    <label for="banco">Banco</label>
                                                    <select name="banco" id="banco" class="form-control select2 banco" style="width: 100%;" onchange="formato_banco();">
                                                      <!-- Aqui listamos los bancos -->
                                                    </select>
                                                  </div>
                                                </div>

                                                <!-- Cuenta bancaria -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_cta">
                                                  <div class="form-group">
                                                    <label for="cta_bancaria" class="chargue-format-1">Cuenta Bancaria</label>
                                                    <input type="text" name="cta_bancaria" class="form-control" id="cta_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
                                                  </div>
                                                </div>

                                                <!-- CCI -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_cci">
                                                  <div class="form-group">
                                                    <label for="cci" class="chargue-format-2">CCI</label>
                                                    <input type="text" name="cci" class="form-control" id="cci" placeholder="CCI" data-inputmask="" data-mask />
                                                  </div>
                                                </div>                             
                                                
                                                <!-- cargo_trabajador  -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 div_cargo">
                                                  <div class="form-group">
                                                    <label for="cargo_trabajador">Cargo </label>
                                                    <select name="cargo_trabajador" id="cargo_trabajador" class="form-control select2 cargo_trabajador" style="width: 100%;">
                                                      <!-- Aqui listamos los cargo_trabajador -->
                                                    </select>
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Mensual) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 div_sueldo_mensual">
                                                  <div class="form-group">
                                                    <label for="sueldo_mensual">Sueldo(Mensual)</label>
                                                    <input type="number" step="any" name="sueldo_mensual" class="form-control" id="sueldo_mensual" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Diario) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 div_sueldo_diario">
                                                  <div class="form-group">
                                                    <label for="sueldo_diario">Sueldo(Diario)</label>
                                                    <input type="number" step="any" name="sueldo_diario" class="form-control" id="sueldo_diario" readonly />
                                                  </div>
                                                </div>
                                                

                                                <!-- Direccion -->
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 div_direccion">
                                                  <div class="form-group">
                                                    <label for="direccion">Dirección</label>
                                                    <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" />
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


                                                <!-- barprogress -->
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_persona_div" style="display: none;">
                                                  <div class="progress" >
                                                    <div id="barra_progress_persona" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                                      0%
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>

                                              <div class="row" id="cargando-4-fomulario" style="display: none;" >
                                                <div class="col-lg-12 text-center">
                                                  <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
                                                  <h4>Cargando...</h4>
                                                </div>
                                              </div>
                                                    
                                            </div>
                                            <!-- /.card-body -->
                                            <button type="submit" style="display: none;" id="submit-form-persona">Submit</button>
                                          </form>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                      <!-- /.row -->
                                    </div>                                    
                                  </div>
                                  <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                              </div>
                            </div>
                            <!-- /.col -->
                          </div>                          
                        </div>
                        
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="limpiar_form_persona();" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_persona">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER COMPROBANTE-->
                <div class="modal fade" id="modal-ver-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Otros Ingresos: <span class="nombre_comprobante text-bold"></span> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-warning" href="#" id="iddescargar" download="" type="button"><i class="fas fa-download"></i> Descargar</a>
                          </div>
                          <div class="col-6 col-md-6">
                            <a class="btn btn-xs btn-block btn-info" href="#" id="ver_completo"  target="_blank" type="button"><i class="fas fa-expand"></i> Ver completo.</a>
                          </div>
                          <div class="col-12 col-md-12 mt-2">
                            <div id="ver_fact_pdf" width="auto"></div>
                          </div>
                        </div>                          
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL - VER DETALLE DE OTRO INGRESO -->
                <div class="modal fade" id="modal-ver-detalle-comprobante">
                  <div class="modal-dialog modal-dialog-scrollable modal-xm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Datos otros Ingresos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body"> 
                        <div id="datos_otro_ingreso" class="class-style">
                          <!-- vemos los datos del trabajador -->
                        </div>
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

              </section>
              <!-- /.content -->
            </div>

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->

        <?php require 'script.php'; ?>
        
        <!-- Funciones del modulo -->
        <script type="text/javascript" src="scripts/cotizacion_venta.js"></script>
        <script type="text/javascript" src="scripts/js_cotizacion.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
