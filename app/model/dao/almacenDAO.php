<?php

require_once "./app/model/model.php";
include_once "./app/model/dto/almacenDTO.php";

class AlmacenDAO extends Model{

    public function __construct(){
        
    }
    
    public function agregarAlAlmacen($sucursalDTO, $piezaDTO, $stock, $stock_min) {
        if($this->buscarAlmacen($sucursalDTO->getCod_entidad(), $piezaDTO->getCod_pieza())){
        	return "ERROR LA PIEZA YA ESTA ASIGNADA AL ALMACEN!";
        }
        $insert = "INSERT INTO `almacen` (`cod_sucursal`, `cod_pieza`, `stock`, `stock_min`) VALUES ('".$sucursalDTO->getCod_sucursal()."','".$piezaDTO->getCod_pieza()."','".$stock."', '".$stock_min."')";
        $this->connect();
        $this->query($insert);
        $this->terminate();
        return "PIEZA ASIGNADA AL ALMACEN!";
    }
    
    public function verAlmacen($cod_sucursal){
    	$consulta = "SELECT p.cod_pieza, p.nombre , a.stock FROM almacen a JOIN pieza p ON a.cod_pieza = p.cod_pieza WHERE a.cod_sucursal=".$cod_sucursal." AND a.stock>0";
        $this->connect();
    	$query = $this->query($consulta);
    	$this->terminate();
        return $query;
    }

    private function buscarAlmacen($sucusal,$pieza){
        $exito = false;
        $queryExist = "SELECT count(*) as conteo from almacen where cod_sucursal = $sucusal and cod_pieza = $pieza";
        $this->connect();
        $consulta= $this->query($queryExist);
        $extraido= mysqli_fetch_array($consulta);
        if( $extraido['conteo'] != 0){
            $this->terminate();
            return true;
        }

        $this->terminate();
        return $exito;
    }


    function hayDisponibles($cod_receptor, $cod_pieza, $cantidad){

        $consulta = "SELECT stock FROM almacen WHERE cod_sucursal=".$cod_receptor." AND cod_pieza=".$cod_pieza."";
        $this->connect();
        $query = mysqli_fetch_array($this->query($consulta));
        $this->terminate();
        $resta = $query['stock']-$cantidad;
        if($resta>=0){
            return true;
        }
        return false;
    }

    private function validarExistenciasPieza($cod_sucursal, $cod_pieza, $cantidad){
        $consulta = "SELECT * FROM piezaXpedido WHERE cod_sucursal = $cod_sucursal and cod_pieza = $cod_pieza";
        $this->connect();
        $query = mysqli_fetch_array($this->query($consulta));
        $this->terminate();
    
        if($query['stock'] > $query['stock_min'] && $query['stock'] > $cantidad){
            return new AlmacenDTO($query['cod_sucursal'], $query['cod_pieza'], $query['stock'], $query['stock_min']);
        }

        return null;
    }

    public function enviarPiezas($cod_sucursal, $cod_pieza, $cantidad){
        $almacen = $this->validarExistenciasPieza($cod_sucursal, $cod_pieza, $cantidad);
        if($almacen == null){
            return false;
        }
        $stock = $almacen->getStock()-$cantidad;
        $consulta  = "UPDATE almacen SET stock ='".$stock."' WHERE cod_sucursal = ".$almacen->getCod_sucursal().
            " and cod_pieza = ".$almacen->getCod_pieza()."";
        return true;
    }
    
}

?>