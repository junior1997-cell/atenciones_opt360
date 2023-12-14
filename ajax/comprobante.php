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

      require_once "../modelos/Comprobante.php";
      require_once "../modelos/Persona.php";

      $comprobante_m = new Comprobante($_SESSION['idusuario']);
      $persona       = new Persona($_SESSION['idusuario']);
            
      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idcomprobante   = isset($_POST["idcomprobante"]) ? limpiarCadena($_POST["idcomprobante"]) : "";      
      $idpersona        = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";  
      $fecha_i          = isset($_POST["fecha_i"]) ? limpiarCadena($_POST["fecha_i"]) : "";
      $forma_pago       = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante  = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal         = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv              = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      
      $precio_parcial   = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion      = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc              = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social     = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion        = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      // :::::::::::::::::::::::::::::::::::: D A T O S   P E R S O N A ::::::::::::::::::::::::::::::::::::::

      $idpersona_p	  	  = isset($_POST["idpersona_p"])? limpiarCadena($_POST["idpersona_p"]):"";
      $id_tipo_persona_p 	= isset($_POST["id_tipo_persona_p"])? limpiarCadena($_POST["id_tipo_persona_p"]):"";
      $nombre_p 		      = isset($_POST["nombre_p"])? limpiarCadena($_POST["nombre_p"]):"";
      $tipo_documento_p 	= isset($_POST["tipo_documento_p"])? limpiarCadena($_POST["tipo_documento_p"]):"";
      $num_documento_p  	= isset($_POST["num_documento_p"])? limpiarCadena($_POST["num_documento_p"]):"";
      $direccion_p		    = isset($_POST["direccion_p"])? limpiarCadena($_POST["direccion_p"]):"";
      $telefono_p		      = isset($_POST["telefono_p"])? limpiarCadena($_POST["telefono_p"]):"";     
      $email_p			      = isset($_POST["email_p"])? limpiarCadena($_POST["email_p"]):"";

      $banco_p            = isset($_POST["banco"])? $_POST["banco"] :"";
      $cta_bancaria_format_p  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria_p     = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci_format_p      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $cci_p            	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta_p		= isset($_POST["titular_cuenta_p"])? limpiarCadena($_POST["titular_cuenta_p"]):"";

      $nacimiento_p       = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $edad_p             = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";

      $cargo_trabajador_p = isset($_POST["cargo_trabajador_p"])? limpiarCadena($_POST["cargo_trabajador_p"]):"";
      $sueldo_mensual_p   = isset($_POST["sueldo_mensual_p"])? limpiarCadena($_POST["sueldo_mensual_p"]):"";
      $sueldo_diario_p    = isset($_POST["sueldo_diario_p"])? limpiarCadena($_POST["sueldo_diario_p"]):"";      
      
      $imagen1_p			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

      switch ($_GET["op"]) {
        case 'guardar_y_editar_comprobante':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = $date_now .'__'.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/comprobante/comprobante/" . $comprobante);
          }
      
          if (empty($idcomprobante)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $comprobante_m->insertar($idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $comprobante_m->ficha_tec($idcomprobante);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/comprobante/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $comprobante_m->editar($idcomprobante,$idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante);
            //var_dump($idcomprobante,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $comprobante_m->desactivar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;

        case 'eliminar':
      
          $rspta = $comprobante_m->eliminar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $comprobante_m->mostrar($idcomprobante);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $comprobante_m->mostrar($idcomprobante);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'tbla_principal':
          $rspta = $comprobante_m->tbla_principal( $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
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
          $rspta = $comprobante_m->total();
          echo json_encode($rspta,true);
        break;
      
        case 'selecct_produc_o_provee':

          $rspta = $comprobante_m->selecct_produc_o_provee(); $cont = 1; $data = "";

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

        case 'select_tipo_persona':
          $rspta = $comprobante_m->select_tipo_persona(); $cont = 1; $data = "";

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
      case 'guardar_y_editar_persona':
    
        if (empty($idpersona_p)){

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
						$imagen1_p=$_POST["foto1_actual"]; $flat_img1 = false;
					} else {
						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
            $imagen1_p = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1_p);						
					}

          $rspta=$persona->insertar($id_tipo_persona_p,$tipo_documento_p,$num_documento_p,$nombre_p,$email_p,$telefono_p,$banco_p,$cta_bancaria_p,$cci_p,
          $titular_cuenta_p,$direccion_p,$nacimiento_p,$cargo_trabajador_p,$sueldo_mensual_p,$sueldo_diario_p,$edad_p, $imagen1_p);
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
