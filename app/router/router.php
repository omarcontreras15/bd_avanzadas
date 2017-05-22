
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
                    $this->user->agregarEmpleado();
                    break;
                
                case "consultarEmpleado":
                    $this->user->consultarEmpleado();
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

                case "eliminarProyecto":
                    $this->user->eliminarProyecto($_GET);
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