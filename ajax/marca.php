<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/Marca.php";

      $marca = new Marca($_SESSION['idusuario']);
            
      date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idmarca            = isset($_POST["idmarca"]) ? limpiarCadena($_POST["idmarca"]) : "";
      $nombre_marca       = isset($_POST["nombre_marca"]) ? limpiarCadena($_POST["nombre_marca"]) : "";
      $descripcion_marca  = isset($_POST["descripcion_marca"]) ? limpiarCadena($_POST["descripcion_marca"]) : "";

      switch ($_GET["op"]) {
        case 'guardar_y_editar_marca':
          if (empty($idmarca)) {
            $rspta = $marca->insertar($nombre_marca, $descripcion_marca );
            echo json_encode($rspta,true);  
          } else {
            $rspta = $marca->editar($idmarca, $nombre_marca, $descripcion_marca );
            echo json_encode($rspta,true);  
          }
        break;
      
        case 'desactivar_marca':      
          $rspta = $marca->desactivar($_GET['id_tabla']);      
          echo json_encode($rspta,true);      
        break;

        case 'eliminar_marca':      
          $rspta = $marca->eliminar($_GET['id_tabla']);      
          echo json_encode($rspta,true);      
        break;
      
        case 'mostrar_marca':      
          $rspta = $marca->mostrar($idmarca);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);      
        break;
      
        case 'verdatos':      
          $rspta = $marca->mostrar($idmarca);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);      
        break;
      
        case 'tbla_principal':
          $rspta = $marca->tbla_principal();
          //Vamos a declarar un array
          $data = [];  $comprobante = '';   $cont = 1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {              
              
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar_marca(' . $reg->idmarca . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar_marca(' . $reg->idmarca . ', \''.  $reg->nombre_marca .  '\')"><i class="fas fa-skull-crossbones"></i> </button>',
                "2" =>$reg->nombre_marca,                
                "3" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
            
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
