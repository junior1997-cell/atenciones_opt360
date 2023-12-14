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
        <title>Comprobantes | Admin JDL</title>
        
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
                      <h1>Comprobantes</h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="comprobante.php">Home</a></li>
                        <li class="breadcrumb-item active">Comprobantes</li>
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
                            <button type="button" class="btn bg-gradient-warning" onclick="limpiar_form(); show_hide_form(1);"><i class="fas fa-arrow-left"></i> Regresar</button>                            
                          </h3>
                          <h3 class="card-title btn-agregar">
                            <button type="button" class="btn bg-gradient-primary" onclick="limpiar_form(); show_hide_form(2);"><i class="fas fa-plus-circle"></i> Agregar</button>
                            Administra de manera eficiente los comprobantes.
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">                        

                          <div id="mostrar-tabla">

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
                                    <option value="Recibo por Honorario">Recibo por Honorario</option>
                                    <option value="Nota de venta">Nota de venta</option>
                                  </select>
                                </div>
                                
                              </div>
                            </div>
                            <!-- /.filtro -->

                            <table id="tabla-comprobante" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th colspan="17" class="cargando text-center bg-danger"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                                </tr>
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

                          <div id="mostrar-form" style="display: none;">
                            
                            <!-- form start -->
                            <form id="form-comprobante" name="form-comprobante" method="POST">
                              <div class="card-body">
                                <div class="row" id="cargando-1-fomulario">
                                  <!-- id hospedaje -->
                                  <input type="hidden" name="idcomprobante" id="idcomprobante" />

                                  <!--Proveedor-->                                   
                                  <div class="col-sm-12 col-md-9 col-lg-7 col-xl-7">
                                    <div class="form-group">
                                      <label for="idpersona">Persona <sup class="text-danger">*</sup></label>
                                      <!-- <div class="input-group"> -->
                                        <select name="idpersona" id="idpersona" class="form-control select2" placeholder="Seleccinar un proveedor"> </select>
                                    </div>
                                  </div>  

                                  <!-- adduser -->
                                  <div class="col-sm-12 col-md-3 col-lg-1 col-xl-1">
                                    <div class="form-group">
                                      <label class="text-white d-none show-min-width-576px">.</label> 
                                      <label class="d-none show-max-width-576px" >Nuevo proveedor</label>
                                      <a data-toggle="modal" href="#modal-agregar-persona" >
                                        <button type="button" class="btn btn-success btn-block" data-toggle="tooltip" data-original-title="Agregar nuevo Provedor" onclick="limpiar_form_persona(); select_one_tab();">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                      </a>
                                    </div>
                                  </div>  
                                  
                                  <!--forma pago-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="forma_pago">Forma Pago</label>
                                      <select name="forma_pago" id="forma_pago" class="form-control select2" style="width: 100%;">
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Crédito">Crédito</option>
                                      </select>
                                    </div>
                                  </div>

                                  <!-- Tipo de comprobante -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" id="content-t-comprob">
                                    <div class="form-group">
                                      <label for="tipo_comprobante">Tipo Comprobante</label>
                                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select2" onchange="comprob_factura(); validando_igv();" onkeyup="comprob_factura();" placeholder="Seleccinar un tipo de comprobante">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Recibo por Honorario">Recibo por Honorario</option>
                                        <option value="Nota de venta">Nota de venta</option>
                                      </select>
                                    </div>
                                  </div>                                  

                                  <!-- Código-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label class="nro_comprobante" for="nro_comprobante">Núm. comprobante </label>
                                      <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control" placeholder="Código" />
                                    </div>
                                  </div>                                  

                                  <!-- Fecha 1  -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="fecha_i">Fecha Emisión</label>
                                      <input type="date" name="fecha_i" class="form-control" id="fecha_i" />
                                    </div>
                                  </div>

                                  <!-- Sub total -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="subtotal">Sub total</label>
                                      <input class="form-control" type="number" id="subtotal" name="subtotal" placeholder="Sub total" readonly />                                   
                                    </div>
                                  </div>

                                  <!-- IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                      <label for="igv">IGV</label>
                                      <input class="form-control igv" type="number" id="igv" name="igv" placeholder="IGV" readonly />
                                    </div>
                                  </div>

                                  <!-- valor IGV -->
                                  <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                        <label for="val_igv" class="text-gray" style="font-size: 13px;">Valor - IGV </label>
                                        <input type="text" name="val_igv" id="val_igv" value="0.18" class="form-control" readonly onkeyup="calculandototales_fact();"> 
                                        <input class="form-control" type="hidden"  id="tipo_gravada" name="tipo_gravada"/>
                                    </div>
                                  </div>
                                  
                                  <!--Precio Parcial-->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="form-group">
                                      <label for="marca">Monto total </label>
                                      <input type="number" name="precio_parcial" id="precio_parcial" class="form-control" onchange="comprob_factura();" onkeyup="comprob_factura();" placeholder="Precio Parcial" />                                  
                                    </div>
                                  </div>

                                  <!--Descripcion-->
                                  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group ">
                                      <label for="descripcion_pago">Descripción</label> <br />
                                      <textarea name="descripcion" id="descripcion" class="form-control" rows="2"></textarea>
                                    </div>
                                  </div>

                                  <!-- Factura -->
                                  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" >   
                                    <!-- linea divisoria -->
                                    <div class="borde-arriba-naranja mt-4"> </div>                            
                                    <div class="row text-center">
                                      <div class="col-md-12" style="padding-top: 15px; padding-bottom: 5px;">
                                        <label for="cip" class="control-label" > Comprobante </label>
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"> <i class="fas fa-upload"></i> Subir.</button>
                                        <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                        <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" /> 
                                      </div>
                                      <div class="col-6 col-md-6 text-center">
                                        <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'admin/dist/docs/comprobante/comprobante');">
                                        <i class="fas fa-redo"></i> Recargar.
                                        </button>
                                      </div>
                                    </div>                              
                                    <div id="doc1_ver" class="text-center mt-4">
                                      <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >
                                    </div>
                                    <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                  </div>

                                  <!-- barprogress -->
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_comprobante_div" style="display: none;">
                                    <div class="progress" >
                                      <div id="barra_progress_comprobante" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                        0%
                                      </div>
                                    </div>
                                  </div>

                                </div>

                                <div class="row" id="cargando-2-fomulario" style="display: none;">
                                  <div class="col-lg-12 text-center">
                                    <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                    <br />
                                    <h4>Cargando...</h4>
                                  </div>
                                </div>
                              </div>
                              <!-- /.card-body -->
                              <div class=" justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_hide_form(1);"> <i class="fas fa-arrow-left"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                              </div>
                            </form>

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
                                                <input type="hidden" name="idpersona_p" id="idpersona_p" />
                                                <!-- tipo persona  -->
                                                <input type="hidden" name="id_tipo_persona_p" id="id_tipo_persona_p" />

                                                <!-- Tipo de documento -->
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-2 div_tipo_doc">
                                                  <div class="form-group">
                                                    <label for="tipo_documento_p">Tipo Doc.</label>
                                                    <select name="tipo_documento_p" id="tipo_documento_p" class="form-control" placeholder="Tipo de documento">
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
                                                    <label for="num_documento_p">N° de documento</label>
                                                    <div class="input-group">
                                                      <input type="number" name="num_documento_p" class="form-control" id="num_documento_p" placeholder="N° de documento" />
                                                      <div class="input-group-append" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_sunat_reniec('_p');">
                                                        <span class="input-group-text" style="cursor: pointer;">
                                                          <i class="fas fa-search text-primary" id="search_p"></i>
                                                          <i class="fa fa-spinner fa-pulse fa-fw fa-lg text-primary" id="charge_p" style="display: none;"></i>
                                                        </span>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                <!-- Nombre -->
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-5 div_nombre">
                                                  <div class="form-group">
                                                    <label for="nombre_p">Nombres/Razon Social</label>
                                                    <input type="text" name="nombre_p" class="form-control" id="nombre_p" placeholder="Nombres y apellidos" />
                                                  </div>
                                                </div>

                                                <!-- Telefono -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 div_telefono">
                                                  <div class="form-group">
                                                    <label for="telefono_p">Teléfono</label>
                                                    <input type="text" name="telefono_p" id="telefono_p" class="form-control" data-inputmask="'mask': ['999-999-999', '+51 999 999 999']" data-mask />
                                                  </div>
                                                </div>

                                                <!-- Correo electronico -->
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 div_correo">
                                                  <div class="form-group">
                                                    <label for="email_p">Correo electrónico</label>
                                                    <input type="email_p" name="email_p" class="form-control" id="email_p" placeholder="Correo electrónico" onkeyup="convert_minuscula(this);" />
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
                                                    <label for="titular_cuenta_p">Titular de la cuenta</label>
                                                    <input type="text" name="titular_cuenta_p" class="form-control" id="titular_cuenta_p" placeholder="Titular de la cuenta" />
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
                                                    <label for="cargo_trabajador_p">Cargo </label>
                                                    <select name="cargo_trabajador_p" id="cargo_trabajador_p" class="form-control select2 cargo_trabajador" style="width: 100%;">
                                                      <!-- Aqui listamos los cargo_trabajador -->
                                                    </select>
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Mensual) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 div_sueldo_mensual">
                                                  <div class="form-group">
                                                    <label for="sueldo_mensual_p">Sueldo(Mensual)</label>
                                                    <input type="number" step="any" name="sueldo_mensual_p" class="form-control" id="sueldo_mensual_p" onclick="sueld_mensual();" onkeyup="sueld_mensual();" />
                                                  </div>
                                                </div>

                                                <!-- Sueldo(Diario) -->
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 div_sueldo_diario">
                                                  <div class="form-group">
                                                    <label for="sueldo_diario_p">Sueldo(Diario)</label>
                                                    <input type="number" step="any" name="sueldo_diario_p" class="form-control" id="sueldo_diario_p" readonly />
                                                  </div>
                                                </div>
                                                

                                                <!-- Direccion -->
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 div_direccion">
                                                  <div class="form-group">
                                                    <label for="direccion_p">Dirección</label>
                                                    <input type="text" name="direccion_p" class="form-control" id="direccion_p" placeholder="Dirección" />
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
        <script type="text/javascript" src="scripts/comprobante.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
