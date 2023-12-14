<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Empresa.php";

    $empresa = new Empresa($_SESSION['idusuario']);

    date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $iddatos_empresa  = isset($_POST["iddatos_empresa"]) ? limpiarCadena($_POST["iddatos_empresa"]) : "";
    $nombre_empresa   = isset($_POST["nombre_empresa"]) ? limpiarCadena($_POST["nombre_empresa"]) : "";
    $tipo_documento   = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
    $numero_documento = isset($_POST["numero_documento"]) ? limpiarCadena($_POST["numero_documento"]) : "";
    $direccion        = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
    $departamento     = isset($_POST["departamento"]) ? limpiarCadena($_POST["departamento"]) : "";
    $provincia        = isset($_POST["provincia"]) ? limpiarCadena($_POST["provincia"]) : "";
    $distrito         = isset($_POST["distrito"]) ? limpiarCadena($_POST["distrito"]) : "";
    $ubigeo           = isset($_POST["ubigeo"]) ? limpiarCadena($_POST["ubigeo"]) : "";
    $pais             = isset($_POST["pais"]) ? limpiarCadena($_POST["pais"]) : "";
    $telefono         = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
    $correo           = isset($_POST["correo"]) ? limpiarCadena($_POST["correo"]) : "" ;
    
    $nombre_impuesto  = isset($_POST["nombre_impuesto"]) ? limpiarCadena($_POST["nombre_impuesto"]) : "";
    $monto_impuesto   = isset($_POST["monto_impuesto"]) ? limpiarCadena($_POST["monto_impuesto"]) : "";
    $moneda           = isset($_POST["moneda"]) ? limpiarCadena($_POST["moneda"]) : "";
    $simbolo          = isset($_POST["simbolo"]) ? limpiarCadena($_POST["simbolo"]) : "";

    $usuario_sol      = isset($_POST["usuario_sol"]) ? limpiarCadena($_POST["usuario_sol"]) : "";
    $clave_sol        = isset($_POST["clave_sol"]) ? limpiarCadena($_POST["clave_sol"]) : "";
    $estado_certificado = isset($_POST["estado_certificado"]) ? limpiarCadena($_POST["estado_certificado"]) : "";
    $clave_certificado  = isset($_POST["clave_certificado"]) ? limpiarCadena($_POST["clave_certificado"]) : "";    

    switch ($_GET["op"]) {

      case 'guardar_y_editar_empresa':
        // imagen
        if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
          $img_perfil = $_POST["foto1_actual"];
          $flat_img1 = false;
        } else {
          $ext1 = explode(".", $_FILES["foto1"]["name"]);
          $flat_img1 = true;
          $img_perfil = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/empresa/img_perfil/" . $img_perfil);
        }

        // Certificado digital
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
          $certificado_digital = $_POST["doc_old_1"];
          $flat_ficha1 = false;
        } else {
          $ext1 = explode(".", $_FILES["doc1"]["name"]);
          $flat_ficha1 = true;
          $certificado_digital = 'certificado-digital-'.$estado_certificado.'-'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../public/FACT_WebService/Facturacion/src/" . $certificado_digital);
        }

        if (empty($iddatos_empresa)) {
          $rspta = ["status" => 'error_personalizado', "titulo" =>'<b>Upss!!</b>', "message" => 'Porfavor no modifiques el <b>codigo interno</b> antes de actualizar tu iformación.', "data" => [], "user" => $_SESSION['nombre']] ;
          echo json_encode( $rspta, true) ;
        } else {
          // validamos si existe LA IMG para eliminarlo          
          if ($flat_img1 == true) {
            $datos_f1 = $empresa->obtener_perfil_certificado($iddatos_empresa);
            $img1_ant = $datos_f1['data']->fetch_object()->logo;
            if ( !empty( $img1_ant ) ) { unlink("../dist/docs/empresa/img_perfil/" . $img1_ant); }
          }
          //validamos si existe un archivo para eliminarlo
          if ($flat_ficha1 == true) {
            $datos_cert = $empresa->obtener_perfil_certificado($iddatos_empresa);
            $file_cert = $datos_cert['data']->fetch_object()->ruta_certificado;
            if (!empty($file_cert)) { unlink("../public/FACT_WebService/Facturacion/src/" . $file_cert); }
          }
          
          $rspta = $empresa->editar($iddatos_empresa, $nombre_empresa, $tipo_documento, $numero_documento, $direccion, $departamento, $provincia, $distrito,
          $ubigeo, $pais , $telefono, $correo, $nombre_impuesto, $monto_impuesto, $moneda, $simbolo,
          $usuario_sol, $clave_sol, $estado_certificado, $clave_certificado, $img_perfil,$certificado_digital);
          echo json_encode( $rspta, true) ;
        }
      break;      

      case 'mostrar_empresa':
        $rspta = $empresa->mostrar($_POST["idempresa"]);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;    

      case 'tbla_empresa':
        $rspta = $empresa->tbla_empresa();
        //Vamos a declarar un array
        $data = []; $cont = 1;        

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $imagen = (empty($reg->logo) ? 'sin-foto.svg' : $reg->logo );
            $data[] = [              
              "0" => '<button class="btn btn-warning btn-sm" onclick="mostrar_empresa(' . $reg->iddatos_empresa . ');" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' ,
              "1" => '<div class="user-block">'.
                '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/empresa/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil_empresa( \'' . $imagen . '\', \'admin/dist/docs/empresa/img_perfil\', \''.encodeCadenaHtml($reg->nombre_empresa).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="mb-0">' . $reg->nombre_empresa . '</p></span>
                <span class="description"><b>' . $reg->tipo_documento .':</b> '. $reg->numero_documento. '</span>
              </div>',
              "2" => $reg->nombre_impuesto . ' | ' . $reg->monto_impuesto ,
              "3" => $reg->moneda . ' | ' . $reg->simbolo ,
              "4" => '<div class="bg-color-242244245" style="overflow: auto; resize: vertical; height: 45px;"><b>Direc: </b>'. $reg->direccion  . '<br> <b>Dpto: </b>'.$reg->departamento. '<br> <b>Prov: </b>'.$reg->provincia. '<br><b>Dtto: </b>'.$reg->distrito.'</div>' . $toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode( $results, true) ;
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }  

      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
  }
  
  
  ob_end_flush();
?>
