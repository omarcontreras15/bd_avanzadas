
<?php

class Router {

    public $user;

    public function __construct() {
        $this->user = new User();
    }

    

    public function router() {
        if(isset($_GET["mode"])) {
            switch ($_GET["mode"]) {
                case "agregarEmpleado":
                    $this->user->formAgregarEmpleado();
                    break;
                
                case "consultarEmpleado":
                    $this->user->consultarEmpleados();
                    break;
                case "registrar-empleado":
                    $this->user->registrarEmpleado($_GET);
                    break;

                case "editar-empleado":
                    $this->user->formEditarEmpleado($_GET["id"]);
                    break;
                case "actualizar-empleado":
                    $this->user->actualizarEmpleado($_GET);
                    break;

                case "eliminar-empleado":
                    $this->user->eliminarEmpleado($_GET["id"]);
                    break;
                    
                case "agregarProyecto":
                    $this->user->agregarProyecto();
                    break;

                case "consultarProyecto":
                    $this->user->consultarProyecto();
                    break;

                case "realizarTarea":
                    $this->user->realizarTarea();
                    break;
                
                case "consultarTarea":
                    $this->user->consultarTarea();
                    break;

                case "eliminarProyecto":
                    $this->user->eliminarProyecto($_GET);
                    break;

                case "editarProyecto":
                    $this->user->editarProyecto($_GET['id']);
                    break;

                case "eliminarTarea":
                    $this->user->eliminarTarea($_GET);
                    break;

                    default:
                    header("Location:index.php");
                    break;
            }

        } else if(isset($_POST["mode"])) {
            switch ($_POST["mode"]) {
                case "agregarProyectoFormulario":
                    $this->user->agregarFormProyecto($_POST);         
                    break;
                case "cargar-tareas":
                  $this->user->cargarTareas();         
                    break;

                case "realizarTareaFormulario":
                    $this->user->agregarFormTarea($_POST);         
                    break;


                default:
                    header("Location:index.php");
                    break;
            }  
        } else {
             $this->user->index();  
        }
    }


}

?>