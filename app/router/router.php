
<?php

class Router {

    public $user;

    public function __construct() {
        $this->user = new User();
    }

    

    public function router() {
        if(isset($_GET["mode"])) {
            switch ($_GET["mode"]) {
                case "agregarSucursal":
                    $this->user->agregarSucursal();
                    break;
                
                case "consultarSucursales":
                    $this->user->consultarSucursales();
                    break;

                case "editarSucursal":
                    $this->user->editarSucursal($_GET["id"]);
                    break;

                case "eliminarSucursal1":
                    $this->user->eliminarSucursal($_GET);
                    break;
                    
                case "agregarProyecto":
                    $this->user->agregarProyecto();
                    break;

                case "consultarProyecto":
                    $this->user->consultarProyecto();
                    break;

                case "realizarPedido":
                    $this->user->realizarPedido();
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
                case "editarPieza":
                    $this->user->editarPieza($_GET["id"]);
                    break;

                case "consultarPedidos":
                    $this->user->consultarPedidos();
                    break;

                case "eliminarPedido":
                    $this->user->eliminarPedido($_GET);
                    break;

                case "verDetallePedido":
                    $this->user->verDetallePedido($_GET);
                    break;

                case "editarPedido":
                    $this->user->editarPedido($_GET);
                    break;

                    default:
                    header("Location:index.php");
                    break;
            }

        } else if(isset($_POST["mode"])) {
            switch ($_POST["mode"]) {
                case "agregarSucursalFormulario":
                    $this->user->agregarFormSucursal($_POST);
                    break;

                case "editarSucursalFormulario":
                    $this->user->editarSucursalFormulario($_POST);
                    break;

                case "agregarProyectoFormulario":
                    $this->user->agregarFormProyecto($_POST);         
                    break;

                case "editarPiezaFormulario":
                    $this->user->editarPiezaFormulario($_POST);
                    break;
                
                case "realizarPedidoFormulario":
                    $this->user->realizarPedidoFormulario($_POST);
                    break;

                case "agregarPiezaPedido":
                    $this->user->agregarPiezaPedido($_POST);
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