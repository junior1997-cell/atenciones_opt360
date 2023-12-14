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
        <title>Pago Trabajador | Admin Briment</title>

        <?php $title = "Trabajadores"; require 'head.php'; ?>
        <!-- <link rel="stylesheet" href="../dist/css/switch_domingo.css"> -->

      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['pago_trabajador']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
              <!-- Content Header (Page header) -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1> <i class="fas fa-dollar-sign nav-icon"></i> Clientes<b>: <small class="texto-parpadeante nombre_trabajador_view"></small> </b> </h1>
                    </div>
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="trabajador.php">Home</a></li>
                        <li class="breadcrumb-item active">cliente</li>
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
                          <!-- agregar general -->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  >
                            <button type="button" class="btn bg-gradient-primary" id="btn-agregar-cliente" data-toggle="modal" data-target="#modal-agregar-cliente-sistema" onclick="mostrar_all_trabajadores();" ><i class="fa-solid fa-circle-plus"></i> Agregar cliente</button>
                          </h3>
                          <!-- agregar mes -->
                          <h3 class="card-title">                            
                            <button type="button" class="btn bg-gradient-warning" id="btn-regresar" style="display: none;" onclick="show_hide_table(1);"><i class="fas fa-arrow-left"></i> Regresar</button>
                            <button type="button" class="btn bg-gradient-primary" id="btn-agregar-mes"data-toggle="modal" onclick="limpiar_form_mes();" style="display: none;" data-target="#modal-agregar-mes" ><i class="fa-solid fa-circle-plus"></i> Agregar Mes</button>
                          </h3>
                          <!--Regresar a la tabla principal  -->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  >
                            <button type="button" class="btn btn-block btn-outline-warning" id="btn-regresar-todo" style="display: none;" onclick="show_hide_table(1);" ><i class="fas fa-arrow-left"></i></button>
                          </h3>
                          <!--Regresar a la tabla mes-->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  >
                            <button type="button" class="btn bg-gradient-warning" id="btn-regresar-meses" style="display: none;" onclick="show_hide_table(2); "><i class="fas fa-arrow-left"></i> Regresar a meses</button>
                          </h3>
                          <!--Agregar pago -->
                          <h3 class="card-title mr-3"  style="padding-left: 2px;"  >
                            <button type="button" class="btn bg-gradient-primary" id="btn-agregar-pago" style="display: none;" onclick="limpiar_form_pago();" data-toggle="modal" data-target="#modal-agregar-pago-trabajdor" ><i class="fa-solid fa-circle-plus"></i> Agregar Pago</button>
                          </h3>
                          <div class="sueldo_trab_view" style="text-align: right; display:none; "> Total a pagar: <b class="texto-parpadeante val_sueldo"> S/ 100</b> </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                          <div id="div-tabla-trabajador">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-trabajador" class="table table-bordered table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombres</th>
                                  <th>Cargo</th>
                                  <th>Sueldo</th>
                                  <th>Teléfono</th>
                                  <th>Pagar</th>
                                  <th>Pago total</th>

                                  <th>Estado</th>
                                  <th>Nombres</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num Doc.</th>
                                  <th>Nacimiento</th>
                                  <th>Edad</th>                                  
                                  <th>Sueldo mensual</th>
                                  <th>Sueldo Diario</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th>Nombres</th>
                                  <th>Cargo</th>
                                  <th>Sueldo</th>
                                  <th>Teléfono</th>
                                  <th>Pagar</th>
                                  <th>Pago total</th>

                                  <th>Estado</th>
                                  <th>Nombres</th>
                                  <th>Tipo Doc.</th>
                                  <th>Num Doc.</th>
                                  <th>Nacimiento</th>
                                  <th>Edad</th>                                  
                                  <th>Sueldo mensual</th>
                                  <th>Sueldo Diario</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          
                          <div id="div-tabla-mes-pago" style="display: none !important;">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-mes-pago" class="table table-bordered table-striped display" style="width: 100% !important; ">
                              <thead>
                                <tr>
                                  <th class="text-center">#</th>   
                                  <th>Op</th>                               
                                  <th>Año</th>
                                  <th>Mes</th>
                                  <th>Ver Detalle</th>
                                  <th >Pagos</th>                                  
                                </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">#</th>     
                                  <th>Op</th>                             
                                  <th>Año</th>
                                  <th>Mes</th>
                                  <th>Ver Detalle</th>
                                  <th class="px-2">Pagos</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                                                    
                          <div id="div-tabla-pagos" style="display: none !important;">
                            <!-- Lista de trabajdores activos -->                      
                            <table id="tabla-ingreso-pagos" class="table table-bordered  table-striped display" style="width: 100% !important;">
                              <thead>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha </th> 
                                  <th>Monto</th>
                                  <th>Descripcion</th>     
                                  <th>Comprobante</th>                                                  
                                </tr>
                              </thead>
                              <tbody>                         
                                
                              </tbody>
                              <tfoot>
                                <tr> 
                                  <th class="text-center">#</th> 
                                  <th>Op.</th> 
                                  <th>Fecha </th> 
                                  <th class="px-2">Monto</th>
                                  <th>Descripcion</th>     
                                  <th>Comprobante</th>                                                  
                                </tr>
                              </tfoot>
                            </table>
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

                <!-- MODAL - AGREGAR CLIENTE DE  SISTEMA - charge 1,2 -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-cliente-sistema">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                    <div class="modal-content shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title">
                          Agregar cliente
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-cliente-sistema" name="form-cliente-sistema" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id pago_trabajador   -->
                            <input type="hidden" name="idpago_trabajador" id="idpago_trabajador" />                            

                            <!-- Tipo Negocio -->
                            <div class=" col col-lg-10 col-xl-10" id="content-tipo-comprobante">
                              <div class="form-group">
                                <label for="cliente">Cliente <sup class="text-danger">(único*)</sup></label>
                                <select name="cliente" id="cliente" class="form-control select2"  placeholder="Seleccinar">                                  
                                </select>
                              </div>
                            </div>

                            <!-- adduser -->
                            <div class="col-lg-2">
                              <div class="form-group">
                              <label for="Add" class="d-none d-sm-inline-block text-break" style="color: white;">.</label> <br class="d-none d-sm-inline-block">
                                <a data-toggle="modal" href="#modal-agregar-proveedor" >
                                  <button type="button" class="btn btn-success p-x-6px" data-toggle="tooltip" data-original-title="Agregar Provedor" onclick="limpiar_form_proveedor();">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                  </button>
                                </a>
                              </div>
                            </div>

                            <!-- Fecha de deposito -->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="fecha_inicio">Fecha de inicio </label>
                                <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" />
                                <input class="form-control" type="hidden" id="dia_inicio" name="dia_inicio" />
                                <input class="form-control" type="hidden" id="mes_inicio" name="mes_inicio" />
                                <input class="form-control" type="hidden" id="year_inicio" name="year_inicio" />
                              </div>
                            </div>

                            <!-- Tipo Negocio -->
                            <div class="col-lg-6" id="content-tipo-comprobante">
                              <div class="form-group">
                                <label for="tipo_negocio">Tipo de negocio <sup class="text-danger">(único*)</sup></label>
                                <select name="tipo_negocio" id="tipo_negocio" class="form-control select2"  placeholder="Seleccinar">                                  
                                </select>
                              </div>
                            </div> 

                            <!-- Plan de Servicio -->
                            <div class="col-lg-6" id="content-tipo-comprobante">
                              <div class="form-group">
                                <label for="plan_servicio">Plan de nervicio <sup class="text-danger">(único*)</sup></label>
                                <select name="plan_servicio" id="plan_servicio" class="form-control select2"  placeholder="Seleccinar">
                                  <option value="PAGO UNICO">PAGO UNICO</option>
                                  <option value="MENSUAL">MENSUAL</option>
                                  <option value="ANUAL">ANUAL</option>
                                </select>
                              </div>
                            </div> 

                            <!-- Monto (de cantidad a depositado)-->
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="monto">Monto <small> (Monto a pagar) </small> </label>
                                <input type="number" name="monto" id="monto" class="form-control" placeholder="Monto a pagar" />
                              </div>
                            </div>                           

                            <!-- Descripción -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="nombre_mes">Descripción</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" cols="3" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- Pdf 1 -->
                            <div class="col-md-6">
                              <div class="row text-center">
                                <div class="col-md-12 p-t-15px p-b-5px">
                                  <label for="doc1_i" class="control-label"> Baucher de deposito </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1,'pago_trabajador', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                </div>
                              </div>
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="barra_progress_pagos_x_mes_div">
                                <div id="barra_progress_pagos_x_mes" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-cliente-sistema">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_cliente_sistema">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>               

                <!-- MODAL - AGREGAR PAGO POR PERSONA - charge 3,4 -->
                <div class="modal fade bg-color-02020280" id="modal-agregar-cliente">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg shadow-0px1rem3rem-rgb-0-0-0-50 rounded">
                    <div class="modal-content shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title">
                          Agregar cliente
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-danger" aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <!-- form start -->
                        <form class="mx-2" id="form-cliente-sistema" name="form-cliente-sistema" method="POST">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id pago_trabajador   -->
                            <input type="hidden" name="idpago_trabajador" id="idpago_trabajador" />

                            <!-- id mes_pago_trabajador  -->
                            <input type="hidden" name="idmes_pago_trabajador_p" id="idmes_pago_trabajador_p" />

                            <!-- Cuenta deposito enviada -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="nombre_mes">Nombre mes</label>
                                <input type="text" name="nombre_mes" id="nombre_mes" class="form-control" placeholder="Nombre mes" readonly />
                              </div>
                            </div>

                            <!-- Monto (de cantidad a depositado)-->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="monto">Monto <small> (Monto a pagar) </small> </label>
                                <input type="number" name="monto" id="monto" class="form-control" placeholder="Monto a pagar" />
                              </div>
                            </div>

                            <!-- Fecha de deposito -->
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="fecha_pago">Fecha de deposito </label>
                                <input class="form-control" type="date" id="fecha_pago" name="fecha_pago" />
                              </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="nombre_mes">Descripción</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" cols="3" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- Pdf 1 -->
                            <div class="col-md-6">
                              <div class="row text-center">
                                <div class="col-md-12 p-t-15px p-b-5px">
                                  <label for="doc1_i" class="control-label"> Baucher de deposito </label>
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                  <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                  <input style="display: none;" id="doc1" type="file" name="doc1" accept="application/pdf, image/*" class="docpdf" />
                                </div>
                                <div class="col-6 col-md-6 text-center">
                                  <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1,'pago_trabajador', 'comprobante');"><i class="fas fa-redo"></i> Recargar.</button>
                                </div>
                              </div>
                              <div id="doc1_ver" class="text-center mt-4">
                                <img src="../dist/svg/doc_uploads.svg" alt="" width="50%" />
                              </div>
                              <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                              <div class="progress" id="barra_progress_pagos_x_mes_div">
                                <div id="barra_progress_pagos_x_mes" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                          <button type="submit" style="display: none;" id="submit-form-pagos">Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="guardar_registro_pagos">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
                </div>   
                

                <!-- MODAL - VER PERFIL PERSONA-->
                <div class="modal fade bg-color-02020280" id="modal-ver-perfil-persona">
                  <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content bg-color-0202022e shadow-none border-0">
                      <div class="modal-header">
                        <h4 class="modal-title text-white foto-persona">Foto Persona</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body"> 
                        <div id="perfil-persona" class="text-center">
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
        <script type="text/javascript" src="scripts/cliente_sistema.js"></script>

        <script> $(function () {  $('[data-toggle="tooltip"]').tooltip();  }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
