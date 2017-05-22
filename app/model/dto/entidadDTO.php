<?php

class EntidadDTO{
    
    private $cod_entidad;
    private $nombre;
    private $direccion;
    private $ciudad;
    private $cod_sucursal;
    
    
    public function __construct($cod_entidad, $nombre, $direccion, $ciudad, $cod_sucursal){
        $this->cod_entidad = $cod_entidad;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->cod_sucursal = $cod_sucursal;
    }
    
       public function getCod_entidad(){
        return $this->cod_entidad;
    }
    
    public function setCod_entidad($cod_entidad){
        $this->cod_entidad = $cod_entidad;
    }
    
    public function getNombre(){
        return $this->nombre;
    }
    
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    
    public function getDireccion(){
        return $this->direccion;
    }
    
    public function setDireccion($direccion){
        $this->direccion=$direccion;
    }
    
    public function getCiudad(){
        return $this->ciudad;
    }
    
    public function setCiudad($ciudad){
        $this->ciudad = $ciudad;
    }
    
    public function getCod_sucursal(){
        return $this->cod_sucursal;
    }
    
    public function setCod_sucursal($cod_sucursal){
        $this->cod_sucursal = $cod_sucursal;
    }
    
}

?>