<?php 

class PiezaXPedido{

    private $cod_pieza;
    private $cod_pedido;
    private $cantidad;
    
    public function __construct($cod_pieza, $cod_pedido, $cantidad){
        $this->cod_pieza = $cod_pieza;
        $this->cod_pedido = $cod_pedido;
        $this->cantidad = $cantidad;
    }
    
    public function getCod_pieza(){
        return $this->cod_pedido;
    }
    
    public function setCod_pieza($cod_pieza){
        $this->cod_pieza = $cod_pieza; 
    }
    
    public function getCod_pedido(){
        return $this->$cod_pedido;
    }
    
    public function setCod_pedido($cod_pedido){
        $this->cod_pedido = $cod_pedido;
    }
    
    public function getCantidad(){
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }
    
}

?>