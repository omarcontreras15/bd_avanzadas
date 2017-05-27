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
        $html_tareas=$this->cargarTareas();
        $contenido = $this->renderView($contenido, "{{grupo_tareas}}", $html_tareas);
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $contenido);
        $this->showView($this->view);
    }

    public function cargarTareas()
    {
        $tareas = "";
        $contenido = $this->getTemplate("./app/views/components/tareas-empleado.html");
        $listaTareas = $this->userModel->listarTareasProyecto();
        foreach ($listaTareas as $key => $value) {
            $temp = $contenido;
            $proyecto=$this->userModel->buscarNomProyecto($value["id_proyecto"]);
            $temp = $this->renderView($temp, "{{NOM_PROYECTO}}", $proyecto);
            $temp = $this->renderView($temp, "{{NOM_TAREA}}", $value['nom_tarea']);
             $temp = $this->renderView($temp, "{{id_proyecto}}", $value['nom_tarea']);
            $temp = $this->renderView($temp, "{{ID}}", ((string)$key));
            $temp = $this->renderView($temp, "{{form_tarea}}"," ");
            $tareas .= $temp;
        }
        return $tareas;
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
            $proyecto=$this->userModel->buscarNomProyecto($value["id_proyecto"]);
            $temp=$this->renderView($temp, "{{id_proyecto}}",$value["id_proyecto"]);
            $temp=$this->renderView($temp, "{{NOM_PROYECTO}}",$proyecto);

            $formTarea=$this->getTemplate("./app/views/components/form-editar-tareas-empleado.html");

            foreach ($arrayTareas as $tarea){
                if(isset($tarea['id_tarea']) && (string)$key== $tarea['id_tarea']){
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
            $empleado=$this->armarArrayEmpleado($form);
            $this->userModel->registrarEmpleado($empleado);
            echo "<script>alert('El empleado  se registro exitosamente!'); window.location='index.php?mode=agregarEmpleado';</script>";
        } else {
            echo "<script>alert('El empleado ya se encuentra registrado, Por favor intentelo de nuevo');window.history.back();</script>";
        }

    }

    public function armarArrayEmpleado($form){
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
        return $empleado;
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

        $htmlTareas=$this->cargarTareasEditar($idProyecto,$tareas);
        $contenido = $this->renderView($contenido, "{{grupo_tareas}}", $htmlTareas);
        $contenido = $this->renderView($contenido, "{{id_empleado}}", $id);
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $contenido);
        $this->showView($this->view);
    }

    public function actualizarEmpleado($form){
        $empleado=$this->armarArrayEmpleado($form);
        $this->userModel->actualizarEmpleado($empleado, $form['id_empleado']);
        echo "<script>alert('Se ha actualizado la informacion satisfactoriamente'); window.location='index.php?mode=consultarEmpleado';</script>";
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
            $stringProyectos .= "<option value='" . $val["_id"] . "'>" . $val["nom_proyecto"] . "</option>";
        }
        $tablaHtml = $this->getTemplate("./app/views/accion/realizaTarea.html");
        $tablaHtml = $this->renderView($tablaHtml, "{{PROYECTO}}", $stringProyectos);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Registrar Tarea");
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
        $listaTareas = $this->getTemplate("./app/views/accion/listaTareas.html");
        $this->view = $this->renderView($this->view, "{{CONTENIDO}}", $listaTareas);
        $listadoTareas = $this->userModel->listarTareas();
        $tablaHtmlCompleta = "";

        foreach ($listadoTareas as $key => $val) {
            $tablaHtml = $this->getTemplate("./app/views/components/tablaTareas.html");
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["cod_tarea"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nom_tarea"]);
            $proyecto=$this->userModel->buscarNomProyecto($val["id_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{proyecto}}", $proyecto);
            $var1 = "<a href='index.php?mode=editarTarea&id=" . (string)$key . "'>
                <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
                <button onclick=realizarAjax('" . (string)$key . "') type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Listado Tareas");
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
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["cod_tarea"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nom_tarea"]);
            $proyecto=$this->userModel->buscarNomProyecto($val["id_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{proyecto}}", $proyecto);
            $var1 = "<a href='index.php?mode=editarTarea&id=" . (string)$key . "'>
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
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Registrar Proyecto");
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
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["cod_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nom_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad_proyecto"]);
            $var1 = "<a href='index.php?mode=editarProyecto&id=" . (string)$key . "'>

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
            $tablaHtml = $this->renderView($tablaHtml, "{{codigo}}", $val["cod_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{nombre}}", $val["nom_proyecto"]);
            $tablaHtml = $this->renderView($tablaHtml, "{{ciudad}}", $val["ciudad_proyecto"]);
            $var1 = "<a href='index.php?mode=editarProyecto&id=" . (string)$key . "'>

                <button type='button' class='btn btn-warning'>Editar</button></a>&nbsp           
                <button onclick=realizarAjax('" . (string)$key . "') type='button' class='btn btn-danger borrar'>Borrar</button>";
            $tablaHtml = $this->renderView($tablaHtml, "{{opciones}}", $var1);

            $tablaHtmlCompleta .= $tablaHtml;
        }


            $this->showView($tablaHtmlCompleta);
     
    }

    public function editarProyecto($id){

        $proyecto = $this->userModel->buscarProyecto($id);
        $editarProyecto = $this->getTemplate("./app/views/accion/editarProyecto.html");
        $editarProyecto = $this->renderView($editarProyecto, "{{CODIGO}}", $proyecto["cod_proyecto"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{NOMBRE}}", $proyecto["nom_proyecto"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{CIUDAD}}", $proyecto["ciudad_proyecto"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{ID_PROYECTO}}", (string)$id);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Proyecto");
        $this->view = $this->renderView($this->view,"{{CONTENIDO}}", $editarProyecto);

        $this->showView($this->view);
    }
    
    public function editarProyectoFormulario($form){
        $result = $this->userModel->updateProyecto($form);
        $this->consultarProyecto();
         echo "<script language=JavaScript>alert('" . $result . "');</script>";
    }


    public function showFromEditTarea($id){

        $tarea = $this->userModel->buscarTarea($id);
        $tareas = $this->userModel->listarProyectos();
        $stringTareas = "";
        foreach ($tareas as $key => $val) {
            if($tarea["id_proyecto"]==$val["_id"]){
                    $stringTareas = "<option value='" . $val["_id"] . "'>" . $val["nom_proyecto"] . "</option>".$stringTareas;
            }else{
                    $stringTareas .= "<option value='" . $val["_id"] . "'>" . $val["nom_proyecto"] . "</option>";
            }
        }
        $editarProyecto = $this->getTemplate("./app/views/accion/editarTarea.html");
        $editarProyecto = $this->renderView($editarProyecto, "{{COD_TAREA}}", $tarea["cod_tarea"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{NOM_TAREA}}", $tarea["nom_tarea"]);
        $editarProyecto = $this->renderView($editarProyecto, "{{ID_TAREA}}", (string)$id);
        $editarProyecto = $this->renderView($editarProyecto, "{{PROYECTO}}", $stringTareas);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Proyecto");
        $this->view = $this->renderView($this->view,"{{CONTENIDO}}", $editarProyecto);
        $this->showView($this->view);
    }


public function editarTareaFormulario($form){
        $result = $this->userModel->updateTarea($form);
        $this->consultarTarea();
        echo "<script language=JavaScript>alert('" . $result . "');</script>";
    }
    
}

?>