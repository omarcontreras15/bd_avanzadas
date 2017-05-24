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
        $listaTareas = $this->userModel->listarTareasProyecto($id);
        foreach ($listaTareas as $key => $value) {
            $temp = $contenido;
            $temp = $this->renderView($temp, "{{NOM_TAREA}}", $value['nom_tarea']);
            $temp = $this->renderView($temp, "{{ID}}", ((string)$key));
            $temp = $this->renderView($temp, "{{form_tarea}}"," ");
            $tareas .= $temp;
        }
        echo $tareas;
    }

    public function cargarTareasEditar($id_proyecto, $arrayTareas)
    {
        $tareas = "";
        $contenido = $this->getTemplate("./app/views/components/editar-tareas-empleado.html");
        $listaTareas = $this->userModel->listarTareasProyecto($id_proyecto);
        foreach ($listaTareas as $key => $value) {
            $validarTarea=false;
            $temp = $contenido;
            $temp = $this->renderView($temp, "{{NOM_TAREA}}", $value['nom_tarea']);
            $temp = $this->renderView($temp, "{{id_tarea}}", ((string)$key));

            $formTarea=$this->getTemplate("./app/views/components/form-editar-tareas-empleado.html");

            foreach ($arrayTareas as $tarea){
                if((string)$key== $tarea['id_tarea']){
                    $temp = $this->renderView($temp, "{{CHECKED}}","CHECKED");
                    $formTarea=$this->renderView($formTarea, "{{ID_TAREA}}",$tarea['id_tarea']);
                    $formTarea=$this->renderView($formTarea, "{{horas_trabajo}}",$tarea['horas_trabajo']);
                    $formTarea=$this->renderView($formTarea, "{{cargo}}",$tarea['cargo']);
                    $temp = $this->renderView($temp, "{{form_tarea}}", $formTarea);
                    $validarTarea=true;
                }
            }

            if($validarTarea==false){
                $temp = $this->renderView($temp, "{{CHECKED}}"," ");
                $temp = $this->renderView($temp, "{{form_tarea}}", " ");

            }
            $tareas .= $temp;
        }
        return $tareas;
    }



    public function registrarEmpleado($form)
    {
        $empleado = $this->userModel->buscarEmpleadoCC($form['cc']);

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
        $consultaEmpleados = $this->getTemplate("./app/views/accion/listaEmpleados.html");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $consultaEmpleados);
        $listadoEmpleados = $this->userModel->listarEmpleados();
        $tablaHtmlCompleta = "";

        foreach ($listadoEmpleados as $element) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaEmpleados.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{NOMBRE}}", $element['nombre_empleado']);
            $tablaHtml = $this->renderView($tablaHtml, "{{CC}}", $element['cc']);
            $tablaHtml = $this->renderView($tablaHtml, "{{DIRECCION}}", $element['direccion']);
            $tablaHtml = $this->renderView($tablaHtml, "{{TELEFONO}}", $element['telefono']);
            $var1 = "<a href='index.php?mode=editar-empleado&id=" . $element['_id']. "'>
            <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
            <a href='index.php?mode=eliminar-empleado&id=" .$element['_id']. "'>
            <button type='button' class='btn btn-danger'>Borrar</button></a>";
            $tablaHtml = $this->renderView($tablaHtml, "{{OPCIONES}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Consulta Empleados");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $tablaHtmlCompleta);
        $this->showView($this->view);
    }

    public function formEditarEmpleado($id)
    {
        $idProyecto=null;
        $tareas=null;
        $contenido = $this->getTemplate("./app/views/accion/editarEmpleado.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Empleado");
        $empleado=$this->userModel->buscarEmpleadoID($id);
        foreach ($empleado as $value){
            $idProyecto=$value['id_proyecto'];
            $tareas=$value['tareas'];
            $contenido = $this->renderView($contenido, "{{nombre_empleado}}", $value['nombre_empleado']);
            $contenido = $this->renderView($contenido, "{{cc}}", $value['cc']);
            $contenido = $this->renderView($contenido, "{{telefono}}", $value['telefono']);
            $contenido = $this->renderView($contenido, "{{direccion}}", $value['direccion']);
        }
        $combo = "<option value='ninguno'>Ninguno</option>";
        $listadoProyectos = $this->userModel->listarProyectos();
        foreach ($listadoProyectos as $key => $value) {
            if(!((string)$key==$idProyecto)) {
                $combo .= "<option value='" . (string)$key . "'>" . $value['nombre'] . "</option>";
            }else if($idProyecto=="ninguno"){

            }else{
                $combo .= "<option selected value='" . (string)$key . "'>" . $value['nombre'] . "</option>";
            }
        }
        $htmlTareas=$this->cargarTareasEditar($idProyecto,$tareas);
        $contenido = $this->renderView($contenido, "{{grupo_tareas}}", $htmlTareas);
        $contenido = $this->renderView($contenido, "{{id_empleado}}", $id);
        $contenido = $this->renderView($contenido, "{{COMBO_PROYECTOS}}", $combo);
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $contenido);
        $this->showView($this->view);
    }

    public function actualizarEmpleado($form){
        $this->eliminarEmpleado($form['id_empleado']);
        $this->registrarEmpleado($form);
    }


    public function eliminarEmpleado($id)
    {
        $this->userModel->eliminarEmpleado($id);
        header("Location: index.php?mode=consultarEmpleado");

    }

    public function realizarTarea()
    {
        $proyectos = $this->userModel->listarProyectos();
        $stringProyectos = "";
        foreach ($proyectos as $key => $val) {
            $stringProyectos .= "<option value='" . $val["nombre"] . "'>" . $val["nombre"] . "</option>";
        }
        $tablaHtml = $this->getTemplate("./app/views/accion/realizaTarea.html");
        $tablaHtml = $this->renderView($tablaHtml, "{{PROYECTO}}", $stringProyectos);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Realizar pedido");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $tablaHtml);
        $this->showView($this->view);

    }


    public function agregarFormTarea($form)
    {
        $mensaje = $this->userModel->registrarTarea($form);
        $this->realizarTarea();
        echo "<script language=JavaScript>alert('" . $mensaje . "');</script>";

    }

    public function consultarTarea()
    {
        $registroPieza = $this->getTemplate("./app/views/accion/listaTareas.html");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $registroPieza);
        $listadoTareas = $this->userModel->listarTareas();
        $tablaHtmlCompleta = "";

        foreach ($listadoTareas as $key => $val) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaTareas.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{proyecto}}", $val["proyecto"]);
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

    public function eliminarTarea($form)
    {
        $this->userModel->eliminarTarea($form['id']);

        $listadoTareas = $this->userModel->listarTareas();
        $tablaHtmlCompleta = "";

        foreach ($listadoTareas as $key => $val) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaTareas.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{proyecto}}", $val["proyecto"]);
            $var1 = "<a href='index.php?mode=editarPieza&id=" . (string)$key . "'>
                <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
                <button onclick=realizarAjax('" . (string)$key . "') type='button' class='btn btn-danger borrar'>Borrar</button>";
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

        $tablaHtmlCompleta="";

            foreach ($listadoProyectos as $key => $val) {
                $tablaHtml= $this->getTemplate("./app/views/components/tablaProyectos.html");
                $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
                $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
                $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad"]);
                $var1="<a href='index.php?mode=editarProyecto&id=".(string)$key."'>";

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
    }

    public function eliminarProyecto($form)
    {
        $this->userModel->eliminarProyecto($form['id']);

        $listadoProyectos = $this->userModel->listarProyectos();

        $tablaHtmlCompleta="";

            foreach ($listadoProyectos as $key => $val) {
                $tablaHtml= $this->getTemplate("./app/views/components/tablaProyectos.html");
                $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["codigo"]);
                $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nombre"]);
                $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad"]);
                $var1="<a href='index.php?mode=editarProyecto&id=".(string)$key."'>";

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

    public function editarProyecto($id){

        $proyecto = $this->userModel->buscarProyecto($id);
        $editarProyecto = $this->getTemplate("./app/views/accion/editarProyecto.html");
        $editarProyecto = $this->renderView($editarProyecto, "{{CODIGO}}", $proyecto["codigo"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{NOMBRE}}", $proyecto["nombre"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{CIUDAD}}", $proyecto["ciudad"]);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Proyecto");
        $this->view = $this->renderView($this->view,"{{CONTENIDO}}", $editarProyecto);

        $this->showView($this->view);
        $this->showView($tablaHtmlCompleta);
    }
}

?>