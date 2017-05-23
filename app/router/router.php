
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
                    $this->user->consultarEmpleado();
                    break;
                case "registrar-empleado":
                    $this->user->registrarEmpleado($_GET);
                    break;

                case "editarEmpleado":
                    $this->user->editarEmpleado($_GET["id"]);
                    break;

                case "eliminarEmpleado":
                    $this->user->eliminarEmpleado($_GET);
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

                case "eliminarPiezaPedido":
                    $this->user->eliminarPiezaPedido($_GET);
                    break;

                case "finalizarPedido":
                    $this->user->finalizarPedido();
                    break;

                case "eliminarProyecto":
                    $this->user->eliminarProyecto($_GET);
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
                  $this->user->cargarTareas($_POST["id"]);         
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