<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Sucursal
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct( $id_usr_sesion = 0 )
	{
    $this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $codigo, $direccion, $comprobante, $serie, $numero)
	{
		$sql="INSERT INTO sucursal (apodo_sucursal, codigo_sucursal, direccion_sucursal, user_created)VALUES ('$nombre','$codigo', '$direccion','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
		$id = $intertar['data'];

		//add registro en nuestra bitacora
		$sql_d = $nombre.', '.$codigo.', '.$direccion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'sucursal','$id','$sql_d', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 		

    $ii=0;    $new_detalle=[];
    while ($ii < count($comprobante)) {

      $sql_detalle="INSERT INTO sn_comprobante( idsucursal, nombre_comprobante, serie_comprobante, numero_comprobante) 
			VALUES ('$id','$comprobante[$ii]','$serie[$ii]','$numero[$ii]')";
      $new_detalle = ejecutarConsulta($sql_detalle); if ($new_detalle['status'] == false) {  return $new_detalle; }
      $ii=$ii+1;
    }
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idsucursal, $nombre, $codigo, $direccion, $comprobante, $serie, $numero)
	{
		$sql_1="UPDATE sucursal SET apodo_sucursal='$nombre', codigo_sucursal='$codigo', direccion_sucursal='$direccion', user_updated= '$this->id_usr_sesion' WHERE idsucursal='$idsucursal'";
		$editar =  ejecutarConsulta($sql_1); if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d =$idsucursal.', '.$nombre.', '.$codigo.', '.$direccion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'sucursal','$idsucursal', '$sql_d', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

		// eliminamos los datos antiguos
		$sql_2 = "DELETE FROM sn_comprobante WHERE idsucursal ='$idsucursal'";
		$delete = ejecutarConsulta($sql_2); if ( $delete['status'] == false) {return $delete; }  

		$ii=0;    $new_detalle=[];
    while ($ii < count($comprobante)) {

      $sql_detalle="INSERT INTO sn_comprobante( idsucursal, nombre_comprobante, serie_comprobante, numero_comprobante) 
			VALUES ('$idsucursal','$comprobante[$ii]','$serie[$ii]','$numero[$ii]')";
      $new_detalle = ejecutarConsulta($sql_detalle); if ($new_detalle['status'] == false) {  return $new_detalle; }
      $ii=$ii+1;
    }
	
		return $editar;
	}

	//Implementamos un método para desactivar lote
	public function desactivar($id)
	{
		$sql="UPDATE sucursal SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idsucursal='$id'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'sucursal','".$id."', '$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar sucursal
	public function activar($idsucursal)
	{
		$sql="UPDATE sucursal SET estado='1' WHERE idsucursal='$idsucursal'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar sucursal
	public function eliminar($id)
	{
		$sql="UPDATE sucursal SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idsucursal='$id'";
		$eliminar =  ejecutarConsulta($sql);	if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'sucursal','$id','$id', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idsucursal)
	{
		$sql="SELECT * FROM sucursal WHERE idsucursal='$idsucursal'";
		$sucursal = ejecutarConsultaSimpleFila($sql); if ( $sucursal['status'] == false) {return $sucursal; } 

		$sql="SELECT * FROM sn_comprobante WHERE idsucursal='$idsucursal' ORDER BY idsn_comprobante ASC";
		$sn_comprobante = ejecutarConsultaArray($sql); if ( $sn_comprobante['status'] == false) {return $sn_comprobante; }  

		return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>['sucursal' =>$sucursal['data'], 'sn_comprobante' =>$sn_comprobante['data']],  ] ;
	}

	//Implementar un método para listar los registros
	public function tbla_sucursal()
	{
		$sql="SELECT * FROM sucursal WHERE estado=1  AND estado_delete=1  ORDER BY apodo_sucursal ASC";
		return ejecutarConsulta($sql);			
	}

	
}
?>