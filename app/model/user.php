<?php

class UserModel{


    public function __construct() {
        
    }


    function registrarProyecto($form){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $proyecto = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "ciudad"=>$form['ciudad']); 
        $coleccionProyecto->insert($proyecto); 
        $connection->close();
       return "PROYECTO REGISTRADO CORRECTAMENTE";
    }


    function eliminarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $coleccionProyecto->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }

    function listarProyectos(){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $respuesta = $coleccionProyecto->find();
        $connection->close();
        return $respuesta;
    }

    function buscarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $result = $coleccionProyecto->findOne(array('_id' => new MongoId($id)));
        $connection->close();
        return $result;
    }


/*    *** CRUD DE TAREA *********************/
    
    function registrarTarea($form){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $tarea = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "proyecto"=>$form['proyecto']); 
        $coleccionTarea->insert($tarea); 
        $connection->close();
       return "TAREA REGISTRADA CORRECTAMENTE";
    }

    function listarTareas(){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea;
        $respuesta = $coleccionTarea->find();
        $connection->close();
        return $respuesta;
    }

    function eliminarTarea($id){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $coleccionTarea->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }
   

}

?>