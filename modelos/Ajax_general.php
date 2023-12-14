<?php 
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  Class Ajax_general
  {
    //Implementamos nuestro variable global
    public $id_usr_sesion;

    //Implementamos nuestro constructor
    public function __construct($id_usr_sesion = 0)
    {
      $this->id_usr_sesion = $id_usr_sesion;
    } 

    //CAPTURAR PERSONA  DE RENIEC 
    public function datos_reniec($dni) { 

      $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      return json_decode( $json, true );
    }

    public function consultaDniReniec($ruc)	{ 
      $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
      $nndnii = $_GET['nrodni'];

      // Iniciar llamada a API
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $nndnii,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Referer: https://apis.net.pe/consulta-dni-api',
          'Authorization: Bearer' . $token
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      // Datos listos para usar
      return json_decode($response);
    }

    //CAPTURAR PERSONA  DE SUNAT
    public function datos_sunat($ruc)	{ 
      $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      return json_decode( $json, true );

    }  

    public function consultaRucSunat($ruc)	{ 
      $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';  

      // Iniciar llamada a API
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Referer: https://apis.net.pe/api-ruc',
          'Authorization: Bearer' . $token
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      // Datos listos para usar
      return json_decode($response);
    }
    /* ══════════════════════════════════════ C O M P R O B A N T E  ══════════════════════════════════════ */

    //Implementamos un método para activar categorías
    public function autoincrement_comprobante() {
      $update_producto = "SELECT * FROM autoincrement_comprobante WHERE idautoincrement_comprobante = '1'";
      $val =  ejecutarConsultaSimpleFila($update_producto); if ( $val['status'] == false) {return $val; }   

      $compra_producto_f = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_f']) ? 1 : (intval($val['data']['compra_producto_f']) +1)); 
      $compra_producto_b = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_b']) ? 1 : (intval($val['data']['compra_producto_b']) +1));
      $compra_producto_nv = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_nv']) ? 1 : (intval($val['data']['compra_producto_nv']) +1));

      $venta_producto_f =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_f']) ? 1 : (intval($val['data']['venta_producto_f']) +1)); 
      $venta_producto_b =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_b']) ? 1 : (intval($val['data']['venta_producto_b']) +1)); 
      $venta_producto_nv =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_nv']) ? 1 : (intval($val['data']['venta_producto_nv']) +1)); 

      $compra_cafe_f = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_f']) ? 1 : (intval($val['data']['compra_cafe_f']) +1));
      $compra_cafe_b = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_b']) ? 1 : (intval($val['data']['compra_cafe_b']) +1));
      $compra_cafe_nv = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_nv']) ? 1 : (intval($val['data']['compra_cafe_nv']) +1));

      $venta_cafe_f = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_f']) ? 1 : (intval($val['data']['venta_cafe_f']) +1));
      $venta_cafe_n = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_n']) ? 1 : (intval($val['data']['venta_cafe_n']) +1));
      $venta_cafe_nv = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_nv']) ? 1 : (intval($val['data']['venta_cafe_nv']) +1));

      return $sw = array( 'status' => true, 'message' => 'todo okey bro', 
        'data' => [
          'compra_producto_f'=> zero_fill($compra_producto_f, 6), 
          'compra_producto_b'=> zero_fill($compra_producto_b, 6), 
          'compra_producto_nv'=> zero_fill($compra_producto_nv, 6),

          'venta_producto_f'=> zero_fill($venta_producto_f, 6), 
          'venta_producto_b'=> zero_fill($venta_producto_b, 6), 
          'venta_producto_nv'=> zero_fill($venta_producto_nv, 6), 

          'compra_cafe_f'=> zero_fill($compra_cafe_f, 6), 
          'compra_cafe_b'=> zero_fill($compra_cafe_b, 6), 
          'compra_cafe_nv'=> zero_fill($compra_cafe_nv, 6), 

          'venta_cafe_f'=> zero_fill($venta_cafe_f, 6),
          'venta_cafe_n'=> zero_fill($venta_cafe_n, 6),
          'venta_cafe_nv'=> zero_fill($venta_cafe_nv, 6),
          
        ] 
      );      
    }

    /* ══════════════════════════════════════ T R A B A J A D O R ══════════════════════════════════════ */

    public function select2_trabajador(){
      $sql = "SELECT t.idtrabajador as id, t.nombres as nombre, t.tipo_documento as documento, t.sueldo_mensual, t.numero_documento, t.imagen_perfil, ct.nombre as cargo_trabajador
      FROM trabajador as t, cargo_trabajador as ct WHERE ct.idcargo_trabajador = t.idcargo_trabajador AND t.estado = '1' AND t.estado_delete = '1' ORDER BY t.nombres ASC;";
      return ejecutarConsultaArray($sql);
    }

    public function select2_cargo_trabajador() {
      $sql = "SELECT * FROM cargo_trabajador WHERE  idcargo_trabajador > 1 AND estado='1' AND estado_delete = '1' ORDER BY nombre ASC";
      return ejecutarConsulta($sql);
    }
    
    /* ══════════════════════════════════════ C L I E N T E  ══════════════════════════════════════ */
    public function select2_cliente() {
      $sql = "SELECT p.idpersona, p.idtipo_persona, p.idbancos, p.nombres, p.es_socio, p.tipo_documento,  p.numero_documento, p.foto_perfil, tp.nombre as tipo_persona
      FROM persona AS p, tipo_persona as tp
      WHERE p.idtipo_persona = tp.idtipo_persona and p.idtipo_persona = 2 and p.estado='1' AND p.estado_delete = '1' AND p.idpersona > 1 ORDER BY p.nombres ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ TIPO PERSONA  ══════════════════════════════════════ */
    public function select2_tipo_persona() {
      $sql = "SELECT idtipo_persona, nombre, descripcion FROM tipo_persona WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ P R O V E E D O R -- C L I E N T E S  ══════════════════════════════════════ */

    public function select2_proveedor_cliente($tipo) {
      $sql = "SELECT idpersona, nombres, tipo_documento, numero_documento, foto_perfil FROM persona 
      WHERE idtipo_persona ='$tipo' AND estado='1' AND estado_delete ='1'";
      return ejecutarConsulta($sql);
      // var_dump($return);die();
    }

    /* ══════════════════════════════════════ S U C U R S A L  ══════════════════════════════════════ */

    public function select2_sucursal() {
      $sql = "SELECT * FROM sucursal WHERE estado='1' AND estado_delete ='1'";
      return ejecutarConsulta($sql);
      // var_dump($return);die();
    }

    /* ══════════════════════════════════════ B A N C O ══════════════════════════════════════ */

    public function select2_banco() {
      $sql = "SELECT idbancos as id, nombre, alias, icono FROM bancos WHERE estado='1' AND estado_delete = '1' AND idbancos > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */

    public function select2_color() {
      $sql = "SELECT idcolor AS id, nombre_color AS nombre, hexadecimal FROM color WHERE idcolor > 1 AND estado='1' AND estado_delete = '1' ORDER BY nombre_color ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ M A R C A ════════════════════════════ */

    public function select2_marca() {
      $sql = "SELECT idmarca, nombre_marca FROM marca WHERE idmarca > 1 AND estado=1 and estado_delete=1;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A ══════════════════════════════════════ */

    public function select2_unidad_medida() {
      $sql = "SELECT idunidad_medida AS id, nombre, abreviatura FROM unidad_medida WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */

    public function select2_categoria() {
      $sql = "SELECT idcategoria_producto as id, nombre FROM categoria_producto WHERE estado='1' AND estado_delete = '1' AND idcategoria_producto > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_categoria_all() {
      $sql = "SELECT idcategoria_producto as id, nombre FROM categoria_producto WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ P R O D U C T O ══════════════════════════════════════ */
    public function mostrar_producto($idproducto)  {
      $data = []; $array_marca = []; $array_marca_name = [];

      $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.idmarca, p.idcolor, p.nombre, p.contenido_neto, p.precio_venta, 
      p.precio_compra, p.stock, p.descripcion, p.imagen,
      um.nombre as unidad_medida, um.abreviatura, cp.nombre as nombre_categoria, c.nombre_color, m.nombre_marca
      FROM producto AS p, unidad_medida AS um, categoria_producto as cp, color AS c, marca AS m
      WHERE p.idunidad_medida = um.idunidad_medida AND p.idcategoria_producto = cp.idcategoria_producto AND p.idcolor = c.idcolor 
      AND p.idmarca = m.idmarca AND p.idproducto = '$idproducto'";
      $activos = ejecutarConsultaSimpleFila($sql); if ($activos['status'] == false) { return  $activos;}

      if ( empty($activos['data'])  ) {
        return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => null ];
      }else{
       
        
        $data = [
          'idproducto'          => $activos['data']['idproducto'],
          'idcategoria_producto'=> $activos['data']['idcategoria_producto'],
          'idunidad_medida'     => $activos['data']['idunidad_medida'],
          'idmarca'             => $activos['data']['idmarca'],
          'idcolor'             => $activos['data']['idcolor'],
          'nombre_producto'     => decodeCadenaHtml($activos['data']['nombre']),
          'contenido_neto'      => $activos['data']['contenido_neto'],
          'precio_venta'        => (empty($activos['data']['precio_venta']) ? 0 : floatval($activos['data']['precio_venta']) ),
          'precio_compra'       => (empty($activos['data']['precio_compra']) ? 0 : floatval($activos['data']['precio_compra']) ),
          'stock'               => (empty($activos['data']['stock']) ? 0 : floatval($activos['data']['stock']) ),
          'descripcion'         => $activos['data']['descripcion'],
          'imagen'              => $activos['data']['imagen'],
          'unidad_medida'       => $activos['data']['unidad_medida'],
          'abreviatura'         => $activos['data']['abreviatura'],   
          'nombre_categoria'    => $activos['data']['nombre_categoria'],
          'nombre_color'        => $activos['data']['nombre_color'],
          'nombre_marca'        => $activos['data']['nombre_marca'],          
                 
        ];

        return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];  
      }       
    }
    //funcion para mostrar registros de prosuctos
    public function tblaProductos() {
      $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.nombre, p.contenido_neto, p.precio_venta, p.precio_compra,
      p.stock, p.descripcion, p.imagen, p.estado,  
      um.nombre as nombre_medida, cp.nombre AS categoria, m.nombre_marca, c.nombre_color
      FROM producto as p, unidad_medida AS um, categoria_producto AS cp, marca as m, color as c
      WHERE p.idcategoria_producto = cp.idcategoria_producto and p.idunidad_medida = um.idunidad_medida and p.idmarca = m.idmarca and p.idcolor = c.idcolor
      and p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
      return ejecutarConsulta($sql);
    }
    /* ══════════════════════════════════════ S E R V i C I O S  M A Q U I N A RI A ════════════════════════════ */

    public function select2_servicio($tipo) {
      $sql = "SELECT mq.idmaquinaria as idmaquinaria, mq.nombre as nombre, mq.codigo_maquina as codigo_maquina, p.razon_social as nombre_proveedor, mq.idproveedor as idproveedor
      FROM maquinaria as mq, proveedor as p WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.estado_delete='1' AND mq.tipo=$tipo";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ E M P R E S A   A   C A R G O ══════════════════════════════════════ */
    public function select2_empresa_a_cargo() {
      $sql3 = "SELECT idempresa_a_cargo as id, razon_social as nombre, tipo_documento, numero_documento, logo FROM empresa_a_cargo WHERE estado ='1' AND estado_delete ='1' AND idempresa_a_cargo > 1 ;";
      return ejecutarConsultaArray($sql3);
    }   

  }

?>