<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['pago_trabajador'] == 1) {

      require_once "../modelos/Pago_trabajador.php";

      $pago_trabajador = new PagoTrabajador($_SESSION['idusuario']);

      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      // ::::::::::::::::: MES TRABAJADOR :::::::::::::::::
      $idmes_pago_trabajador= isset($_POST["idmes_pago_trabajador"])? limpiarCadena($_POST["idmes_pago_trabajador"]):"";
      $idpersona            = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
      $mes                  = isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";
      $anio                 = isset($_POST["anio"])? limpiarCadena($_POST["anio"]):"";

      // ::::::::::::::::: PAGO TRABAJADOR :::::::::::::::::
      $idpago_trabajador	  	= isset($_POST["idpago_trabajador"])? limpiarCadena($_POST["idpago_trabajador"]):"";
      $idmes_pago_trabajador_p= isset($_POST["idmes_pago_trabajador_p"])? limpiarCadena($_POST["idmes_pago_trabajador_p"]):"";
      $nombre_mes		          = isset($_POST["nombre_mes"])? limpiarCadena($_POST["nombre_mes"]):"";
      $monto		              = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $fecha_pago		          = isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
      $descripcion		        = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      $comprobante			      = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";

      switch ($_GET["op"]) {     

        case 'tbla_trabajador':          

          $rspta=$pago_trabajador->tbla_principal();
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {           
              
              $imagen = (empty($value['foto_perfil']) ? 'user_default.svg' : $value['foto_perfil']) ;
          
              $data[]=array(
                "0"=>$cont++,                
                "1"=>'<div class="user-block">
                  <img class="img-circle cursor-pointer" src="../dist/docs/persona/perfil/'.$imagen.'" alt="User Image" onerror="'.$imagen_error.'" onclick="ver_img_persona(\'' . $imagen . '\', \'admin/dist/docs/persona/perfil\',  \''.encodeCadenaHtml($value['nombres']).'\');" data-toggle="tooltip" data-original-title="Ver foto">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                </div>',
                "2"=> $value['cargo'],
                "3"=> '<div>
                  <span class="description">Mensual: <b>'. number_format($value['sueldo_mensual']) .'</b> </span><br>
                  <span class="description">Diario: <b> '. $value['sueldo_diario'] .'</b> </span>
                </div>',
                "4"=>'<a href="tel:+51'.quitar_guion($value['celular']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['celular'] . '</a>',
                "5"=> '<button class="btn btn-warning " onclick="tbla_pago_trabajador(' . $value['idpersona'] . ',\''.$value['nombres'].'\',\''.$value['sueldo_mensual'].'\', \''.$value['cargo'].'\')" data-toggle="tooltip" data-original-title="Agregar mes de pago"><i class="fas fa-hand-holding-usd fa-lg"></i></button>',
                "6"=>  $value['pago'],
                
                "7"=>(($value['estado'])?'<span class="text-center badge badge-success">Activado</span>': '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "8"=> $value['nombres'],
                "9"=> $value['tipo_documento'],
                "10"=> $value['numero_documento'],
                "11"=> format_d_m_a($value['fecha_nacimiento']),
                "12"=> calculaedad($value['fecha_nacimiento']),
                "13"=> number_format($value['sueldo_mensual']),
                "14"=> $value['sueldo_diario'],

              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;         

        case 'verdatos':
          $rspta=$pago_trabajador->verdatos($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 

        case 'datos_trabajador':
          $rspta=$pago_trabajador->datos_trabajador($_POST["idtrabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;  

        /* :::::::::::::::::::::::::::::: S E C C I O N   M E S :::::::::::::::::::::::::::::: */
        case 'guardaryeditar_mes_pago':
          
          if (empty($idmes_pago_trabajador)){
            
            $rspta=$pago_trabajador->insertar_mes_pago($idpersona,$mes,$anio);            
            echo json_encode($rspta, true);
  
          }else {
            
            $rspta=$pago_trabajador->actualizar_mes_pago($idmes_pago_trabajador, $idpersona,$mes,$anio);            
            echo json_encode($rspta, true);
          }            

        break;

        case 'ver_datos_mes':
          $rspta=$pago_trabajador->ver_datos_mes($_POST["id_mes"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break; 

        case 'tbla_mes_pago':          

          $rspta=$pago_trabajador->tbla_mes_pago($_GET["idpersona"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {             
          
              $data[]=array(
                "0"=> $cont++,
                "1"=> '<button class="btn btn-warning btn-sm" onclick="ver_datos_mes(' . $value['idmes_pago_trabajador'] . ')"><i class="fas fa-pencil-alt"></i></button>',
                "2"=> $value['anio'],
                "3"=> $value['mes_nombre'],
                "4"=> '<button type="button" class="btn btn-success" onclick="ver_desglose_de_pago('.$value['idmes_pago_trabajador'].',\''.$value['mes_nombre'].'\');" >Pagos</button>',
                "5"=> $value['pago_total_por_meses'],
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break; 

        /* :::::::::::::::::::::::::::::: S E C C I O N   P A G O S :::::::::::::::::::::::::::::: */
        case 'guardar_editar_pago':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = $date_now .'__'.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/pago_trabajador/comprobante/" . $comprobante);
          }
      
          if (empty($idpago_trabajador)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $pago_trabajador->insertar_pago($idmes_pago_trabajador_p,$nombre_mes,$monto,$fecha_pago,$descripcion,$comprobante);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $pago_trabajador->obtenerImg($idpago_trabajador);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/pago_trabajador/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $pago_trabajador->editar_pago($idpago_trabajador,$idmes_pago_trabajador_p,$nombre_mes,$monto,$fecha_pago,$descripcion,$comprobante);
            //var_dump($idotro_ingreso,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;

        case 'desactivar_pago':
          $rspta=$pago_trabajador->desactivar_pago($_GET["id_tabla"]);
         echo json_encode($rspta, true);
        break;

        case 'eliminar_pago':
          $rspta=$pago_trabajador->eliminar_pago($_GET["id_tabla"]);
          echo json_encode($rspta, true);
        break;

        case 'mostrar_pago':
          $rspta=$pago_trabajador->mostrar_pago($idpago_trabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'listar_pago':
          $rspta=$pago_trabajador->listar_pago( $_GET["idmes_pago_trabajador"]);
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {       
              
            $doc = (empty($value['comprobante']) ? '<a href="#" class="btn btn-sm btn-outline-info" data-toggle="tooltip" data-original-title="Vacio" ><i class="fa-regular fa-file-pdf fa-2x"></i></a>' : '<a href="../dist/docs/pago_trabajador/comprobante/'.$value['comprobante'].'" target="_blank" class="btn btn-sm btn-info" data-toggle="tooltip" data-original-title="Ver documento"><i class="fa-regular fa-file-pdf fa-2x"></i></a>');              
          
              $data[]=array(
                "0"=>$cont++,
                "1"=> '<button type="button" class="btn btn-warning btn-sm" onclick="mostrar_pago('.$value['idpago_trabajador'].');" ><i class="fas fa-pencil-alt"></i></button>'.
                      ' <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_pago('.$value['idpago_trabajador'].',\'Pago con fecha - '.$value['fecha_pago'].'\');" ><i class="fas fa-skull-crossbones"></i></button>',
                "2"=> $value['fecha_pago'],
                "3"=>$value['monto'],
                "4"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$value['descripcion'].'</textarea>',
                "5"=>$doc,
              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;
        
        case 'formato_banco':           
          $rspta=$pago_trabajador->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        case 'total_pago_trabajador':
          $rspta=$pago_trabajador->total_pago_trabajador($_POST["idmes_pago_trabajador"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        /* =========================== P A G O   A L L   T R A B A J A D O R =========================== */
        case 'pago_all_trabajador':
          $rspta=$pago_trabajador->tbla_principal();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;

        case 'guardar_y_editar_all_pago':
          
          if (empty($idmes_pago_trabajador)){
            
            $rspta=$pago_trabajador->insertar_mes_pago($idpersona,$mes,$anio);            
            echo json_encode($rspta, true);
  
          }else {
            
            $rspta=$pago_trabajador->actualizar_mes_pago($idmes_pago_trabajador, $idpersona,$mes,$anio);            
            echo json_encode($rspta, true);
          }            

        break;

        /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */
        

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();

?>
