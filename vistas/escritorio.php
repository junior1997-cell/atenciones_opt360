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
        <title>Escritorio | Admin JDL</title>

        <?php $title = "Escritorio"; require 'head.php'; ?>
     
      </head>
      <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed ">

        <div class="wrapper">
          <!-- Preloader -->
          <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/svg/logo-short-jdl.svg" alt="AdminLTELogo" width="360" />
          </div>
        
          <?php
            require 'nav.php';
            require 'aside.php';
            if ($_SESSION['escritorio']==1){
              // require 'enmantenimiento.php';
              ?>           
              <!--Contenido-->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0">Tablero</h1>
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="escritorio.php">Home</a></li>
                          <li class="breadcrumb-item active">Tablero</li>
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
                    <!-- Small boxes (Stat box) -->
                    <div class="row">

                      <!-- CANIDAD DE PROYECTOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3 id="cantidad_box_producto" > <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total Trabajadores </p>
                          </div>
                          <div class="icon">
                            <i class="nav-icon fas fa-suitcase"></i>
                          </div>
                          <a href="tours.php" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE PROVEEDORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3 id="cantidad_box_agricultor"> <i class="fas fa-spinner fa-pulse "></i>   </h3>
                            <p>Total Compras</p>
                          </div>
                          <div class="icon"><i class="nav-icon fas fa-map"></i> </div>
                          <a href="paquete.php" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE TRABAJADORES -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3 id="cantidad_box_trabajador"> <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p>Total ventas</p>
                          </div>
                          <div class="icon"> <i class="fa-solid fa-cart-shopping nav-icon"></i> </div>
                          <a href="#" data-toggle="tooltip" data-original-title="Click para visitar" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                      <!-- CANTIDAD DE SERVICIOS -->
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary" style="background-color: #6c757dd9!important;">
                          <div class="inner">
                            <h3 id="cantidad_box_visita"> <i class="fas fa-spinner fa-pulse "></i> </h3>
                            <p> <strong class="vista"><i class="fas fa-spinner fa-pulse "></i></strong> es una vista de la página web </p>
                          </div>
                          <div class="icon"> <i class="fas fa-user-plus"></i> </div>
                          <a href="#" class="small-box-footer" data-toggle="tooltip" data-original-title="Click para visitar">Más info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>

                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.container-fluid -->
                </section>
                <!-- /.content -->

                <!-- Main content -->
                <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-12">
                        <div class="card card-primary card-outline">
                          <div class="card-header">
                            <h1 class="mb-0 text-success text-bold font-size-16px"><i class="fa-solid fa-chart-column"></i> REPORTES</h1>                      
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">

                            <div class="row mb-3">
                              <!-- :::::::::::::::::::::::::::::::: VISITAS POR DIA :::::::::::::::::::::::::::::::: -->
                              <div class="col-md-4 ">
                                
                                <div class="card card-primary">

                                  <div class="card-header">
                                  <span class="text-bold text-lg">Visita</span> <span> por día</span>
                                  </div>

                                  <div class="card-body" >

                                    <div class="position-relative text-center">
                                      <canvas id="radar-chart-visita-por-dia" height="350" ></canvas>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2" ><i class="fas fa-square" style="color: #28a745;" ></i> Total venta</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #ced4da;"></i> Total pago</span>
                                    </div> -->

                                  </div>
                                </div>                                      
                                <!-- /.card -->                      
                              </div>     
                              <!-- /.col -->

                              <!-- :::::::::::::::::::::::::::::::: VISITAS POR MES :::::::::::::::::::::::::::::::: -->
                              <div class="col-md-4 ">
                                
                                <div class="card card-primary">

                                  <div class="card-header">
                                  <span class="text-bold text-lg">Visita</span> <span> por mes</span>
                                  </div>

                                  <div class="card-body" >

                                    <div class="position-relative text-center">
                                      <canvas id="barras-chart-visita-por-mes" height="550" ></canvas>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                      <span class="mr-2" ><i class="fas fa-square" style="color: #28a745;" ></i> Total venta</span>
                                      <span class="mr-2"><i class="fas fa-square" style="color: #ced4da;"></i> Total pago</span>
                                    </div> -->

                                  </div>
                                </div>                                      
                                <!-- /.card -->                      
                              </div>     
                              <!-- /.col -->

                              <!-- :::::::::::::::::::::::::::::::: VISITAS POR PAGINA :::::::::::::::::::::::::::::::: -->
                              <div class="col-md-4 ">
                                <div class="row">
                                  <div class="col-md-12 "> 
                                    <div class="card card-primary">
                                      <div class="card-header">
                                        <!-- <h3 class="card-title">visitas a la página web</h3> -->
                                        <span class="text-bold text-lg">visitas </span> <span> por página </span>

                                      </div>
                                      <div class="card-body">
                                        <canvas id="donut-chart-visita-por-pagina" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                                      </div>
                                    </div>    
                                  </div>    
                                  <!-- /.row -->                      
                                </div>
                              </div>     
                              <!-- /.col -->
                              
                              <!-- :::::::::::::::::::::::::::::::: VENTAS TOURS :::::::::::::::::::::::::::::::: -->
                              <div class="col-md-6 ">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="card card-primary">

                                      <div class="card-header">
                                      <span class="text-bold text-lg">Compras</span> <span> por mes</span>
                                      </div>

                                      <div class="card-body">

                                        <div class="position-relative mb-4">
                                          <canvas id="venta-tours-chart" height="350"></canvas>
                                        </div>

                                        <div class="d-flex flex-row justify-content-end">
                                          <span class="mr-2" ><i class="fas fa-square" style="color: #28a745;" ></i> Total venta</span>
                                          <span class="mr-2"><i class="fas fa-square" style="color: #ced4da;"></i> Total pago</span>
                                        </div>

                                      </div>

                                    </div>
                                  </div>
                                </div>    
                                <!-- /.row -->                      
                              </div>     
                              <!-- /.col -->

                              <!-- :::::::::::::::::::::::::::::::: VENTAS PAQUETE :::::::::::::::::::::::::::::::: -->
                              <div class="col-md-6 ">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="card card-primary">

                                      <div class="card-header">
                                      <span class="text-bold text-lg">Ventas</span> <span> por mes</span>
                                      </div>

                                      <div class="card-body">

                                        <div class="position-relative mb-4">
                                          <canvas id="venta-paquete-chart" height="350"></canvas>
                                        </div>

                                        <div class="d-flex flex-row justify-content-end">
                                          <span class="mr-2" ><i class="fas fa-square" style="color: #28a745;" ></i> Total venta</span>
                                          <span class="mr-2"><i class="fas fa-square" style="color: #ced4da;"></i> Total pago</span>
                                        </div>

                                      </div>

                                    </div>
                                  </div>
                                </div>    
                                <!-- /.row -->                      
                              </div>     
                              <!-- /.col -->
                            </div>
                            <!-- /.row --> 
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

                  <!-- Modal agregar proyecto -->
                 
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

        <!-- OPTIONAL SCRIPTS -->
        <script src="../plugins/chart.js/Chart.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>


        <script type="text/javascript" src="scripts/escritorio.js"></script>

        <script>  $(function () { $('[data-toggle="tooltip"]').tooltip(); }); </script>


        
      </body>
    </html>
    <?php    
  }
  ob_end_flush();
?>
