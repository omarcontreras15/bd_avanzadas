<?php

class PiezaDTO{
    
    private $cod_pieza;
    private $nombre;
    
    public function __construct($cod_pieza, $nombre){
        $this->cod_pieza = $cod_pieza;
        $this->nombre = $nombre;
    }
    
    public function getCod_pieza(){
        return $this->cod_pieza;
    }
    
    public function setCod_pieza($cod_pieza){
        $this->cod_pieza = $cod_pieza;
    }
    
    public function getNombre(){
        return $this->nombre;
    }
    
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    
    
}

?>