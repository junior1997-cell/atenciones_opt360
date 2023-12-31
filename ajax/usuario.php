<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  switch ($_GET["op"]) {

    case 'verificar':

      require_once "../modelos/Usuario.php";
      $usuario = new Usuario(); 

      $logina = $_POST['logina'];
      $clavea = $_POST['clavea'];

      //Hash SHA256 en la contraseña
      $clavehash = hash("SHA256", $clavea); #echo $logina.' - '. $clavehash;

      $rspta = $usuario->verificar($logina, $clavehash);   //$fetch = $rspta->fetch_object();

      if ( $rspta['status'] == true ) {
        if ( !empty($rspta['data']['usuario']) ) {

          // validamos si esta vacio la "sucursal"
          if (empty($rspta['data']['sucursal']) && $rspta['data']['usuario']['cargo'] != 'Administrador' ) {
            $sucursal = [
              'status'  => 'no_sucursal', 
              'message' => 'Este usuario no tiene asigando una <b>sucursal</b>, pida a su admintrador que le asigne alguno.', 
              'data'    => [
                'usuario'   => $rspta['data']['usuario'],
                'sucursal'  => $rspta['data']['sucursal']
              ]
            ]; 
            echo json_encode($sucursal, true);
          } else {
            // ultima sesion
            $ultima_sesion = $usuario->ultima_sesion($rspta['data']['usuario']['idusuario']);

            //Declaramos las variables de sesión
            $_SESSION['idusuario']    = $rspta['data']['usuario']['idusuario'];
            $_SESSION['nombre']       = $rspta['data']['usuario']['nombres'];
            $_SESSION['imagen']       = $rspta['data']['usuario']['foto_perfil'];
            $_SESSION['login']        = $rspta['data']['usuario']['login'];
            $_SESSION['cargo']        = $rspta['data']['usuario']['cargo'];
            $_SESSION['tipo_documento'] = $rspta['data']['usuario']['tipo_documento'];
            $_SESSION['num_documento'] = $rspta['data']['usuario']['numero_documento'];
            $_SESSION['telefono']     = $rspta['data']['usuario']['celular'];
            $_SESSION['email']        = $rspta['data']['usuario']['correo'];

            //Obtenemos los permisos del usuario
            $marcados = $usuario->listarmarcados($rspta['data']['usuario']['idusuario']);
            
            //Declaramos el array para almacenar todos los permisos marcados
            $valores = [];

            if ($rspta['status']) {
              //Almacenamos los permisos marcados en el array
              foreach ($marcados['data'] as $key => $value) {
                array_push($valores, $value['idpermiso']);
              }
              echo json_encode($rspta);
            }else{
              echo json_encode($marcados);
            }       

            //Determinamos los accesos del usuario
            in_array(1, $valores) ? ($_SESSION['escritorio'] = 1)     : ($_SESSION['escritorio'] = 0);
            in_array(2, $valores) ? ($_SESSION['acceso'] = 1)         : ($_SESSION['acceso'] = 0);
            in_array(3, $valores) ? ($_SESSION['recurso'] = 1)        : ($_SESSION['recurso'] = 0);   
            in_array(4, $valores) ? ($_SESSION['papelera'] = 1)       : ($_SESSION['papelera'] = 0);
            
            // LOGISTICA Y ADQUISICIONES
            in_array(5, $valores) ? ($_SESSION['almacen_producto'] = 1) : ($_SESSION['almacen_producto'] = 0);
            in_array(6, $valores) ? ($_SESSION['venta_producto'] = 1)   : ($_SESSION['venta_producto'] = 0);
            in_array(7, $valores) ? ($_SESSION['compra_producto'] = 1)  : ($_SESSION['compra_producto'] = 0);
            
            // CONTABLE Y FINANCIERO
            in_array(8, $valores) ? ($_SESSION['pago_trabajador'] = 1)  : ($_SESSION['pago_trabajador'] = 0);         
            in_array(9, $valores) ? ($_SESSION['comprobante'] = 1)      : ($_SESSION['comprobante'] = 0);
            in_array(10, $valores) ? ($_SESSION['cotizacion_venta'] = 1): ($_SESSION['cotizacion_venta'] = 0);
          }          

        } else {
          echo json_encode($rspta, true);
        }
      }else{
        
        echo json_encode($rspta, true);
      }
      
    break;
    
    case 'salir':
      //Limpiamos las variables de sesión
      session_unset();
      //Destruìmos la sesión
      session_destroy();
      //Redireccionamos al login
      header("Location: index.php?file=".(isset($_GET["file"]) ? $_GET["file"] : ""));
    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
    
  }
 
  require_once "../modelos/Usuario.php";
  require_once "../modelos/Permiso.php";
  require_once "../modelos/Persona.php";      

  $usuario = new Usuario();  
  $permisos = new Permiso();
  $persona = new Persona();

  date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");

  $imagen_error = "this.src='../dist/svg/user_default.svg'";
  $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

  // ::::::::::::::::::::::::::::::::: D A T O S   U S U A R I O S :::::::::::::::::::::::::::::
  $idusuario        = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
  $trabajador       = isset($_POST["trabajador"]) ? limpiarCadena($_POST["trabajador"]) : "";
  $trabajador_old   = isset($_POST["trabajador_old"]) ? limpiarCadena($_POST["trabajador_old"]) : "";
  $cargo            = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
  $login            = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
  $clave            = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
  $clave_old        = isset($_POST["password-old"]) ? limpiarCadena($_POST["password-old"]) : "";
  $permiso          = isset($_POST['permiso']) ? $_POST['permiso'] : "";

  // ::::::::::::::::::::::::::::::::: D A T O S   T R A B A J A D O R :::::::::::::::::::::::::::::
  $idpersona_per	  	  = isset($_POST["idpersona_per"])? limpiarCadena($_POST["idpersona_per"]):"";
  $id_tipo_persona_per 	= isset($_POST["id_tipo_persona_per"])? limpiarCadena($_POST["id_tipo_persona_per"]):"";
  $nombre_per 		      = isset($_POST["nombre_per"])? limpiarCadena($_POST["nombre_per"]):"";
  $tipo_documento_per 	= isset($_POST["tipo_documento_per"])? limpiarCadena($_POST["tipo_documento_per"]):"";
  $num_documento_per  	= isset($_POST["num_documento_per"])? limpiarCadena($_POST["num_documento_per"]):"";
  $input_socio_per     	= isset($_POST["input_socio_per"])? limpiarCadena($_POST["input_socio_per"]):"";
  $direccion_per		    = isset($_POST["direccion_per"])? limpiarCadena($_POST["direccion_per"]):"";
  $telefono_per		      = isset($_POST["telefono_per"])? limpiarCadena($_POST["telefono_per"]):"";     
  $email_per			      = isset($_POST["email_per"])? limpiarCadena($_POST["email_per"]):"";
  
  $banco                = isset($_POST["banco"])? $_POST["banco"] :"";
  $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
  $cta_bancaria         = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
  $cci_format      	    = isset($_POST["cci"])? $_POST["cci"]:"";
  $cci            	    = isset($_POST["cci"])? $_POST["cci"]:"";
  $titular_cuenta_per		= isset($_POST["titular_cuenta_per"])? limpiarCadena($_POST["titular_cuenta_per"]):"";

  $nacimiento_per       = isset($_POST["nacimiento_per"])? limpiarCadena($_POST["nacimiento_per"]):"";
  $cargo_trabajador_per = isset($_POST["cargo_trabajador_per"])? limpiarCadena($_POST["cargo_trabajador_per"]):"";
  $sueldo_mensual_per   = isset($_POST["sueldo_mensual_per"])? limpiarCadena($_POST["sueldo_mensual_per"]):"";
  $sueldo_diario_per    = isset($_POST["sueldo_diario_per"])? limpiarCadena($_POST["sueldo_diario_per"]):"";
  $edad_per             = isset($_POST["edad_per"])? limpiarCadena($_POST["edad_per"]):"";
    
  $imagen1			        = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

  // ::::::::::::::::::::::::::::::::: D A T O S   S U C U R S A L :::::::::::::::::::::::::::::
  $idpersona_sucursal	  = isset($_POST["idpersona_sucursal"])? limpiarCadena($_POST["idpersona_sucursal"]):"";
  $idpersona_suc	  	  = isset($_POST["idpersona_suc"])? limpiarCadena($_POST["idpersona_suc"]):"";
  $sucursal 	          = isset($_POST["sucursal"])? limpiarCadena($_POST["sucursal"]):"";

  switch ($_GET["op"]) {

    case 'guardar_y_editar_usuario':

      $clavehash = "";

      if (!empty($clave)) {
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $clave);
      } else {
        if (!empty($clave_old)) {
          // enviamos la contraseña antigua
          $clavehash = $clave_old;
        } else {
          //Hash SHA256 en la contraseña
          $clavehash = hash("SHA256", "123456");
        }
      }

      if (empty($idusuario)) {

        $rspta = $usuario->insertar($trabajador, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);

      } else {

        $rspta = $usuario->editar($idusuario, $trabajador,$trabajador_old, $cargo, $login, $clavehash, $permiso);

        echo json_encode($rspta, true);
      }
    break;

    case 'desactivar':
      $rspta = $usuario->desactivar($_GET["id_tabla"]);
      echo json_encode($rspta, true);
    break;

    case 'activar':
      $rspta = $usuario->activar($_GET["id_tabla"]);
      echo json_encode($rspta, true);
    break;

    case 'eliminar':
      $rspta = $usuario->eliminar($_GET["id_tabla"]);
      echo json_encode($rspta, true);
    break;

    case 'mostrar':
      $rspta = $usuario->mostrar($idusuario);
      //Codificar el resultado utilizando json
      echo json_encode($rspta, true);
    break;

    case 'validar_usuario':
      $rspta = $usuario->validar_usuario($_GET["idusuario"],$_GET["login"]);
      //Codificar el resultado utilizando json
      echo json_encode($rspta, true);
    break;

    case 'tbla_principal':

      $rspta = $usuario->listar();
          
      //Vamos a declarar un array
      $data = [];  
      $imagen_error = "this.src='../dist/svg/user_default.svg'"; $cont=1;
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      if ($rspta['status']) {
        foreach ($rspta['data'] as $key => $val) {
          $data[] = [
            "0"=>$cont++,
            "1" => $val['estado'] ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $val['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ($val['cargo']=='Administrador' ? ' <button class="btn btn-danger btn-sm disabled" data-toggle="tooltip" data-original-title="El administrador no se puede eliminar."><i class="fas fa-skull-crossbones"></i> </button>' : ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $val['idusuario'] .', \''.encodeCadenaHtml($val['nombres']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>' ) .
                ' <button class="btn bg-purple  btn-sm" onclick="tabla_sucursal(' . $val['idusuario'] . ', \''.$val['nombres'].'\')" data-toggle="tooltip" data-original-title="Sucursal"><i class="fas fa-store-alt"></i></button>' :
                '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $val['idusuario'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' . 
                ' <button class="btn btn-primary  btn-sm" onclick="activar(' . $val['idusuario'] . ')" data-toggle="tooltip" data-original-title="Recuperar"><i class="fa fa-check"></i></button>'.
                ' <button class="btn bg-purple  btn-sm" onclick="tabla_sucursal(' . $val['idusuario'] . ', \''.$val['nombres'].'\')" data-toggle="tooltip" data-original-title="Sucursal"><i class="fas fa-store-alt"></i></button>',
            "2" => '<div class="user-block">'. 
              '<img class="img-circle" src="../dist/docs/persona/perfil/' . $val['foto_perfil'] . '" alt="User Image" onerror="' . $imagen_error . '">'.
              '<span class="username"><p class="text-primary m-b-02rem" >' . $val['nombres'] . '</p></span>'. 
              '<span class="description"> - ' . $val['tipo_documento'] .  ': ' . $val['numero_documento'] . ' </span>'.
            '</div>',
            "3" => empty( $val['sucursal'])? '-- vacio --': '<span class="text-purple"><i class="fas fa-store-alt"></i> '. $val['sucursal'] . '</span>',
            "4" => $val['celular'],
            "5" => $val['login'],
            "6" => $val['cargo'],
            "7" => nombre_dia_semana( date("Y-m-d", strtotime($val['last_sesion'])) ) .', <br>'. date("d/m/Y", strtotime($val['last_sesion'])) .' - '. date("g:i a", strtotime($val['last_sesion'])) ,
            "8" => ($val['estado'] ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results, true);
      } else {
        echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

    break;

    case 'permisos':
      //Obtenemos todos los permisos de la tabla permisos      
      $rspta = $permisos->listar();

      if ( $rspta['status'] ) {

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = [];

        if ($marcados['status']) {

          //Almacenar los permisos asignados al usuario en el array
          foreach ($marcados['data'] as $key => $value) {
            array_push($valores, $value['idpermiso']);
          }

          $data = ""; $num = 2;  $stado_close = false;
          //Mostramos la lista de permisos en la vista y si están o no marcados <label for=""></label>
          foreach ($rspta['data'] as $key => $value) {

            $div_open = ''; $div_close = '';

            if ( ($key + 1) == 1 ) {                  
              $div_open = '<ol class="list-unstyled row"><div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">'. 
              '<li class="text-primary"><input class="h-1rem w-1rem" type="checkbox" id="marcar_todo" onclick="marcar_todos_permiso();"> ' .
                '<label for="marcar_todo" class="marcar_todo">Marcar Todo</label>'.
              '</li>';                 
            } else {
              if ( ($key + 1) == $num ) { 
                $div_close = '</div>';
                $num += 3;
                $stado_close = true;
              } else {
                if ($stado_close) {
                  $div_open = '<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">';
                  $stado_close = false; 
                }             
              }
            }               
            
            $sw = in_array($value['idpermiso'], $valores) ? 'checked' : '';

            $data .= $div_open.'<li>'. 
              '<div class="form-group mb-0">'.
                '<div class="custom-control custom-checkbox">'.
                  '<input id="permiso_'.$value['idpermiso'].'" class="custom-control-input permiso h-1rem w-1rem" type="checkbox" ' . $sw . ' name="permiso[]" value="' . $value['idpermiso'] . '"> '.
                  '<label for="permiso_'.$value['idpermiso'].'" class="custom-control-label font-weight-normal" >' .$value['icono'] .' '. $value['nombre'].'</label>' . 
                '</div>'.
              '</div>'.
            '</li>'. $div_close;
          }

          $retorno = array(
            'status' => true, 
            'message' => 'Salió todo ok', 
            'data' => $data.'</ol>', 
          );

          echo json_encode($retorno, true);

        } else {
          echo json_encode($marcados, true);
        }

      } else {
        echo json_encode($rspta, true);
      }    

    break;    

    case 'select2Trabajador':

      $rspta = $usuario->select2_trabajador();  $data = "";

      if ($rspta['status']) {

        foreach ($rspta['data'] as $key => $value) {
          $data  .= '<option value=' . $value['idpersona'] . ' title="'.$value['foto_perfil'].'">' . $value['nombres'] . ' - ' . $value['numero_documento'] . '</option>';
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

    case 'select2_cargo_trabajador':
      $rspta=$usuario->select2_cargo_trabajador($_POST['id_persona']);
      echo json_encode($rspta, true);
    break;
    
    // ::::::::::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R :::::::::::::::::::::::::::::
    case 'guardar_y_editar_trabajador':
      // imgen de perfil
      if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
        $imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
      } else {
        $ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
        $imagen1 = $date_now .'__'. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
        move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);          
      }

      if (empty($idpersona_per)){

        $rspta=$persona->insertar($id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$input_socio_per,$email_per,$telefono_per,$banco,$cta_bancaria,$cci,
          $titular_cuenta_per,$direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per,$edad_per, $imagen1);
                    
        echo json_encode($rspta, true);
        
      }else{
        // validamos si existe LA IMG para eliminarlo
        if ($flat_img1 == true) {
          $datos_f1 = $persona->obtenerImg($idpersona_per);
          $img1_ant = $datos_f1['data']['foto_perfil'];
          if ( !empty($img1_ant) ) { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
        }           

        // editamos un persona existente
        $rspta=$persona->editar($idpersona_per,$id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$input_socio_per,$email_per,$telefono_per,$banco,$cta_bancaria,$cci,
          $titular_cuenta_per,$direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per,$edad_per, $imagen1);
          
        echo json_encode($rspta, true);
      }
  
    break;

    // ::::::::::::::::::::::::::::::::: S E C C I O N   S U C U R S A L :::::::::::::::::::::::::::::

    case 'guardar_y_editar_sucursal':
      
      if (empty($idpersona_sucursal)){

        $rspta=$usuario->insertar_sucursal($idpersona_suc, $sucursal);                    
        echo json_encode($rspta, true);
        
      }else{  
        // editamos un persona existente
        $rspta=$usuario->editar_sucursal($idpersona_sucursal, $idpersona_suc, $sucursal);          
        echo json_encode($rspta, true);
      }
  
    break;

    case 'tbla_principal_sucursal':

      $rspta = $usuario->tbla_principal_sucursal($_GET["id_persona"]);
          
      //Vamos a declarar un array
      $data = []; $cont=1;

      if ($rspta['status']) {
        foreach ($rspta['data'] as $key => $value) {
          $data[] = [
            "0"=>$cont++,
            "1" => '<button class="btn btn-warning  btn-sm" onclick="mostrar(' . $value['idpersona_sucursal'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
              ($value['estado'] ? ' <button class="btn btn-danger btn-sm" onclick="quitar_sucursal(' . $value['idpersona_sucursal'] .','. $value['idusuario'] .', \''. $value['apodo_sucursal'] .'\', \''. $value['codigo_sucursal'] .'\', \''. $value['direccion_sucursal'] .'\')" data-toggle="tooltip" data-original-title="Quitar"><i class="fa fa-close"></i></button>'  :                
              ' <button class="btn btn-success btn-sm" onclick="asignar_sucursal(' . $value['idpersona_sucursal'] .','. $value['idusuario'] .', \''. $value['apodo_sucursal'] .'\', \''. $value['codigo_sucursal'] .'\', \''. $value['direccion_sucursal'] .'\')" data-toggle="tooltip" data-original-title="Asignar"><i class="fa fa-check"></i></button>'),
            "2" => $value['apodo_sucursal'],
            "3" => $value['direccion_sucursal'],
            "4" => ($value['estado'] ? '<span class="text-center badge badge-success">Asignado</span>' : '<span class="text-center badge badge-danger">No Asignado</span>').$toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results, true);
      } else {
        echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

    break;

    case 'tbla_principal_sucursal_todos':

      $rspta = $usuario->tbla_principal_sucursal($_GET["id_persona"]);
          
      //Vamos a declarar un array
      $data = []; $cont=1;

      if ($rspta['status']) {
        foreach ($rspta['data'] as $key => $value) {
          $data[] = [
            "0"=>$cont++,
            "1" => ($value['estado'] ? 'Actual' : ' <button class="btn btn-success btn-sm" onclick="abrir_sucursal_para_todos_los_modulos(' . $value['idpersona_sucursal'] .','. $value['idusuario'] .', \''. $value['apodo_sucursal'] .'\', \''. $value['codigo_sucursal'] .'\', \''. $value['direccion_sucursal'] .'\')" data-toggle="tooltip" data-original-title="Abrir Sucursal"><i class="fa fa-check"></i></button>'),
            "2" => $value['apodo_sucursal'],
            "3" => $value['direccion_sucursal'],
            "4" => ($value['estado'] ? '<span class="text-center badge badge-success">Asignado</span>' : '<span class="text-center badge badge-danger">No Asignado</span>').$toltip,
          ];
        }
        $results = [
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
          "data" => $data,
        ];
        echo json_encode($results, true);
      } else {
        echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

    break;

    case 'desactivar_sucursal':
      $rspta = $usuario->desactivar_sucursal($_GET["id_tabla"]);
      echo json_encode($rspta, true);
    break;

    case 'activar_sucursal':
      $rspta = $usuario->activar_sucursal($_GET["idpersona_sucursal"], $_GET["id_usuario"]);
      echo json_encode($rspta, true);
    break;

    case 'eliminar_sucursal':
      $rspta = $usuario->eliminar_sucursal($_GET["id_tabla"]);
      echo json_encode($rspta, true);
    break;

    // default: 
    //   $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
    // break;
  }
  
  ob_end_flush();
?>
