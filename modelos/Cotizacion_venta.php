<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Cotizacion_Venta
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}
  
  //metodo insertar registro
  public function insertar($idproveedor, $fecha_cotizacion, $tipo_comprobante, $serie_comprobante, $numero_comprobante, $precio_con_igv, $impuesto, $impuesto_decimal,
  $forma_pago, $tiempo_produccion, $validez_cotizacion, $nota, $subtotal_cotizacion, $descuento_cotizacion, $igv_cotizacion, $total_cotizacion, 
  $idproducto, $ficha_tecnica, $nombre_producto, $nombre_marca, $nombre_color, $nombre_categoria, 
  $unidad_medida, $cantidad, $precio_sin_igv, $precio_igv, $precio_venta, $descuento, $subtotal_producto){

    $sql="INSERT INTO cotizacion( idpersona, fecha, tipo_comprobante, serie_comprobante, numero_comprobante, precio_con_igv, impuesto, impuesto_decimal, forma_pago, 
    tiempo_produccion, validez_cotizacion, nota, descuento_cotizacion, subtotal_cotizacion, igv_cotizacion, total_cotizacion) 
    VALUES ('$idproveedor','$fecha_cotizacion','$tipo_comprobante','$serie_comprobante','$numero_comprobante','$precio_con_igv','$impuesto','$impuesto_decimal','$forma_pago',
    '$tiempo_produccion','$validez_cotizacion','$nota', '$descuento_cotizacion','$subtotal_cotizacion','$igv_cotizacion','$total_cotizacion')";
    $coti_new = ejecutarConsulta_retornarID($sql);  if ($coti_new['status'] == false) {  return $coti_new; }
    $id = $coti_new['data'];

    $ii=0;    $new_detalle=[];
    while ($ii < count($idproducto)) {

      $sql_detalle="INSERT INTO detalle_cotizacion( idcotizacion, idproducto, producto, ficha_tecnica, marca, color, categoria, unidad_medida, cantidad, 
      precio_sin_igv, precio_igv,  precio_venta, descuento, subtotal_producto) 
      VALUES ('$id','$idproducto[$ii]','$nombre_producto[$ii]','$ficha_tecnica[$ii]','$nombre_marca[$ii]','$nombre_color[$ii]','$nombre_categoria[$ii]','$unidad_medida[$ii]',
      '$cantidad[$ii]','$precio_sin_igv[$ii]','$precio_igv[$ii]','$precio_venta[$ii]','$descuento[$ii]', '$subtotal_producto[$ii]')";
      $new_detalle = ejecutarConsulta($sql_detalle); if ($new_detalle['status'] == false) {  return $new_detalle; }
      $ii=$ii+1;
    }
    return $new_detalle;
  }

  public function editar($idproveedor, $fecha_cotizacion, $tipo_comprobante, $serie_comprobante, $numero_comprobante, $precio_con_igv, $impuesto, $impuesto_decimal,
  $forma_pago, $tiempo_produccion, $validez_cotizacion, $nota, $subtotal_cotizacion, $descuento_cotizacion, $igv_cotizacion, $total_cotizacion, 
  $idproducto, $ficha_tecnica, $nombre_producto, $nombre_marca, $nombre_color, $nombre_categoria, 
  $unidad_medida, $cantidad, $precio_sin_igv, $precio_igv, $precio_venta, $descuento, $subtotal_producto){

    $sql = "UPDATE cotizacion SET idsucursal='$idsucursal',idcliente='$idcliente', idpersonal='$idpersonal', tipo_comprobante='$tipo_comprobante', serie_comprobante='$serie_comprobante', num_comprobante='$num_comprobante',fecha_hora='$fecha_hora',total_venta='$total_venta',titulo='$titulo',nota='$nota',igv='$igv',formapago='$formapago',tiempo_pro='$tiempoproduccion' WHERE idcotizacion='$idcotizacion'";
    $edit_coti = ejecutarConsulta($sql); if ($edit_coti['status'] == false) {  return $edit_coti; }

    $sql2 = "DELETE FROM detalle_cotizacion WHERE idcotizacion='$idcotizacion'";
    $delete_coti = ejecutarConsulta($sql2); if ($delete_coti['status'] == false) {  return $delete_coti; }

    $ii=0;
    $new_detalle=[];

    while ($ii < count($idproducto)) {
      $sql_detalle="INSERT INTO detalle_cotizacion (idcotizacion,idproducto,cantidad,precio_venta,descuento) VALUES('$idcotizacion','$idproducto[$ii]','$cantidad[$ii]','$precio_venta[$ii]','$descuento[$ii]')";
      $new_detalle = ejecutarConsulta($sql_detalle); if ($new_detalle['status'] == false) {  return $new_detalle; }
      $ii=$ii+1;
    }
    return $new_detalle;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($id) {
    $sql = "UPDATE comprobante SET estado='0' WHERE idcomprobante ='$id'";
    $desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2,'comprobante','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($id) {
    $sql = "UPDATE comprobante SET estado_delete='0' WHERE idcomprobante ='$id'";
    $eliminar =  ejecutarConsulta($sql);	if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'comprobante','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcomprobante) {
    $sql = "SELECT c.idcomprobante, c.idpersona, c.fecha_ingreso, c.tipo_comprobante, c.numero_comprobante, c.forma_de_pago, c.precio_sin_igv, 
    c.precio_igv, c.precio_con_igv,c.val_igv, c.tipo_gravada, c.descripcion, c.comprobante, p.nombres,p.numero_documento,p.tipo_documento, p.direccion
    FROM comprobante as c, persona as p 
    WHERE c.idpersona=p.idpersona AND c.idcomprobante ='$idcomprobante'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT c.idcomprobante, c.idpersona, c.fecha_ingreso, c.tipo_comprobante, c.numero_comprobante, c.forma_de_pago, c.precio_sin_igv, 
    c.precio_igv, c.precio_con_igv, c.tipo_gravada, c.descripcion, c.comprobante, p.nombres,p.numero_documento,p.tipo_documento, p.direccion
    FROM comprobante as c, persona as p 
    WHERE c.estado=1 AND c.estado_delete=1 AND c.idpersona=p.idpersona";
    return ejecutarConsulta($sql);

  }

  //total
  public function total() {
    $sql = "SELECT SUM(precio_con_igv) as precio_parcial FROM comprobante WHERE estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Seleccionar un comprobante
  public function ficha_tec($idcomprobante) {
    $sql = "SELECT comprobante FROM comprobante WHERE idcomprobante='$idcomprobante'";
    return ejecutarConsulta($sql);
  }

  public function selecct_produc_o_provee()
  {
    $sql = "SELECT p.idpersona, p.idtipo_persona, p.nombres, p.numero_documento, tp.nombre as tipo FROM persona as p, tipo_persona as tp 
    WHERE p.idtipo_persona = tp.idtipo_persona AND p.idtipo_persona BETWEEN '2' and '3'  AND p.estado_delete =1 AND p.estado=1;";
    return ejecutarConsultaArray($sql);
  }

  public function select_tipo_persona()
  {
    $sql="SELECT * FROM tipo_persona WHERE idtipo_persona BETWEEN 2 AND 3;";
    return ejecutarConsulta($sql);
  }

}

?>
