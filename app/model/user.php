<?php

class UserModel{


    public function __construct() {
        
    }


      public function registrarProyecto($form){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $proyecto = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "ciudad"=>$form['ciudad']); 
        $coleccionProyecto->insert($proyecto); 
        $connection->close();
       return "PROYECTO REGISTRADO CORRECTAMENTE";
    }


      public function eliminarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $coleccionProyecto->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }

     public function listarProyectos(){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $respuesta = $coleccionProyecto->find();
        $connection->close();
        return $respuesta;
    }

    public  function listarTareasProyecto($id){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        return $coleccionTarea->find(array( "id_proyecto"=>"$id"));
    }

    public function registrarEmpleado($empleado){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $coleccionEmpleado->insert($empleado);
    }

    public function buscarEmpleado($cc){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        return $coleccionEmpleado->find(array("cc"=>(integer)$cc));
    }

    public function registrarTarea($form){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $tarea = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "proyecto"=>$form['proyecto']); 
        $coleccionTarea->insert($tarea); 
        $connection->close();
       return "TAREA REGISTRADA CORRECTAMENTE";
    }


    public function eliminarTarea($id){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $coleccionTarea->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
        return "SE HA ELIMINADO CORRECTAMENTE";
    }


    public function listarTareas(){
        $connection = new Mongo();
        $coleccionTarea = $connection->admin->tarea;
        $respuesta = $coleccionTarea->find();
        $connection->close();
        return $respuesta;
    }
   

}

?>