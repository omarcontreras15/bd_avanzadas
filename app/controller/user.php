<?php

include_once "./app/controller/controller.php";

class User extends Controller
{

    private $userModel;
    private $view;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->view = $this->getTemplate("./app/views/index.html");
    }

    public function index()
    {
        $inicio = $this->getTemplate("./app/views/inicio.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Inicio");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $inicio);
        $this->showView($this->view);
    }

// Metodos sucursal

    public function formAgregarEmpleado()
    {
        $contenido = $this->getTemplate("./app/views/accion/registroEmpleado.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Registrar Empleado");
        $combo = "<option value='ninguno'>Ninguno</option>";
        $listadoProyectos = $this->userModel->listarProyectos();
        foreach ($listadoProyectos as $key => $value) {
            $combo .= "<option value='" . (string)$key . "'>" . $value['nombre'] . "</option>";
        }
        $contenido = $this->renderView($contenido, "{{COMBO_PROYECTOS}}", $combo);
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $contenido);
        $this->showView($this->view);
    }

    public function cargarTareas($id)
    {
        $tareas = "";
        $contenido = $this->getTemplate("./app/views/components/tareas-empleado.html");
        $temp = $contenido;
        $listaTareas = $this->userModel->listarTareas($id);
        foreach ($listaTareas as $key => $value) {
            //echo $value['nom_tarea'];
            $temp = $contenido;
            $temp = $this->renderView($temp, "{{NOM_TAREA}}", $value['nom_tarea']);
            $temp = $this->renderView($temp, "{{ID}}", ((string)$key));
            $tareas .= $temp;
        }
        echo $tareas;
    }

    public function registrarEmpleado($form)
    {
        $empleado = $this->userModel->buscarEmpleado($form['cc']);

        if ($empleado->count() == 0) {
            $tareas = array();
            if (isset($form["tareas-empleados"])) {
                foreach ($form["tareas-empleados"] as $element) {

                    $tarea = array(
                        "id_tarea" => $element,
                        "horas_trabajo" => (integer)$form["horas-trabajo-" . $element],
                        "cargo" => $form["cargo-" . $element]
                    );

                    array_unshift($tareas, $tarea);
                }
            } else {
                array_unshift($tareas, array());
            }
            $empleado = array(
                "nombre_empleado" => $form['nombre_empleado'],
                "cc" => (integer)$form['cc'],
                "telefono" => (integer)$form['telefono'],
                "direccion" => $form['direccion'],
                "id_proyecto" => $form['id_proyecto'],
                "tareas" => $tareas
            );
            $this->userModel->registrarEmpleado($empleado);
            echo "<script>alert('El empleado  se registro exitosamente!'); window.location='index.php?mode=agregarEmpleado';</script>";
        } else {
            echo "<script>alert('El empleado ya se encuentra registrado, Por favor intentelo de nuevo');window.history.back();</script>";
        }

    }


    public function consultarEmpleados()
    {
        $registroSucursal = $this->getTemplate("./app/views/accion/listaSucursales.html");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $registroSucursal);
        $listadoSucursales = $this->userModel->mostrarSucursales();
        $tablaHtmlCompleta = "";

        foreach ($listadoSucursales as $element) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaSucursales.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $element->getCod_entidad());
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $element->getNombre());
            $tablaHtml = $this->renderView($tablaHtml, "{{direccion}}", $element->getDireccion());
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $element->getCiudad());
            $var1 = "<a href='index.php?mode=editarSucursal&id=" . $element->getCod_entidad() . "'>
            <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
            <button onclick='realizarAjax(" . $element->getCod_entidad() . ")' type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Listado Sucursales");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $tablaHtmlCompleta);
        $this->showView($this->view);
    }

    public function editarEmpleado($id)
    {
        $tablaHtml = $this->getTemplate("./app/views/accion/editaSucursal.html");
        $element = $this->userModel->buscarSucursal($id);
        $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $element[0]->getCod_entidad());
        $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $element[0]->getNombre());
        $tablaHtml = $this->renderView($tablaHtml, "{{direccion}}", $element[0]->getDireccion());
        $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $element[0]->getCiudad());
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Sucursal");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $tablaHtml);
        $this->showView($this->view);
    }

    public function editarSucursalFormulario($formulario)
    {
        $mensaje = $this->userModel->editarSucursalFormulario($formulario);
        $this->consultarSucursales();
        echo "<script language=JavaScript>alert('" . $mensaje . "');</script>";
    }

    public function eliminarEmpleado($form)
    {
        $this->userModel->eliminarSucursal($form['id']);
        $listadoSucursales = $this->userModel->mostrarSucursales();
        $tablaHtmlCompleta = "";
        foreach ($listadoSucursales as $element) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaSucursales.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $element->getCod_entidad());
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $element->getNombre());
            $tablaHtml = $this->renderView($tablaHtml, "{{direccion}}", $element->getDireccion());
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $element->getCiudad());
            $var1 = "<a href='index.php?mode=editarSucursal&id=" . $element->getCod_entidad() . "'>
            <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
            <button onclick='realizarAjax(" . $element->getCod_entidad() . ")' type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }
        $this->showView($tablaHtmlCompleta);
    }

    public function agregarProyecto()
    {
        $registroPieza = $this->getTemplate("./app/views/accion/registroProyecto.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Registrar Pieza");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $registroPieza);
        $this->showView($this->view);
    }

    public function agregarFormProyecto($form)
    {
        $mensaje = $this->userModel->registrarProyecto($form);
        $this->agregarProyecto();
        echo "<script language=JavaScript>alert('" . $mensaje . "');</script>";

    }


    public function consultarProyecto()
    {
        $registroPieza = $this->getTemplate("./app/views/accion/listaProyectos.html");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $registroPieza);
        $listadoProyectos = $this->userModel->listarProyectos();
        $tablaHtmlCompleta = "";

        foreach ($listadoProyectos as $key => $val) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaProyectos.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad"]);
            $var1 = "<a href='index.php?mode=editarPieza&id=" . (string)$key . "'>
                <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
                <button onclick=realizarAjax('" . (string)$key . "') type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }

        $this->view = $this->renderView($this->view, "{{TITULO}}", "Listado Piezas");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $tablaHtmlCompleta);
        $this->showView($this->view);
    }

    public function eliminarProyecto($form)
    {
        $this->userModel->eliminarProyecto($form['id']);

        $listadoProyectos = $this->userModel->listarProyectos();
        $tablaHtmlCompleta = "";

        foreach ($listadoProyectos as $key => $val) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaProyectos.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad"]);
            $var1 = "<a href='index.php?mode=editarPieza&id=" . (string)$key . "'>
                <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
                <button onclick=realizarAjax('" . (string)$key . "') type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }

        $this->showView($tablaHtmlCompleta);
    }
}

?>