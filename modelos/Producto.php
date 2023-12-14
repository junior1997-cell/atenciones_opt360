<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Producto
{
  //Implementamos nuestro constructor
  public $id_usr_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }

  //Implementamos un método para insertar registros
  public function insertar( $idcategoria_producto, $idunidad_medida, $nombre, $idmarca, $idcolor, $contenido_neto, $descripcion, $imagen) {
    $sql = "SELECT p.nombre, p.contenido_neto, p.estado, p.descripcion,
    p.imagen, p.estado, p.estado_delete, um.nombre as nombre_medida, cp.nombre as nombre_categoria, m.nombre_marca, c.nombre_color
		FROM producto p, unidad_medida as um, categoria_producto as cp, marca as m, color as c
    WHERE um.idunidad_medida=p.idunidad_medida AND cp.idcategoria_producto=p.idcategoria_producto AND  p.idmarca = m.idmarca AND p.idcolor = c.idcolor AND
    p.idcategoria_producto = '$idcategoria_producto' AND p.idunidad_medida = '$idunidad_medida' AND p.idmarca = '$idmarca' AND p.idcolor = '$idcolor' AND
    p.nombre='$nombre'";
    $buscando = ejecutarConsultaArray($sql);  if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto (idcategoria_producto, idunidad_medida, idmarca, idcolor, nombre, contenido_neto, descripcion, imagen, user_created) 
      VALUES ( '$idcategoria_producto', '$idunidad_medida', '$idmarca', '$idcolor', '$nombre', '$contenido_neto', '$descripcion', '$imagen','$this->id_usr_sesion')";     
      $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_d = "$idcategoria_producto, $idunidad_medida, $nombre, $idmarca, $idcolor, $contenido_neto, $descripcion, $imagen";
      $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5, 'producto','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Clasificaciòn: </b>'.$value['nombre_categoria'].'<br>
          <b>UM: </b>'.$value['unidad_medida'].'<br>
          <b>Marca: </b>'.$value['nombre_marca'].'<br>
          <b>Color: </b>'.$value['color_nombre'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }      
    
  }

  //Implementamos un método para editar registros
  public function editar($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $idmarca, $idcolor, $contenido_neto, $descripcion, $img_pefil) {
    // var_dump($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET idcategoria_producto = '$idcategoria_producto',	idunidad_medida = '$unidad_medida',	nombre = '$nombre',
		idmarca = '$idmarca', idcolor = '$idcolor',	contenido_neto = '$contenido_neto',	descripcion = '$descripcion',	imagen = '$img_pefil', user_updated= '$this->id_usr_sesion'
		WHERE idproducto='$idproducto'";
    $editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_d = "$idproducto, $idcategoria_producto, $unidad_medida, $nombre, $idmarca, $idcolor, $contenido_neto, $descripcion, $img_pefil";
    $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'producto', '$idproducto', '$sql_d', '$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto) {
    $sql = "UPDATE producto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idproducto ='$idproducto'";
    $desactivar= ejecutarConsulta($sql);  if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'producto','$idproducto','$idproducto','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto) {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto) {
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idproducto ='$idproducto'";
    $eliminar =  ejecutarConsulta($sql);  if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'producto','$idproducto','$idproducto','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto) {
    $data = Array();

    $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.idcolor, p.idmarca, p.nombre,  p.contenido_neto, 
    p.precio_venta, p.precio_compra, p.stock, p.descripcion, p.imagen, p.estado, p.created_at,
    um.nombre as nombre_medida, cp.nombre AS categoria, c.nombre_color, c.hexadecimal, m.nombre_marca
		FROM producto AS p, unidad_medida AS um, categoria_producto AS cp, color as c,  marca as m
    WHERE p.idunidad_medida = um.idunidad_medida AND p.idcategoria_producto = cp.idcategoria_producto AND p.idcolor = c.idcolor 
    and p.idmarca = m.idmarca AND p.idproducto = '$idproducto'";
    $producto = ejecutarConsultaSimpleFila($sql); if ($producto['status'] == false) {  return $producto; }

    $data = array(
      'idproducto'      => $producto['data']['idproducto'],
      'idcategoria_producto' => $producto['data']['idcategoria_producto'],
      'idunidad_medida' => $producto['data']['idunidad_medida'],
      'idmarca'         => $producto['data']['idmarca'],
      'idcolor'         => $producto['data']['idcolor'],
      'nombre_medida'   => $producto['data']['nombre_medida'],
      'categoria'       => $producto['data']['categoria'],           
      'nombre'          => decodeCadenaHtml($producto['data']['nombre']),
      'marca'           => decodeCadenaHtml($producto['data']['nombre_marca']),
      'color'           => decodeCadenaHtml($producto['data']['nombre_color']),
      'hexadecimal'     => $producto['data']['hexadecimal'],    
      'contenido_neto'  => decodeCadenaHtml($producto['data']['contenido_neto']),
      'precio_venta'    => (empty($producto['data']['precio_venta']) ? 0 : $producto['data']['precio_venta']),
      'precio_compra'   => (empty($producto['data']['precio_compra']) ? 0 : $producto['data']['precio_compra']),
      'stock'           => $producto['data']['stock'],
      'descripcion'     => decodeCadenaHtml($producto['data']['descripcion']),
      'imagen'          => $producto['data']['imagen'],
      'estado'          => $producto['data']['estado'],
      'fecha'           => $producto['data']['created_at'],
      //'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];    
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idcategoria) {

    $tipo_categoria = '';

    if ($idcategoria == 'todos') { $tipo_categoria = ""; } else{ $tipo_categoria = "AND p.idcategoria_producto = '$idcategoria'"; }

    $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.nombre, p.contenido_neto, p.precio_venta, p.precio_compra,  p.stock, 
    p.descripcion, p.imagen, p.estado,  
    um.nombre as nombre_medida, cp.nombre AS categoria, c.nombre_color, m.nombre_marca
    FROM producto as p, unidad_medida AS um, categoria_producto AS cp, color as c, marca as m
    WHERE p.idcategoria_producto = cp.idcategoria_producto and p.idunidad_medida = um.idunidad_medida AND p.idcolor = c.idcolor and p.idmarca = m.idmarca
    $tipo_categoria and p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //OBTENEMOS LA IMAGEN PARA REEMPLAZARLO
  public function obtenerImg($idproducto) {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════  C A T E G O R I A S   P R O D U C T O  ══════════════════════════════════════

  public function lista_de_categorias(  )  {
    $sql = "SELECT * FROM categoria_producto WHERE estado = '1' AND estado_delete ='1';";
    return ejecutarConsultaArray($sql);
  }

  
}

?>
