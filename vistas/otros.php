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
        <title>Otros | Admin JDL</title>

        <?php $title = "Otros"; require 'head.php'; ?>

        <!--CSS  switch_MATERIALES-->
        <link rel="stylesheet" href="../dist/css/switch_materiales.css" />
      </head>
      <body class="hold-transition sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
          <?php
          require 'nav.php';
          require 'aside.php';
          if ($_SESSION['recurso']==1){
            //require 'enmantenimiento.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">               

              <!-- Main content -->
              <section class="content">
                <div class="container-fluid">
                  <div class="row">

                    <div class="col-12 col-sm-12 mt-4">
                      <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="custom-tabs-three-producto-tab" data-toggle="pill" href="#custom-tabs-three-producto" role="tab" aria-controls="custom-tabs-three-producto" aria-selected="true"><i class="fa-solid fa-computer"></i> Producto</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-persona-tab" data-toggle="pill" href="#custom-tabs-three-persona" role="tab" aria-controls="custom-tabs-three-persona" aria-selected="false"><i class="fas fa-user"></i> Persona</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-compra-tab" data-toggle="pill" href="#custom-tabs-three-compra" role="tab" aria-controls="custom-tabs-three-compra" aria-selected="false"><i class="fas fa-shopping-cart"></i> Compra</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-venta-tab" data-toggle="pill" href="#custom-tabs-three-venta" role="tab" aria-controls="custom-tabs-three-venta" aria-selected="false"><i class="fas fa-shopping-cart"></i> Venta</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-sucursal-tab" data-toggle="pill" href="#custom-tabs-three-sucursal" role="tab" aria-controls="custom-tabs-three-sucursal" aria-selected="false"><i class="fas fa-store-alt"></i> Sucursal</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="custom-tabs-three-empresa-tab" data-toggle="pill" href="#custom-tabs-three-empresa" role="tab" aria-controls="custom-tabs-three-empresa" aria-selected="false"><i class="fa-solid fa-gear"></i> Empresa</a>
                            </li>
                          </ul>
                        </div>                        
                        <!-- /.card -->
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-three-producto" role="tabpanel" aria-labelledby="custom-tabs-three-producto-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - UNIDAD DE MEDIDA-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Unidades de Medida</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Unidad de Medida</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-unidad-m" onclick="limpiar_unidades_m();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Unidad de medidas.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-unidades-m" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                            
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Abreviación</th>
                                            <th>Descripciòn</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>                            

                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - CATEGORIAS PRODUCTO -->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Categorias Producto</h2>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Producto</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-categorias-af" onclick="limpiar_c_af();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Categorías Producto.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-categorias-af" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div> 
                            </div> 

                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - MARCAS -->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Marca Producto</h2>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Marca</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-marca" onclick="limpiar_form_marca();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Marca Producto.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-marca" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div> 
                            </div> 
                            
                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - COLOR -->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Color Producto</h2>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Color</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-color" onclick="limpiar_form_color();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Color Producto.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-color" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div> 
                            </div> 

                          </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-three-persona" role="tabpanel" aria-labelledby="custom-tabs-three-persona-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - BANCOS -->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Bancos</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Bancos</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-bancos" onclick="limpiar_banco();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Administrar Bancos.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-bancos" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Formato Cta/CCI</th>
                                            <th>Estado</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Formato Cta</th>
                                            <th>Formato CCI</th>
                                            <th>Formato Cta. Dtrac.</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Formato</th>
                                            <th>Estado</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Formato Cta</th>
                                            <th>Formato CCI</th>
                                            <th>Formato Cta. Dtrac.</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - TIPO PERSONA-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Tipo Persona</h2>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-tipo" onclick="limpiar_tipo();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Tipo* .
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-tipo" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                </div>
                              </div>
                            </div> 

                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - CARGO-->
                              <div class="row">
                                
                                <div class="col-sm-6">
                                  <h2>Cargos</h2>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Cargos</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-cargo" onclick="limpiar_cargo();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Cargos.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-cargo" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>    
                                <!-- /.col-12 -->
                              </div>
                            </div> 

                            <div class="col-sm-12 col-md-12 col-lg-6g col-xl-6">
                              <!-- TBLA - TIPO NEGOCIO-->
                              <div class="row">
                                
                                <div class="col-sm-6">
                                  <h2>Tipo de negocio</h2>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Tipo de negocio</li>
                                  </ol>
                                </div>
                                <!-- /.col-6 -->

                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-tipo-negocio" onclick="limpiar_tipo_negocio();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar tipo de negocio.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-tipo-negocio" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>                                
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>    
                                <!-- /.col-12 -->
                              </div>
                            </div> 

                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-three-compra" role="tabpanel" aria-labelledby="custom-tabs-three-compra-tab">
                          <div class="row">
                            --- compra - vacio ---
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-three-venta" role="tabpanel" aria-labelledby="custom-tabs-three-venta-tab">
                          <div class="row">
                            --- venta - vacio ---
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-three-sucursal" role="tabpanel" aria-labelledby="custom-tabs-three-sucursal-tab">
                          <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - SUCURSAL-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Sucursal</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Sucursal</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-agregar-sucursal" onclick="limpiar_sucursal();"><i class="fas fa-plus-circle"></i> Agregar</button>
                                        Admnistrar Sucursal.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="tabla-sucursal" class="table table-bordered table-striped display" style="width: 100% !important;">
                                        <thead>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Codigo</th>
                                            <th>Direccion</th>
                                            <th>Estado</th>                                            
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                          <tr>
                                            <th class="text-center">#</th>
                                            <th class="">Acciones</th>
                                            <th>Nombre</th>
                                            <th>Codigo</th>
                                            <th>Direccion</th>
                                            <th>Estado</th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-three-empresa" role="tabpanel" aria-labelledby="custom-tabs-three-empresa-tab">
                          <div class="row">
                            <div class="div_col_empresa col-sm-12 col-md-12 col-lg-6 col-xl-6">
                              <!-- TBLA - EMPRESA-->
                              <div class="row">
                                <div class="col-sm-6">
                                  <h2>Empresa</h2>
                                </div>
                                <div class="col-sm-6">
                                  <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Empresa</li>
                                  </ol>
                                </div>
                                <div class="col-12">
                                  <div class="card card-primary card-outline">
                                    <div class="card-header">
                                      <h3 class="card-title">
                                        <button type="button" class="btn bg-gradient-warning btn-regresar" onclick="limpiar_form_empresa(); show_hide_form(1);" style="display: none;"><i class="fas fa-arrow-left"></i> Regresar</button>
                                        Admnistrar Empresa.
                                      </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <div class="div_tabla_empresa">
                                        <table id="tabla-empresa" class="table table-bordered table-striped display" style="width: 100% !important;">
                                          <thead>
                                            <tr>                                              
                                              <th class="">OP</th>
                                              <th>Nombre</th> 
                                              <th>Impuesto</th>
                                              <th>Moneda</th>
                                              <th>Ubicacion</th>                                          
                                            </tr>
                                          </thead>
                                          <tbody></tbody>
                                          <tfoot>
                                            <tr>                                              
                                              <th class="">OP</th>
                                              <th>Nombre</th>
                                              <th>Impuesto</th>
                                              <th>Moneda</th>
                                              <th>Ubicacion</th>
                                            </tr>
                                          </tfoot>
                                        </table>
                                      </div>
                                      <!-- /.div -->
                                      <div class="div_form_empresa" style="display: none;">
                                        <!-- form start -->
                                        <form id="form-empresa" name="form-empresa" method="POST" autocomplete="off">
                                          
                                          <div class="row" id="cargando-16-fomulario">
                                            <!-- id idunidad_medida -->
                                            <input type="hidden" name="iddatos_empresa" id="iddatos_empresa" />

                                            <div class="col-12 pl-0">
                                              <div class="text-primary"><label for="">DATOS EMPRESA</label></div>
                                            </div>

                                            <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                                              <div class="row ">                                                
                                                <!--imagen-material-->
                                                <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                                                  <label for="foto1">Logo empresa</label>
                                                  <div style="text-align: center;">
                                                    <img onerror="this.src='../dist/img/default/img_defecto.png';" src="../dist/img/default/img_defecto.png"
                                                      class="img-thumbnail" id="foto1_i" style="cursor: pointer !important; height: 100% !important;" width="auto" />
                                                    <input style="display: none;" type="file" name="foto1" id="foto1" accept="image/*" />
                                                    <input type="hidden" name="foto1_actual" id="foto1_actual" />
                                                    <div class="text-center" id="foto1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-10 col-xl-10">
                                                  <div class="row">                                                    
                                                    <!-- Descripciòn -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="tipo_documento">Tipo Documento</label>
                                                        <input type="text" name="tipo_documento" id="tipo_documento" class="form-control" placeholder="Tipo Documento" value="RUC" readonly />
                                                      </div>
                                                    </div>                                                    
                                                    <!-- Número Documento -->
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                                      <div class="form-group">
                                                        <label for="numero_documento">N° de documento</label>
                                                        <div class="input-group">
                                                          <input type="number" name="numero_documento" class="form-control" id="numero_documento" placeholder="N° de documento" step="1"  />
                                                          <div class="input-group-append cursor-pointer" data-toggle="tooltip" data-original-title="Buscar Reniec/SUNAT" onclick="buscar_empresa('');">
                                                            <span class="input-group-text bnt-charge-sunat" >
                                                              <i class="fas fa-search text-primary"></i>
                                                            </span>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <!-- Razon Social -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="nombre_empresa">Razon Social</label>
                                                        <textarea name="nombre_empresa" id="nombre_empresa" class="form-control" rows="1" placeholder="Razon social"></textarea>                                                        
                                                      </div>
                                                    </div>
                                                    <!-- Pais -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="pais">Pais</label>
                                                        <input type="text" name="pais" id="pais" class="form-control" placeholder="Peru" value="Perú" readonly />
                                                      </div>
                                                    </div>
                                                    <!-- Dirección -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="direccion">Dirección Fiscal</label>
                                                        <textarea name="direccion" id="direccion" class="form-control" rows="1" placeholder="Dirección"></textarea>
                                                      </div>
                                                    </div>
                                                    <!-- Departamento -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="departamento">Departamento</label>
                                                        <input type="text" name="departamento" id="departamento" class="form-control" placeholder="Departamento" />
                                                      </div>
                                                    </div>
                                                    <!-- Provincia -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="provincia">Provincia</label>
                                                        <input type="text" name="provincia" id="provincia" class="form-control" placeholder="Provincia" />
                                                      </div>
                                                    </div>
                                                    <!-- Distrito -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="distrito">Distrito</label>
                                                        <input type="text" name="distrito" id="distrito" class="form-control" placeholder="Distrito" />
                                                      </div>
                                                    </div>
                                                    <!-- Ubigeo -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="ubigeo">Ubigeo</label>
                                                        <input type="text" name="ubigeo" id="ubigeo" class="form-control" placeholder="Ubigeo" />
                                                      </div>
                                                    </div>
                                                    <!-- Telefono -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="telefono">Telefono</label>
                                                        <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="999 999 999" pattern="[0-9]{9}" />
                                                      </div>
                                                    </div>
                                                    <!-- correo -->
                                                    <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                      <div class="form-group">
                                                        <label for="correo">Correo</label>
                                                        <textarea type="email" name="correo" id="correo" class="form-control" rows="1" placeholder="Correo"></textarea>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <!-- /.row -->
                                                </div>
                                              </div>
                                            </div>  

                                            <div class="col-6"> 
                                              <div class="row px-2">    
                                                <div class="col-12 pl-0">
                                                <div class="text-primary"><label for="">DATOS FINANCIEROS</label></div>
                                                </div>                                          
                                                <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">
                                                  
                                                  <div class="row">                                              
                                                    <!-- Nombre inpuesto -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="nombre_impuesto">Nombre inpuesto</label>
                                                        <input type="text" name="nombre_impuesto" id="nombre_impuesto" class="form-control" placeholder="Nombre inpuesto" />
                                                      </div>
                                                    </div>
                                                    <!-- Monto inpuesto -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="monto_impuesto">Monto inpuesto</label>
                                                        <input type="number" name="monto_impuesto" id="monto_impuesto" class="form-control" placeholder="18%" step="0.01" />
                                                      </div>
                                                    </div>
                                                    <!-- moneda -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="moneda">Moneda</label>
                                                        <input type="text" name="moneda" id="moneda" class="form-control" placeholder="Moneda" />
                                                      </div>
                                                    </div>
                                                    <!-- Simbolo -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="simbolo">Simbolo</label>
                                                        <input type="text" name="simbolo" id="simbolo" class="form-control" placeholder="Simbolo" />
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="col-12 pl-0">
                                                <div class="text-primary"><label for="">USUARIO Y PASWORD SOL - SUNAT</label></div>
                                                </div>
                                                <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">                                                  
                                                  <div class="row">
                                                    <!-- usuario_sol -->
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="usuario_sol">Usuario Sol</label>
                                                        <input type="text" name="usuario_sol" id="usuario_sol" class="form-control" placeholder="Usuario Sol" autocomplete="off" />
                                                      </div>
                                                    </div>

                                                    <!-- clave_sol -->                                                
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                      <div class="form-group">
                                                        <label for="clave_sol">Contraseña <sup class="text-danger">(*)</sup></label>
                                                        <div class="input-group">
                                                          <input type="password" name="clave_sol" id="clave_sol" class="form-control" placeholder="Contraseña" autocomplete="off" />
                                                          <div class="input-group-append cursor-pointer" data-toggle="tooltip" data-original-title="Ver contraseña" onclick="ver_password(this, 'clave_sol', '#icon-view-clave-sol');">
                                                            <span class="input-group-text" id="icon-view-clave-sol">
                                                              <i class="fa-solid fa-eye text-primary"></i>
                                                            </span>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>  
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-6">
                                              <div class="row px-2"> 
                                                <div class="col-12 pl-0">
                                                  <div class="text-primary"><label for="">CERTIFICADO ELECTRONICO Y PASSWORD</label></div>
                                                </div>
                                                <div class="card col-12 px-3 py-3" style="box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(0 0 0 / 60%);">                                              
                                                  <div class="row">                                                
                                                    <!-- CErtificado Digital -->
                                                    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                      <div class="row text-center">
                                                        <div class="col-md-12" style="padding-bottom: 5px;">
                                                          <label for="cip" class="control-label"> Certificado Digital </label>
                                                        </div>
                                                        <div class="col-6 col-md-6 text-center">
                                                          <button type="button" class="btn btn-success btn-block btn-xs" id="doc1_i"><i class="fas fa-upload"></i> Subir.</button>
                                                          <input type="hidden" id="doc_old_1" name="doc_old_1" />
                                                          <input style="display: none;" id="doc1" type="file" name="doc1" accept=".pfx, .p12" class="docpdf" />
                                                        </div>
                                                        <div class="col-6 col-md-6 text-center">
                                                          <button type="button" class="btn btn-info btn-block btn-xs" onclick="re_visualizacion(1, 'admin/public/FACT_WebService/Facturacion/src');"><i class="fas fa-redo"></i> Recargar.</button>
                                                        </div>
                                                      </div>
                                                      <div id="doc1_ver" class="text-center mt-4">
                                                        <img src="../dist/img/default/img_defecto_3.png" alt="" width="150px" />
                                                      </div>
                                                      <div class="text-center" id="doc1_nombre"><!-- aqui va el nombre del pdf --></div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8">
                                                      <div class="row">
                                                        <!-- Descripciòn -->
                                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                          <div class="form-group">
                                                            <label for="estado_certificado">Estado Certificado</label>
                                                            <select name="estado_certificado" id="estado_certificado" class="form-control">
                                                              <option value="BETA">BETA</option>
                                                              <option value="PRODUCCION">PRODUCCION</option>
                                                            </select>
                                                          </div>
                                                        </div>

                                                        <!-- Descripciòn -->                                                
                                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-12">
                                                          <div class="form-group">
                                                            <label for="clave_certificado">Contraseña <sup class="text-danger">(*)</sup></label>
                                                            <div class="input-group">
                                                              <input type="password" name="clave_certificado" id="clave_certificado" class="form-control" placeholder="Contraseña" autocomplete="off" />
                                                              <div class="input-group-append cursor-pointer" data-toggle="tooltip" data-original-title="Ver contraseña" onclick="ver_password(this, 'clave_certificado', '#icon-view-clave-certificado');">
                                                                <span class="input-group-text" id="icon-view-clave-certificado">
                                                                  <i class="fa-solid fa-eye text-primary"></i>
                                                                </span>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <!-- /.row -->
                                                    </div>
                                                    <!-- /.col -->
                                                  </div>
                                                </div>                                            
                                              </div>
                                            </div>
                                            <!-- barprogress -->
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_empresa_div">
                                              <div class="progress" >
                                                <div id="barra_progress_empresa" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                                  0%
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-12 mt-3">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_form_empresa(); show_hide_form(1);">Close</button>
                                              <button type="submit" class="btn btn-success" id="guardar_registro_empresa" >Guardar Cambios</button>
                                            </div>

                                          </div>

                                          <div class="row" id="cargando-17-fomulario" style="display: none;">
                                            <div class="col-lg-12 text-center">
                                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                                              <br />
                                              <h4>Cargando...</h4>
                                            </div>
                                          </div>
                                          <!-- /.card-body -->                                          
                                          
                                        </form>
                                      </div>
                                      <!-- /.div-form -->
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div>
                    <!-- /.col-12 -->
                                                           
                  </div>                  
                </div>
                <!-- /.container-fluid -->
              </section>
              <!-- /.content -->                         

              <!-- MODAL - UNIDAD DE MEDIDA - chague 1,2 -->
              <div class="modal fade" id="modal-agregar-unidad-m">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Unidad de Medida</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-unidad-m" name="form-unidad-m" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-1-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idunidad_medida" id="idunidad_medida" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre_medida" class="form-control" id="nombre_medida" placeholder="Nombre de la medida" />
                              </div>
                            </div>

                            <!-- abreviacion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="abreviatura">Abreviación</label>
                                <input type="text" name="abreviatura" class="form-control" id="abreviatura" placeholder="abreviatura." />
                              </div>
                            </div>

                            <!-- Descripciòn -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_m">Descripciòn</label>
                                <textarea name="descripcion_m" id="descripcion_m" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_um">
                                <div id="barra_progress_um" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" style="display: none;" id="submit-form-unidad-m">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_unidades_m();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_unidad_m">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - CATEGORIAS - chague 3,4 -->
              <div class="modal fade" id="modal-agregar-categorias-af">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar categoría Producto</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-categoria-af" name="form-categoria-af" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-3-fomulario">
                            <!-- id categoria_insumos_af -->
                            <input type="hidden" name="idcategoria_producto" id="idcategoria_producto" />

                            <!-- nombre categoria -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_categoria">Nombre categoría</label>
                                <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" placeholder="Nombre categoría" />
                              </div>
                            </div>
                            <!-- descripcion_cat categoria -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_cat">Descripcion Categoria</label>
                                <textarea name="descripcion_cat" id="descripcion_cat" class="form-control" rows="2"></textarea>                                
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_categoria_af">
                                <div id="barra_progress_categoria_af" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-4-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-cateogrias-af">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_c_af();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_categoria_af">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>  
              
              <!-- MODAL - MARCA - chague 5,6 -->
              <div class="modal fade" id="modal-agregar-marca">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Marca</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-marca" name="form-marca" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-5-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idmarca" id="idmarca" />

                            <!-- Nombre -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="nombre_marca">Nombre</label>
                                <input type="text" name="nombre_marca" class="form-control" id="nombre_marca" placeholder="Nombre del color." />
                              </div>
                            </div>

                            <!-- Descripcion -->
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="descripcion_marca">Descripcion</label>
                                <textarea name="descripcion_marca" id="descripcion_marca" class="form-control" placeholder="Descripcion" cols="30" rows="2"></textarea>
                              </div>
                            </div>                           

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20px" id="barra_progress_marca_div" style="display: none;">
                              <div class="progress" >
                                <div id="barra_progress_marca" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-6-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-marca">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_marca">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - COLOR - chague 7,8 -->
              <div class="modal fade" id="modal-agregar-color">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Color</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-color" name="form-color" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-7-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idcolor" id="idcolor" />

                            <!-- Nombre -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_color">Nombre</label>
                                <input type="text" name="nombre_color" class="form-control" id="nombre_color" placeholder="Nombre del color." />
                              </div>
                            </div>

                            <!-- hexadecimal -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label>Color hexadecimal:</label>
                                <div class="input-group my-colorpicker2">
                                  <input type="text" name="hexadecimal" id="hexadecimal" class="form-control" placeholder="#00AFB">
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-square fa-lg"></i></span>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-lg-12 mt-4">
                              <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> <b>Que es un Hexadecimal?</b></h5>
                                Un <b>color hexadecimal</b> sigue el formato #RRVVAA, donde RR es rojo, VV es verde y AA es azul. 
                                Estos enteros hexadecimales pueden encontrarse en un <b>rango de 00 a FF</b> para especificar la intensidad del color.
                                Mas informacion en: <a href="https://htmlcolorcodes.com/es/nombres-de-los-colores/" class="font-weight-bold" target="_blank" rel="noopener noreferrer" style="color: #000 !important;">https://htmlcolorcodes.com</a>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_color">
                                <div id="barra_progress_color" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-color">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_color">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>              

              <!-- MODAL - BANCOS - chague 9,10 -->
              <div class="modal fade" id="modal-agregar-bancos">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Banco</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-bancos" name="form-bancos" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-9-fomulario">
                            <!-- id banco -->
                            <input type="hidden" name="idbancos" id="idbancos" />

                            <!-- Nombre -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="nombre_b">Nombre</label>
                                <input type="text" name="nombre_b" id="nombre_b" class="form-control" placeholder="Nombre del banco." />
                              </div>
                            </div>

                            <!-- alias -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="alias">Alias</label>
                                <input type="text" name="alias" id="alias" class="form-control" placeholder="Alias del banco." />
                              </div>
                            </div>

                            <!-- Formato cuenta bancaria -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                <label for="formato_cta">Formato Cuenta Bancaria</label>
                                <input type="text" name="formato_cta" id="formato_cta" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div>

                            <!-- Formato CCI -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="formato_cci">Formato CCI</label>
                                <input type="text" name="formato_cci" id="formato_cci" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div>

                            <!-- Formato CCI -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="formato_detracciones">Formato Detracción</label>
                                <input type="text" name="formato_detracciones" id="formato_detracciones" class="form-control" placeholder="Formato." value="00000000" data-inputmask="'mask': ['99-99-99-99', '99 99 99 99']" data-mask />
                              </div>
                            </div> 

                            <!--img-material-->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                              <label for="imagen1">Imagen</label>
                              <div style="text-align: center;">
                                <img
                                  onerror="this.src='../dist/img/default/img_defecto_banco.png';"
                                  src="../dist/img/default/img_defecto_banco.png"
                                  class="img-thumbnail"
                                  id="imagen1_i"
                                  style="cursor: pointer !important; height: 100% !important;"
                                  width="auto"
                                />
                                <input style="display: none;" type="file" name="imagen1" id="imagen1" accept="image/*" />
                                <input type="hidden" name="imagen1_actual" id="imagen1_actual" />
                                <div class="text-center" id="imagen1_nombre"><!-- aqui va el nombre de la FOTO --></div>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_banco">
                                <div id="barra_progress_banco" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
                        <button type="submit" style="display: none;" id="submit-form-bancos">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_banco();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - TIPO DE TRABAJDOR - chague 11,12 -->
              <div class="modal fade" id="modal-agregar-tipo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Tipo Persona</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-tipo" name="form-tipo" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-11-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idtipo_persona" id="idtipo_persona" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_tipo">Nombre Tipo Persona</label>
                                <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" placeholder="Nombre tipo Persona" />
                              </div>
                            </div>

                              <!-- Descripciòn -->
                              <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_t">Descripciòn</label>
                                <textarea name="descripcion_t" id="descripcion_t" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_tipo">
                                <div id="barra_progress_tipo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-12-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-tipo">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_tipo();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_tipo">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - CARGO TRABAJDOR - chague 13,14 -->
              <div class="modal fade" id="modal-agregar-cargo">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Cargo</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-cargo" name="form-cargo" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-13-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idcargo_trabajador" id="idcargo_trabajador" />


                            <!-- nombre_trabajador -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_cargo">Nombre Cargo</label>
                                <input type="text" name="nombre_cargo" id="nombre_cargo" class="form-control" placeholder="Nombre Cargo" />
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_cargo">
                                <div id="barra_progress_cargo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-14-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-cargo">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_cargo();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_cargo">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>    

              <!-- MODAL - TIPO DE NEGOCIO - chague 17,18 -->
              <div class="modal fade" id="modal-agregar-tipo-negocio">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Tipo Negocio</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-tipo-negocio" name="form-tipo-negocio" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-17-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idtipo_negocio" id="idtipo_negocio" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="nombre_tipo_negocio">Nombre Tipo Persona</label>
                                <input type="text" name="nombre_tipo_negocio" id="nombre_tipo_negocio" class="form-control" placeholder="Nombre tipo Persona" />
                              </div>
                            </div>

                              <!-- Descripciòn -->
                              <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="descripcion_tipo_negocio">Descripciòn</label>
                                <textarea name="descripcion_tipo_negocio" id="descripcion_tipo_negocio" class="form-control" rows="2"></textarea>
                              </div>
                            </div>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                              <div class="progress" id="div_barra_progress_tipo">
                                <div id="barra_progress_tipo" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-18-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-tipo-negocio">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_tipo_negocio();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_tipo_negocio">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- MODAL - SUCURSAL - chargue 15,16 -->
              <div class="modal fade" id="modal-agregar-sucursal">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Agregar Sucursal</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <!-- form start -->
                      <form id="form-sucursal" name="form-sucursal" method="POST" autocomplete="off">
                        <div class="card-body">
                          <div class="row" id="cargando-15-fomulario">
                            <!-- id idunidad_medida -->
                            <input type="hidden" name="idsucursal" id="idsucursal" />

                            <!-- nombre_medida -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="apodo_sucursal">Apodo</label>
                                <input type="text" name="apodo_sucursal" class="form-control" id="apodo_sucursal" placeholder="Apodo" />
                              </div>
                            </div>

                            <!-- Codigo -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="codigo_sucursal">Codigo</label>
                                <input type="text" name="codigo_sucursal" class="form-control" id="codigo_sucursal" placeholder="Codigo del sucursal" />
                              </div>
                            </div>                         

                            <!-- Direccion -->
                            <div class="col-lg-12 class_pading">
                              <div class="form-group">
                                <label for="direccion_sucursal">Direccion</label>
                                <textarea name="direccion_sucursal" id="direccion_sucursal" class="form-control" rows="2"></textarea>                              
                              </div>
                            </div>

                            <table class="table">
                              <thead>
                                <tr>                                  
                                  <th class="">Comprobante</th>
                                  <th class="">Serie</th>
                                  <th class="">Número</th>
                                </tr>
                              </thead>
                              <tbody id="comprobante_sucursal">
                                
                              </tbody>
                            </table>

                            <!-- barprogress -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="barra_progress_sucursal_div">
                              <div class="progress" >
                                <div id="barra_progress_sucursal" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
                                  0%
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row" id="cargando-16-fomulario" style="display: none;">
                            <div class="col-lg-12 text-center">
                              <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
                              <br />
                              <h4>Cargando...</h4>
                            </div>
                          </div>
                        </div>
                        <!-- /.card-body -->
                        <button type="submit" style="display: none;" id="submit-form-sucursal">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limpiar_sucursal();">Close</button>
                      <button type="submit" class="btn btn-success" id="guardar_registro_sucursal">Guardar Cambios</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODAL - VER PERFIL BANCO-->
              <div class="modal fade" id="modal-ver-perfil-banco">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content bg-color-0202022e shadow-none border-0">
                    <div class="modal-header">
                      <h4 class="modal-title text-white foto-banco">Foto Banco</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white cursor-pointer" aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body"> 
                      <div id="perfil-banco" class="class-style">
                        <!-- vemos los datos del trabajador -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <?php
          }else{
            require 'noacceso.php';
          }
          require 'footer.php';
          ?>
        </div>
        <!-- /.content-wrapper -->

        <?php  require 'script.php'; ?>
        
        <script type="text/javascript" src="scripts/otros.js"></script>
        <script type="text/javascript" src="scripts/color.js"></script>
        <script type="text/javascript" src="scripts/marca.js"></script>
        <script type="text/javascript" src="scripts/bancos.js"></script>
        <script type="text/javascript" src="scripts/unidades_m.js"></script>
        <script type="text/javascript" src="scripts/tipo.js"></script>
        <script type="text/javascript" src="scripts/tipo_negocio.js"></script>
        <script type="text/javascript" src="scripts/cargo.js"></script>
        <script type="text/javascript" src="scripts/categoria_p.js"></script>
        <script type="text/javascript" src="scripts/sucursal.js"></script>
        <script type="text/javascript" src="scripts/empresa.js"></script>

        <script> $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>
        
      </body>
    </html>

    <?php  
  }
  ob_end_flush();

?>
