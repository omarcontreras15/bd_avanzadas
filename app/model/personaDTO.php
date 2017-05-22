<?php 

class personaDTO{
    
    public $nombres;
    public $apellidos;
    public $cc;
    public $posicion;
    public $telefono;
    public $celular;
    
    
    public function __construct($nombres,$apellidos, $cc,$posicion,$telefono,$celular){
        
        $this->nombres=$nombres;
        $this->apellidos=$apellidos;
        $this->cc=$cc;
        $this->posicion=$posicion;
        $this->telefono=$telefono;
        $this->celular=$celular;
    }
}

?>