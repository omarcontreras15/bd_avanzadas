<?php

require_once "./app/model/model.php";
include_once "./app/model/dto/entidadDTO.php";

class EntidadDAO extends Model{

    public function __construct(){
        
    }

    public function agregarSucursal($EntidadDTO){
        if($this->buscarEntidad($EntidadDTO->getCod_entidad())){
            return "ERROR AL REGISTRAR! LA ENTIDAD YA EXISTE";
        }
        $insert = "INSERT INTO `entidad` (`codigo`, `nombre`, `direccion`, `ciudad`, `cod_sucursal`) VALUES (
            '".$EntidadDTO->getCod_entidad()."','".$EntidadDTO->getNombre()."','".$EntidadDTO->getDireccion()."', 
            '".$EntidadDTO->getCiudad()."', '".$EntidadDTO->getCod_sucursal()."')";
        $this->connect();
        $this->query($insert);
        $this->terminate();
        return "ENTIDAD REGISTRADA EXITOSAMENTE";
    }
        
    
    public function eliminarSucursal($idSucursal){
        $consulta = "DELETE FROM entidad WHERE codigo =".$idSucursal."";
        $this->connect();
        $this->query($consulta);
        $this->terminate();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }

    public function buscarSucursal($id){
        $consulta = "SELECT * FROM entidad where (codigo=".$id.")";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();
        $row =mysqli_fetch_array($query);
        array_unshift($array, new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], 0));
        return $array;
    }
    
    public function listarSucursales(){
        $consulta = "SELECT * FROM entidad WHERE (cod_sucursal=0)";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();
        while($row = mysqli_fetch_array($query)){
            $sucursal = new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], 0);
            array_unshift($array,$sucursal);
        }

        return $array;
    }

    public function mostrarEntidades(){
        $consulta = "SELECT * FROM entidad";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();
        while($row = mysqli_fetch_array($query)){
            $sucursal = new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], 0);
            array_unshift($array,$sucursal);
        }
        return $array;
    }

    public function editarSucursal($EntidadDTO, $id){

        if($id!=$EntidadDTO->getCod_entidad()){
            if($this->buscarEntidad($EntidadDTO->getCod_entidad())){
                 return "ERROR AL EDITAR! EL NUEVO CODIGO DE ESA SUCURSAL YA ESTA ASOCIADO CON OTRA, INTENTELO DE NUEVO";
            }
        }
        $insert = "UPDATE entidad SET codigo='".$EntidadDTO->getCod_entidad()."', nombre='".$EntidadDTO->getNombre()."', direccion='".
        $EntidadDTO->getDireccion()."', ciudad='".$EntidadDTO->getCiudad()."', cod_sucursal='".$EntidadDTO->getCod_sucursal()."'
        WHERE codigo =".$id."";
        $this->connect();
        $this->query($insert);
        $this->terminate();
        return "LA SUCURSAL FUE EDITADA EXITOSAMENTE";
    }


    public function listarConcesionarios(){
        $consulta = "SELECT * FROM entidad WHERE cod_sucursal is not null";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();

        while($row = mysqli_fetch_array($query)){
            $concesionario = new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], 
                $row["cod_sucursal"]);
            array_unshift($array,$concesionario);
        }

        return $array;
    }
    
    public function listarSucursalesExcepto($cod_sucursal){
        $consulta = "SELECT * FROM entidad WHERE codigo <> $cod_sucursal is null";
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();

        while($row = mysqli_fetch_array($query)){
             $sucursal = new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], 0);
            array_unshift($array,$sucursal);
        }

        return $array;
    }


    public function buscarEntidad($codigo){
        $exito = false;
        $queryExist = "SELECT count(*) as conteo from entidad where (codigo =".$codigo.")";
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

    public function busquedaFiltrada($EntidadDTO){
        $consulta = $this->componerConsulta($EntidadDTO);
        $array = array();
        $this->connect();
        $query = $this->query($consulta);
        $this->terminate();

        while($row = mysqli_fetch_array($query)){
             $sucursal = new EntidadDTO($row["codigo"], $row["nombre"], $row["direccion"], $row["ciudad"], $row["cod_sucursal"]);
            array_unshift($array,$sucursal);
        }
        return $array;
    }
/*
    private  function componerConsulta($EntidadDTO){

        if ($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() == "") {
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() == ""){
            return "SELECT * FROM entidad WHERE nombre like '%$EntidadDTO->getNombre()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() == ""){
            return "SELECT * FROM entidad WHERE direccion like '%EntidadDTO->getDireccion()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE ciudad like '%$EntidadDTO->getCiudad()%'";
        }

        if($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() == ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and nombre like '%$EntidadDTO->getNombre()%'";
        }

        if($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() == ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and direccion like '%$EntidadDTO->getDireccion()%'";
        }

        if($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() == ""){
            return "SELECT * FROM entidad WHERE nombre like '%$EntidadDTO->getNombre()%' and direccion like '%EntidadDTO->getDireccion()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE nombre like '%$EntidadDTO->getNombre()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE direccion like '%$EntidadDTO->getDireccion()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if ($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() == "") {
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and nombre like '%$EntidadDTO->getNombre()%' and direccion like '%EntidadDTO->getDireccion()%'";
        }

        if($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() == "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and nombre like '%$EntidadDTO->getNombre()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() == "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and direccion like '%EntidadDTO->getDireccion()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if($EntidadDTO->getCod_entidad() == "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() != ""){
            return "SELECT * FROM entidad WHERE nombre like '%$EntidadDTO->getNombre()%' and direccion like '%EntidadDTO->getDireccion()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }

        if ($EntidadDTO->getCod_entidad() != "" && $EntidadDTO->getNombre() != "" && $EntidadDTO->getDireccion() != "" && $EntidadDTO->getCiudad() != "" && $EntidadDTO->getCod_sucursal() != ""){
            return "SELECT * FROM entidad WHERE codigo like '%$EntidadDTO->getCod_entidad()%' and nombre like '%$EntidadDTO->getNombre()%' and direccion like '%EntidadDTO->getDireccion()%' and ciudad like '%EntidadDTO->getCiudad()%'";
        }
    }
*/

     private  function componerConsulta($EntidadDTO){
         $consulta = "SELECT * FROM entidad WHERE";
        if($EntidadDTO->getCod_entidad() != ""){
            $consulta .= "codigo like '%$EntidadDTO->getCod_entidad()%' ";
            if($EntidadDTO->getNombre() != ""){
                $consulta .= "and nombre l ike '%$EntidadDTO->getNombre()%' ";
            }
            if($EntidadDTO->getDireccion() != ""){
                $consulta .= "and direccion like '%$EntidadDTO->getDireccion()%' ";
            }
            if($EntidadDTO->getCiudad() != ""){
                $consulta .= "and ciudad like '%$EntidadDTO->getCiudad()%' ";
            }
        }else{
            if($EntidadDTO->getNombre() != ""){
                $consulta .= " nombre like '%$EntidadDTO->getNombre()%' ";
                if($EntidadDTO->getDireccion() != ""){
                    $consulta .= "and direccion like '%$EntidadDTO->getDireccion()%' ";
                }
                if($EntidadDTO->getCiudad() != ""){
                    $consulta .= "and ciudad like '%$EntidadDTO->getCiudad()%' ";
                }
            }else{
                if($EntidadDTO->getDireccion() != ""){
                    $consulta .= "direccion like '%$EntidadDTO->getDireccion()%' ";
                    if($EntidadDTO->getCiudad() != ""){
                        $paconsultart2 .= "and ciudad like '%$EntidadDTO->getCiudad()%' ";
                    }
                }else{
                    if($EntidadDTO->getCiudad() != ""){
                    $consulta .= "ciudad like '%$EntidadDTO->getCiudad()%' ";
                    }
                }
            }
        }
        return $consulta;
     }

}

?>