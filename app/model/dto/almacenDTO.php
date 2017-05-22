<?php

class AlmacenDTO{
    
    private $cod_sucursal;
    private $cod_pieza;
    private $stock;
    private $stock_min;
    
    public function __construct($cod_sucursal, $cod_pieza, $cod_pieza, $stock_min){
        $this->cod_sucursal = $cod_sucursal;
        $this->cod_pieza = $cod_pieza;
        $this->stock = $stock;
        $this->stock_min = $stock_min;
    }
    
    public function getCod_sucursal(){
        return $this->cod_sucursal;
    }
    
    public function setCod_sucursal($cod_sucursal){
        $this->cod_sucursal = $cod_sucursal;
    }
    
    public function getCod_pieza(){
        return $this->cod_pieza;
    }
    
    public function setCod_pieza($cod_pieza){
        $this->cod_pieza = $cod_pieza;
    }
    
    public function getStock(){
        return $this->$stock;
    }
    
    public function setStock($stock){
        $this->$stock = $stock;
    }
    
    public function getStock_min(){
        return $this->$stock_min;
    }
    
    public function setStock_min($stock_min){
        $this->$stock_min = $stock_min;
    }
    
}

?>