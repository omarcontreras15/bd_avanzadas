<?php

class UserModel{


    public function __construct() {
        
    }

      public function registrarProyecto($form){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $proyecto = array("cod_proyecto"=> $form['codigo'], "nom_proyecto" => $form['nombre'], "ciudad_proyecto"=>$form['ciudad']); 
        $coleccionProyecto->insert($proyecto); 
        $connection->close();
       return "PROYECTO REGISTRADO CORRECTAMENTE";
    }

      public function eliminarProyecto($id){
        $connection = new Mongo(); 
        $coleccionProyecto = $connection->admin->proyecto; 
        $coleccionProyecto->remove(array("_id" => new MongoId((string)$id)), array("justOne" => true));
        $connection->close();
        $this->eliminarTareasDeProyecto($id);


        return "SE HA ELIMINADO CORRECTAMENTE";
    }

    public function eliminarTareasDeProyecto($id){
        $connection = new Mongo(); 
        $coleccionTarea = $connection->admin->tarea; 
        $numTareas = $coleccionTarea->count(array("id_proyecto"=>$id));

        while($numTareas>=0){
            $coleccionTarea->remove(array("id_proyecto" => $id), array("justOne" => true));
            $numTareas--;
        }
        $connection->close();
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
        return $result['nom_proyecto'];
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
        $tarea = array("cod_tarea"=> $form['codigo'], "nom_tarea" => $form['nombre'], "id_proyecto"=>$form['proyecto']); 
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

    public function buscarProyecto($id){
        $connection = new Mongo();
        $coleccionProyecto = $connection->admin->proyecto;        
        $respuesta = $coleccionProyecto->findOne(array('_id' =>new MongoId((string)$id)));
        $connection->close();
        return $respuesta;
    }
   
    public function updateProyecto($form){
      
        $connection = new Mongo();
        $collectionProyecto = $connection->admin->proyecto; 
        $collectionProyecto->update(array('_id' => new MongoId((string)$form["id_"])), array('$set' => array("nom_proyecto" => $form["nombre"], "cod_proyecto"=> $form["codigo"], "ciudad_proyecto" => $form["ciudad"] )));
        $connection->close();
        return "EL PROYECTO ".$form["nombre"]." SE HA MODIFICADO CORRECTAMENTE";
    }

    public function buscarTarea($id){
        $connection = new Mongo();
        $coleccionTarea = $connection->admin->tarea;        
        $respuesta = $coleccionTarea->findOne(array('_id' =>new MongoId((string)$id)));
        $connection->close();
        return $respuesta;
    }

    public function updateTarea($form){
      
        $connection = new Mongo();
        $collectionTarea = $connection->admin->tarea; 
        $collectionTarea->update(array('_id' => new MongoId((string)$form["id_"])), array('$set' => array("nom_tarea" => $form["nom_tarea"], "cod_tarea"=> $form["cod_tarea"], "id_proyecto" => $form["id_proyecto"] )));
        $connection->close();
        return "LA TAREA ".$form["nom_tarea"]." SE HA MODIFICADO CORRECTAMENTE";
    }

}

?>