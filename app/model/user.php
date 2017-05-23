<?php

class UserModel{


    public function __construct() {
        
    }


      public function registrarProyecto($form){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $proyecto = array("codigo"=> $form['codigo'], "nombre" => $form['nombre'], "ciudad"=>$form['ciudad']); 
        $coleccionProyecto->insert($proyecto); 

       return "PROYECTO REGISTRADO CORRECTAMENTE";
    }


      public function eliminarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $coleccionProyecto->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        return "SE HA ELIMINADO CORRECTAMENTE";
    }

     public function listarProyectos(){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        return $coleccionProyecto->find();
    }

    public  function listarTareas($id){
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

    

   

}

?>