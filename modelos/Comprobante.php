<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Comprobante
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}
  
  //$idcomprobante,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante)
  {

    $sql = "INSERT INTO comprobante( idpersona, fecha_ingreso, tipo_comprobante, numero_comprobante, forma_de_pago, precio_sin_igv, precio_igv, precio_con_igv,val_igv, tipo_gravada, descripcion, comprobante, user_created) 
    VALUES ('$idpersona', '$fecha_i', '$tipo_comprobante', '$nro_comprobante', '$forma_pago', '$subtotal', '$igv', '$precio_parcial',$val_igv, '$tipo_gravada', '$descripcion', '$comprobante','$this->id_usr_sesion')";
    $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

		//add registro en nuestra bitacora
    $sql_d = $idpersona.', '.$fecha_i.', '.$forma_pago.', '.$tipo_comprobante.', '.$nro_comprobante.', '.$subtotal.', '.$igv.', '.$val_igv.', '.$tipo_gravada.', '.$precio_parcial.', '.$descripcion.', '.$comprobante;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'comprobante','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

		return $intertar;

  }

  //Implementamos un método para editar registros
  public function editar($idcomprobante,$idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion,$comprobante)
  {

    $sql = "UPDATE comprobante SET
    idpersona='$idpersona',
    fecha_ingreso='$fecha_i',
    tipo_comprobante='$tipo_comprobante',
    numero_comprobante='$nro_comprobante',
    forma_de_pago='$forma_pago',
    precio_sin_igv='$subtotal',
    precio_igv='$igv',
    precio_con_igv='$precio_parcial',
    val_igv='$val_igv',
    tipo_gravada='$tipo_gravada',
    descripcion='$descripcion',
    comprobante='$comprobante',
    user_updated = '$this->id_usr_sesion'
		WHERE idcomprobante='$idcomprobante'";
    $edit = ejecutarConsulta($sql);  if ( $edit['status'] == false) {return $edit; }

    //add registro en nuestra bitacora
    $sql_d = $idcomprobante.', '.$idpersona.', '.$fecha_i.', '.$forma_pago.', '.$tipo_comprobante.', '.$nro_comprobante.', '.$subtotal.', '.$igv.', '.$val_igv.', '.$tipo_gravada.', '.$precio_parcial.', '.$descripcion.', '.$comprobante;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'comprobante','$idcomprobante','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

		return $edit;
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
  public function tbla_principal($fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND c.fecha_ingreso BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND c.fecha_ingreso = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND c.fecha_ingreso = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND c.idpersona = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND c.tipo_comprobante = '$comprobante'"; } 

    $sql = "SELECT c.idcomprobante, c.idpersona, c.fecha_ingreso, c.tipo_comprobante, c.numero_comprobante, c.forma_de_pago, c.precio_sin_igv, 
    c.precio_igv, c.precio_con_igv, c.tipo_gravada, c.descripcion, c.comprobante, p.nombres,p.numero_documento,p.tipo_documento, p.direccion
    FROM comprobante as c, persona as p 
    WHERE c.estado=1 AND c.estado_delete=1 AND c.idpersona=p.idpersona $filtro_proveedor $filtro_comprobante $filtro_fecha 
    ORDER BY c.fecha_ingreso DESC";
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
