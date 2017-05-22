<?php

include_once "./app/model/model.php";
require_once "./app/model/dto/piezaDTO.php";

class PiezaDAO extends Model{
    
    public function agregarPieza($piezaDTO){

    	if($this->buscarPieza($piezaDTO->getCod_pieza())){
            return "ERROR AL REGISTRAR! LA PIEZA YA EXISTE";
        }

        $insert = "INSERT INTO `pieza` (`cod_pieza`, `nombre`) VALUES ('".$piezaDTO->getCod_pieza()."','".$piezaDTO->getNombre()."')";
        $this->connect();
        $this->query($insert);
        $this->terminate();
        return "PIEZA REGISTRADA EXITOSAMENTE!";
    }
    
    public function listarPiezas(){
        $consulta = "SELECT * FROM pieza order by cod_pieza desc";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();
        while($row = mysqli_fetch_array($query)){
            $pieza = new PiezaDTO($row["cod_pieza"], $row["nombre"]);
            array_unshift($array,$pieza);
        }

        return $array;
    }
    
    public function eliminarPieza($codigo){
        $consulta = "DELETE FROM pieza WHERE cod_pieza = ".$codigo."";
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }
    
    public function editarPieza($piezaDTO, $cod_pieza){
        if($cod_pieza != $piezaDTO->getCod_pieza()){
            if($this->buscarPieza($piezaDTO->getCod_pieza())){
                 return "ERROR AL EDITAR! EL NUEVO CODIGO DE ESA PIEZA YA ESTA ASOCIADO CON OTRA, INTENTELO DE NUEVO";
            }
        }
        $insert = "UPDATE pieza SET cod_pieza = '".$piezaDTO->getCod_pieza()."', nombre ='".$piezaDTO->getNombre()."'
        WHERE cod_pieza =".$cod_pieza."";
        $this->connect();
        $this->query($insert);
        $this->terminate();
        return "LA PIEZA FUE EDITADA EXITOSAMENTE";
    }

    public function busquedaFiltrada($piezaDTO){
    	$consulta = $this->componerConsulta($piezaDTO);
    	$this->connect();
    	$array = array();
    	$query = $this->query($consulta);
    	$this->terminate();

    	while($row = mysqli_fetch_array($query)){
            $pieza = new PiezaDTO($row["cod_pieza"], $row["nombre"]);
            array_unshift($array,$pieza);
        }

        return $array;
    }

    private function componerConsulta($piezaDTO){
        $consulta = "SELECT * FROM pieza";
        if($piezaDTO->getCod_pieza() != ""){
            $consulta .= "cod_pieza like '%$piezaDTO->getCod_pieza()%' ";
            if($piezaDTO->getNombre() != ""){
                $consulta .= "and nombre like '%$piezaDTO->getNombre()%' ";
            }
        }else{
            $consulta .= "nombre like '%$piezaDTO->getNombre()%' ";
        }
    }

/*
    private function componerConsulta($piezaDTO){

    	if($piezaDTO->getCod_pieza() != "" && $piezaDTO->getNombre() == ""){
    		return "SELECT * FROM pieza WHERE cod_pieza like '%$piezaDTO.getCod_pieza()%'";
    	}

    	if($piezaDTO->getCod_pieza() == "" && $piezaDTO->getNombre() != ""){
    		return "SELECT * FROM pieza WHERE nombre like '%$piezaDTO.getNombre()%'";
    	}

    	if($piezaDTO->getCod_pieza() != "" && $piezaDTO->getNombre() != ""){
    		return "SELECT * FROM pieza WHERE cod_pieza like '%$piezaDTO.getCod_pieza()%' and nombre like '%$piezaDTO->getNombre()%'";
    	}
    }
*/

    public function buscarPieza($codigo){
       $exito = false;
        $queryExist = "SELECT count(*) as conteo from pieza where (cod_pieza = ".$codigo.")";
        $this->connect();
        $consulta = $this->query($queryExist);
        $extraido = mysqli_fetch_array($consulta);
        if( $extraido['conteo'] != 0){
            $this->terminate();
            return true;
        }

        $this->terminate();
        return $exito;

    }

    public function obtenerPieza($codigo){
        $consulta = "SELECT * FROM pieza WHERE (cod_pieza=".$codigo.")";
         $array = array();
        $this->connect();
         $query = $this->query($consulta);
        $this->terminate();
        $row =mysqli_fetch_array($query);
        return new PiezaDTO($row["cod_pieza"], $row["nombre"]);
        
    }
    
}

?>