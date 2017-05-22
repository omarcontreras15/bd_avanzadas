<?php

include_once "./app/model/model.php";
require_once "./app/model/dto/PiezaXPedidoDTO.php";
include_once "./app/model/util/classUtil.php";

class PiezaXPedidoDAO extends Model{

    private $arrayCantidades;
    
    public function agregarPiezaPedida($cod_pedido, $cod_pieza, $cantidad){

        $hayRegistro=$this->hayRegistro($cod_pedido, $cod_pieza);
        $existencias=0;
        if($hayRegistro){
        
        $this->connect();
    	$result= $this->query("SELECT cantidad FROM `piezaXpedido` WHERE cod_pedido='$cod_pedido' AND cod_pieza='$cod_pieza'");
    	$this->terminate();
        $row = mysqli_fetch_array($result);
        $existencias=$row['cantidad'];
            
        $consulta2 = "DELETE FROM `piezaXpedido` WHERE cod_pedido='$cod_pedido' AND cod_pieza='$cod_pieza'";
    	$this->connect();
    	$this->query($consulta2);
    	$this->terminate();

        }
        $cantidad=$cantidad+$existencias;
        $consulta = "INSERT INTO `piezaXpedido` (`cod_pieza`,`cod_pedido`,`cantidad`) values ('$cod_pieza','$cod_pedido', '$cantidad')";
    	$this->connect();
    	$this->query($consulta);
    	$this->terminate();
    	return true;
    }


    public function hayRegistro($cod_pedido, $cod_pieza){
        $this->connect();
        $query = $this->query("SELECT * FROM `piezaXpedido` WHERE cod_pedido='$cod_pedido' AND cod_pieza='$cod_pieza'");
        $this->terminate();
        while ($row = mysqli_fetch_array($query)) {
    		return true;
    	}
        return false;
    }

    public function listarPedidos(){
    	$consulta = "SELECT pe.cantidad, p.cod_pieza, p.nombre  FROM piezaXpedido pe inner join pieza p on pe.cod_pieza=p.cod_pieza where pe.cod_pedido=";
    	$this->connect();
    	$array = array();
    	$query = $this->query($consulta);
    	$this->terminate();

    	while ($row = mysqli_fetch_array($query)) {
    		$piezaXpedido = new PiezaXPedidoDTO($row['cod_pieza'], $row['cod_pedido'], $row['cantidad']);
    		array_unshift($array, $piezaXpedido);
    	}

    	return $array;
    }

    public function listarPiezasPedido($cod_pedido){
    	$consulta = "SELECT pe.cantidad, p.cod_pieza, p.nombre  FROM piezaXpedido pe inner join 
            pieza p on pe.cod_pieza=p.cod_pieza where pe.cod_pedido=".$cod_pedido."";

    }
    public function listarPiezasPedido1($cod_pedido){
    	$consulta = "SELECT pe.cantidad, p.cod_pieza, p.nombre  FROM piezaXpedido pe inner join pieza p on pe.cod_pieza=p.cod_pieza where pe.cod_pedido=".$cod_pedido."";

    	$this->connect();
    	$query = $this->query($consulta);
    	$this->terminate();
        return $query;
    }
    
    public function modificarPiezaPedido(){

    }

    public function eliminarPiezaPedido($cod_pedido, $cod_pieza){    	
        $sentencia="DELETE FROM piezaXpedido WHERE $cod_pedido='$cod_pedido' and cod_pieza='$cod_pieza'";
        $this->connect();
    	$this->query($sentencia);
    	$this->terminate();
    }
    
    public function buscarPiezaPedido($codPieza, $cod_pedido){
    	$exito = false;
        $queryExist = "SELECT count(*) as conteo from piezaXpedido where (cod_pedido = $cod_pedido and cod_pieza = $cod_pieza)";
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
    
}

?>