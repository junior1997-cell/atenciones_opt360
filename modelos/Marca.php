<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Marca
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}
  
  //metodo insertar registro
  public function insertar($nombre_marca, $descripcion_marca){

    $sql="INSERT INTO marca( nombre_marca, descripcion, user_created) VALUES ('$nombre_marca','$descripcion_marca', '$this->id_usr_sesion')";
    $marca_new = ejecutarConsulta_retornarID($sql);  if ($marca_new['status'] == false) {  return $marca_new; }
    $id = $marca_new['data'];
    //add registro en nuestra bitacora
    $sql_d = "$nombre_marca, $descripcion_marca";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'marca','$id','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    return $marca_new;
  }

  public function editar($idmarca, $nombre_marca, $descripcion_marca){

    $sql = "UPDATE marca SET nombre_marca='$nombre_marca', descripcion='$descripcion_marca', user_updated= '$this->id_usr_sesion' WHERE idmarca='$idmarca';";
    $edit_marca = ejecutarConsulta($sql); if ($edit_marca['status'] == false) {  return $edit_marca; }

    //add registro en nuestra bitacora
		$sql_d = "$idmarca, $nombre_marca, $descripcion_marca";
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'marca','$idmarca','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }    

    return $edit_marca;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($id) {
    $sql = "UPDATE marca SET estado='0' WHERE idmarca ='$id'";
    $desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2,'marca','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($id) {
    $sql = "UPDATE marca SET estado_delete='0' WHERE idmarca ='$id'";
    $eliminar =  ejecutarConsulta($sql);	if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'marca','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idmarca) {
    $sql = "SELECT * FROM marca  WHERE  idmarca ='$idmarca'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT * FROM marca WHERE idmarca > 1 AND estado= '1' AND estado_delete= '1' ";
    return ejecutarConsulta($sql);

  }


}

?>
