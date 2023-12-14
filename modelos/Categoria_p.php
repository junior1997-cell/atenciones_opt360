<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Categoria_p
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $descripcion)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO `categoria_producto`(`nombre`, `descripcion`, user_created) VALUES ('$nombre', '$descripcion','$this->id_usr_sesion')";
		$insertar =  ejecutarConsulta_retornarID($sql); if ($insertar['status'] == false) {  return $insertar; } 
		$id = $insertar['data'];
		//add registro en nuestra bitacora
		$sql_d = "$nombre, $descripcion";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5, 'categoria_producto','$id','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $insertar;
	}

	//Implementamos un método para editar registros
	public function editar($idcategoria_producto, $nombre, $descripcion)
	{
		$sql="UPDATE categoria_producto SET nombre='$nombre', descripcion= '$descripcion',user_updated= '$this->id_usr_sesion' WHERE idcategoria_producto='$idcategoria_producto'";
		$editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d = "$idcategoria_producto, $nombre, $descripcion";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'categoria_producto','$idcategoria_producto','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar categoria_producto
	public function desactivar($idcategoria_producto)
	{
		$sql="UPDATE categoria_producto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idcategoria_producto='$idcategoria_producto'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('categoria_producto','".$idcategoria_producto."','Categoría de insumos desactivado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar categoria_producto
	public function activar($idcategoria_producto)
	{
		$sql="UPDATE categoria_producto SET estado='1' WHERE idcategoria_producto='$idcategoria_producto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function delete($idcategoria_producto)
	{
		$sql="UPDATE categoria_producto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idcategoria_producto='$idcategoria_producto'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('trabajador','$idcategoria_producto','Categoría de insumos Eliminado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategoria_producto)
	{
		$sql="SELECT * FROM categoria_producto WHERE idcategoria_producto='$idcategoria_producto'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM categoria_producto WHERE  idcategoria_producto>1 AND estado=1 AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categoria_producto where idcategoria_producto>1 AND estado=1 AND estado_delete=1";
		return ejecutarConsulta($sql);		
	}

}
?>