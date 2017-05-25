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
   

    function buscarNomProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $result = $coleccionProyecto->findOne(array('_id' => new MongoId($id)));
        return $result['nombre'];
    }

    public  function listarTareasProyecto(){
        $connection = new Mongo();
        $coleccionTarea = $connection->admin->tarea;
        $connection->close();
        return $coleccionTarea->find();
    }

    public function registrarEmpleado($empleado){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $coleccionEmpleado->insert($empleado);
        $connection->close();
    }

    public function buscarEmpleadoCC($cc){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $empleado=$coleccionEmpleado->find(array("cc"=>(integer)$cc));
        $connection->close();
        return $empleado;
    }

    public function buscarEmpleadoID($id){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $empleado=$coleccionEmpleado->find(array("_id" => new MongoId((string)$id)));
        $connection->close();
        return $empleado;
    }

    public function listarEmpleados(){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $empleados=$coleccionEmpleado->find();
        $connection->close();
        return $empleados ;
    }

    public function eliminarEmpleado($id){
        $connection = new Mongo();
        $coleccionEmpleado = $connection->admin->empleado;
        $coleccionEmpleado->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
    }


    public function registrarTarea($form){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $tarea = array("codigo"=> $form['codigo'], "nom_tarea" => $form['nombre'], "id_proyecto"=>$form['proyecto']); 
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