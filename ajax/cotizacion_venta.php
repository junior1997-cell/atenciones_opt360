<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['comprobante'] == 1) {

      require_once "../modelos/Cotizacion_venta.php";
      require_once "../modelos/Persona.php";

      $cotizacion_venta = new Cotizacion_Venta($_SESSION['idusuario']);
      $persona       = new Persona($_SESSION['idusuario']);
            
      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idcotizacion       = isset($_POST["idcotizacion"]) ? limpiarCadena($_POST["idcotizacion"]) : "";
      $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      $fecha_cotizacion   = isset($_POST["fecha_cotizacion"]) ? limpiarCadena($_POST["fecha_cotizacion"]) : "";
      $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
      $numero_comprobante = isset($_POST["numero_comprobante"]) ? limpiarCadena($_POST["numero_comprobante"]) : "";
      $precio_con_igv     = isset($_POST["precio_con_igv"]) ? limpiarCadena($_POST["precio_con_igv"]) : "";
      $impuesto           = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
      $impuesto_decimal   = isset($_POST["impuesto_decimal"]) ? limpiarCadena($_POST["impuesto_decimal"]) : "";
      $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tiempo_produccion  = isset($_POST["tiempo_produccion"]) ? limpiarCadena($_POST["tiempo_produccion"]) : "";
      $validez_cotizacion = isset($_POST["validez_cotizacion"]) ? limpiarCadena($_POST["validez_cotizacion"]) : "";
      $nota               = isset($_POST["nota"]) ? limpiarCadena($_POST["nota"]) : "";

      $subtotal_cotizacion= isset($_POST["subtotal_cotizacion"]) ? limpiarCadena($_POST["subtotal_cotizacion"]) : "";
      $descuento_cotizacion= isset($_POST["descuento_cotizacion"]) ? limpiarCadena($_POST["descuento_cotizacion"]) : "";
      $igv_cotizacion     = isset($_POST["igv_cotizacion"]) ? limpiarCadena($_POST["igv_cotizacion"]) : "";
      $total_cotizacion   = isset($_POST["total_cotizacion"]) ? limpiarCadena($_POST["total_cotizacion"]) : "";
      

      // :::::::::::::::::::::::::::::::::::: D A T O S   P E R S O N A ::::::::::::::::::::::::::::::::::::::

      $idpersona	  	  = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
      $id_tipo_persona 	= isset($_POST["id_tipo_persona"])? limpiarCadena($_POST["id_tipo_persona"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";     
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco            = isset($_POST["banco"])? $_POST["banco"] :"";
      $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria     = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $cci            	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

      $nacimiento       = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $cargo_trabajador = isset($_POST["cargo_trabajador"])? limpiarCadena($_POST["cargo_trabajador"]):"";
      $sueldo_mensual   = isset($_POST["sueldo_mensual"])? limpiarCadena($_POST["sueldo_mensual"]):"";
      $sueldo_diario    = isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
      $edad             = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";
      
      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      
      switch ($_GET["op"]) {
        case 'guardar_y_editar_cotizacion':
          if (empty($idcotizacion)) {
            $rspta = $cotizacion_venta->insertar( $idproveedor, $fecha_cotizacion, $tipo_comprobante, $serie_comprobante, $numero_comprobante, $precio_con_igv, $impuesto, $impuesto_decimal,
            $forma_pago, $tiempo_produccion, $validez_cotizacion, $nota, $subtotal_cotizacion, $descuento_cotizacion, $igv_cotizacion, $total_cotizacion, 
            $_POST["idproducto"], $_POST["ficha_tecnica"], $_POST["nombre_producto"], $_POST["nombre_marca"], $_POST["nombre_color"], $_POST["nombre_categoria"], 
            $_POST["unidad_medida"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"], $_POST["precio_venta"], $_POST["descuento"], $_POST["subtotal_producto"]);
            echo json_encode($rspta,true);
          } else {
            $rspta = $cotizacion_venta->editar($idcotizacion, $idproveedor, $fecha_cotizacion, $tipo_comprobante, $serie_comprobante, $numero_comprobante, $precio_con_igv, $impuesto, $impuesto_decimal,
            $forma_pago, $tiempo_produccion, $validez_cotizacion, $nota, $subtotal_cotizacion, $descuento_cotizacion, $igv_cotizacion, $total_cotizacion, 
            $_POST["idproducto"], $_POST["ficha_tecnica"], $_POST["nombre_producto"], $_POST["nombre_marca"], $_POST["nombre_color"], $_POST["nombre_categoria"], 
            $_POST["unidad_medida"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"], $_POST["precio_venta"], $_POST["descuento"], $_POST["subtotal_producto"]);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':      
          $rspta = $cotizacion_venta->desactivar($_GET['id_tabla']);      
          echo json_encode($rspta,true);      
        break;

        case 'eliminar':      
          $rspta = $cotizacion_venta->eliminar($_GET['id_tabla']);      
          echo json_encode($rspta,true);      
        break;
      
        case 'mostrar':      
          $rspta = $cotizacion_venta->mostrar($idcomprobante);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);      
        break;
      
        case 'verdatos':      
          $rspta = $cotizacion_venta->mostrar($idcomprobante);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);      
        break;
      
        case 'tbla_principal':
          $rspta = $cotizacion_venta->tbla_principal();
          //Vamos a declarar un array
          $data = [];  $comprobante = '';   $cont = 1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              empty($reg->comprobante)
                ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" .(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idcomprobante . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idcomprobante . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'". ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                    ' <button class="btn btn-info btn-sm" onclick="ver_datos(' . $reg->idcomprobante . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" =>'<div class="user-block">
                  <span class="username ml-0" ><p class="text-primary m-b-02rem" >' . ((empty($reg->nombres)) ? 'Sin Razón social' : $reg->nombres ) .'</p> </span>
                  <span class="description ml-0" >'. $reg->tipo_documento .': ' . (empty($reg->numero_documento) ? "Sin Ruc" : $reg->numero_documento) . '</span>         
                </div>',
                "3" => $reg->forma_de_pago,
                "4" =>'<div class="user-block">
                  <span class="username ml-0"> 
                  <p class="text-primary m-b-02rem" >' .  $reg->tipo_comprobante .   '</p> </span>
                  <span class="description ml-0" >N° ' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>         
                </div>',
                "5" => $reg->fecha_ingreso,
                "6" =>$reg->precio_con_igv,
                "7" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "8" => $comprobante. $toltip,

                "9"=>$reg->nombres,
                "10"=>$reg->tipo_documento,
                "11"=>(empty($reg->numero_documento) ? "Sin Ruc" : $reg->numero_documento),
                "12"=>$reg->tipo_comprobante,
                "13"=>$reg->numero_comprobante,
                "14"=>$reg->precio_sin_igv,
                "15"=>$reg->precio_igv,
                "16"=>$reg->tipo_gravada,
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;
      
        case 'total':
          $rspta = $cotizacion_venta->total();
          echo json_encode($rspta,true);
        break;
      
        case 'selecct_produc_o_provee':

          $rspta = $cotizacion_venta->selecct_produc_o_provee(); $cont = 1; $data = "";

          if ($rspta['status']) {
  
            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['idpersona']. '>'.( !empty($value['nombres']) ?  $value['tipo'].' : '.$value['nombres'].' - ' : '') .$value['numero_documento'].'</option>';
    
            }
  
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => '<option value="vacio">Sin proveedor</option>'.$data, 
            );
    
            echo json_encode($retorno, true);
  
          } else {
  
            echo json_encode($rspta, true); 
          }

        break;

        //opcion para mostrar la numeracion y la serie_comprobante de la ticket
        case 'mostrar_num_ticket':
          $idsucursal = $_REQUEST["idsucursal"];
          //mostrando el numero de boleta de la tabla comprobantes
          require_once "../modelos/Comprobantes.php";
          $comprobantes = new Comprobantes();

          $rspta = $comprobantes->mostrar_numero_cotizacion($idsucursal);
          $data = array();
          while ($reg = $rspta->fetch_object()) {
            $data[] = array(
              $num_comp_tic = $reg->num_comprobante
            );
          }
          $numero_tic_comp = (int)$num_comp_tic;
          //fin de mostrar numero de boleta de la tabla comprobantes
          $rspta = $venta->numero_venta_cotizacion($idsucursal);
          $data = array();
          $numerot = $numero_tic_comp;

          while ($reg = $rspta->fetch_object()) {
            $data[] = array(
              $numerot = $reg->num_comprobante
            );
          }
          $numero_ticket = (int)$numerot;
          $new_ticket = '';

          if ($numero_ticket == 9999999 or empty($numerot)) {
            $new_ticket = '0000001';
            echo json_encode($new_ticket);
          } elseif ($numerot == 9999999) {
            $new_ticket = '0000001';
            echo json_encode($new_ticket);
          } else {
            $sumatic = $numero_ticket + 1;
            echo json_encode($sumatic);
          }
          //$num = (int)$numerof; 
          //echo json_encode($numerof);
        break;

        case 'mostrar_s_ticket':
          $idsucursal = $_REQUEST["idsucursal"];
          //mostrando el numero de factura de la tabla comprobantes
          require_once "../modelos/Comprobantes.php";
          $comprobantes = new Comprobantes();

          $rspta = $comprobantes->mostrar_serie_cotizacion($idsucursal);
          $data = array();
          while ($reg = $rspta->fetch_object()) {
            $data[] = array(
              $serie_comp_tic = $reg->serie_comprobante,
              $num_comp_tic = $reg->num_comprobante
            );
          }
          $serie_tic_comp = (int)$serie_comp_tic;
          $num_tic_comp = (int)$num_comp_tic;
          //fin de mostrar numero de factura de la tabla comprobantes
          $rspta = $venta->numero_serie_cotizacion($idsucursal);
          $data = array();
          $numero_s_tic = $serie_tic_comp;
          $numero_bolet = $num_tic_comp;

          while ($reg = $rspta->fetch_object()) {
            $data[] = array(
              $numero_s_tic = $reg->serie_comprobante,
              $numero_bolet = $reg->num_comprobante
            );
          }
          $num_s_ticket = (int)$numero_s_tic;
          $nuew_serie_ticket = 0;
          $numbo = (int)$numero_bolet;
          if ($numbo == 9999999 or empty($numero_s_tic)) {
            $nuew_serie_ticket = $num_s_ticket + 1;
            echo json_encode($nuew_serie_ticket);
          } else {
            echo json_encode($num_s_ticket);
          }
        break; //fin de opcion de mostrar num_comprobante y serie_comprobante del ticket


        case 'select_tipo_persona':
          $rspta = $cotizacion_venta->select_tipo_persona(); $cont = 1; $data = "";

          if ($rspta['status']) {
  
            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['idtipo_persona']. '>'.( !empty($value['nombre']) ?  $value['nombre'] : '') .'</option>';
    
            }
  
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => $data, 
            );
    
            echo json_encode($retorno, true);
  
          } else {
  
            echo json_encode($rspta, true); 
          }
        break;

                
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardarpersona':
    
        if (empty($idpersona)){

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
					} else {
						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
            $imagen1 = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);						
					}

          $rspta=$persona->insertar($id_tipo_persona,$tipo_documento,$num_documento,$nombre,$email,$telefono,$banco,$cta_bancaria,$cci,
          $titular_cuenta,$direccion,$nacimiento,$cargo_trabajador,$sueldo_mensual,$sueldo_diario,$edad, $imagen1);
          echo json_encode($rspta, true);
          
        }else{
    
          echo "error";
        }
    
      break;

        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");      
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
      }
      
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>
