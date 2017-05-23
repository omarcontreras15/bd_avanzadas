<?php

class UserModel{


    public function __construct() {
        
    }


    function registrarProyecto($form){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $proyecto = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "ciudad"=>$form['ciudad']); 
        $coleccionProyecto->insert($proyecto); 

       return "PROYECTO REGISTRADO CORRECTAMENTE";
    }


    function eliminarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $coleccionProyecto->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        return "SE HA ELIMINADO CORRECTAMENTE";
    }

    function listarProyectos(){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        return $coleccionProyecto->find();
    }

    

   

}

?>