<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Tipo_negocio
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_tipo, $descripcion)
	{
		$sql="INSERT INTO tipo_negocio (nombre, descripcion, user_created)VALUES ('$nombre_tipo', '$descripcion','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
    $sql_d = "$nombre_tipo, $descripcion";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'tipo_negocio','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idtipo_negocio, $nombre_tipo, $descripcion)
	{
		$sql="UPDATE tipo_negocio SET nombre='$nombre_tipo', descripcion= '$descripcion', user_updated= '$this->id_usr_sesion' WHERE idtipo_negocio='$idtipo_negocio'";
		$editar =  ejecutarConsulta($sql);	if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
    $sql_d = "$idtipo_negocio, $nombre_tipo, $descripcion";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'tipo_negocio', '$idtipo_negocio', '$sql_d', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar tipo
	public function desactivar($idtipo_negocio)	{
		$sql="UPDATE tipo_negocio SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idtipo_negocio='$idtipo_negocio'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'tipo_negocio','$idtipo_negocio','$idtipo_negocio','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar tipo
	public function activar($idtipo_negocio)	{
		$sql="UPDATE tipo_negocio SET estado='1' WHERE idtipo_negocio='$idtipo_negocio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar tipo
	public function eliminar($idtipo_negocio)
	{
		$sql="UPDATE tipo_negocio SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idtipo_negocio='$idtipo_negocio'";
		$eliminar =  ejecutarConsulta($sql); if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'tipo_negocio','$idtipo_negocio','$idtipo_negocio','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtipo_negocio)
	{
		$sql="SELECT * FROM tipo_negocio WHERE idtipo_negocio='$idtipo_negocio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar_tipo()
	{
		$sql="SELECT * FROM tipo_negocio WHERE estado=1 AND estado_delete=1 ORDER BY idtipo_negocio ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM tipo_negocio where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>