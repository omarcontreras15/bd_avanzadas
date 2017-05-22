<?php

include_once "./app/controller/controller.php";
include_once "./app/model/personaDAO.php";

class Accion extends Controller {

    private $personaDAO;
    private $view;


    public function __construct() {
        $this->personaDAO= new personaDAO();
        $this->view = $this->getTemplate("./app/views/index.html");
    }

    public function registrar(){
        $form = $this->getTemplate("./app/views/accion/registroAccion.html");
        $this->view = $this->renderView($this->view, "{{TITLE}}","Registrar");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $form);
        $this->showView($this->view); 
    }

    public function agregarFormRegistrar($form){
       
        $id=$this->personaDAO->agregarForm($form);
        
      
        if($id!=""){
            echo "<script>alert('Registro éxitoso');</script>";
            header("Location:index.php");
        }else{
             echo "<script>alert('Error al registrar, intente más tarde');</script>";
        }
        
        
   }      

    public function editarFormDiagnosticoIdea($form){
       $query= $this->diagnosticoIdeaModel->editarForm($form);
       echo $query;
       echo "<script>alert('Diagnostico de la idea actualizado exitosamente.');window.location='index.php';</script>"; 
    }


    public function editarForm($num_consecutivo){
        $row = $this->diagnosticoIdeaModel->consultarForm($num_consecutivo);
        $form= $this->getTemplate("./app/views/DiagnosticoIdea/editarDIdea.html");
        $menu = $this->getTemplate("./app/views/components/menu-login.html");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $form);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Editar Diagnostico Idea");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view,"{{Num_consecutivo}}" ,$num_consecutivo);
        $this->llenarForm($row);

    }

    public function consultarForm($num_consecutivo){
        $row = $this->diagnosticoIdeaModel->consultarForm($num_consecutivo);
        $form= $this->getTemplate("./app/views/DiagnosticoIdea/consultarDIdea.html");
        $menu = $this->getTemplate("./app/views/components/menu-login.html");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $form);
        $this->view = $this->renderView($this->view, "{{TITULO}}", "Consultar Diagnostico Idea");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        foreach($row as $clave=>$valor){
            $this->view = $this->renderView($this->view, "{{".$clave."}}", $valor);
        }
        $this->showView($this->view);  

    }


    //llena el form para editar y para consultar diagnostico
    public function llenarForm($row){
        $this->view = $this->renderView($this->view,"{{Asesor}}" , $row['Asesor']);
        $this->view = $this->renderView($this->view,"{{Fecha}}" , $row['Fecha']);
        $this->view = $this->renderView($this->view,"{{Nombres}}" , $row['Nombres']);
        $this->view = $this->renderView($this->view,"{{Apellidos}}" , $row['Apellidos']);
        $this->view = $this->renderView($this->view,"{{CC}}" , $row['CC']);

        //select posicion
        if($row['Posicion']=='Dueño'){
            $this->view = $this->renderView($this->view,'{{opt_dueño}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_socio}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_otro}}" , "");
        }else if($row['Posicion']=='Socio'){
            $this->view = $this->renderView($this->view,"{{opt_dueño}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_socio}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_otro}}" , "");
        }else{
            $this->view = $this->renderView($this->view,"{{opt_dueño}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_socio}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_otro}}"' , '"selected');
        }

        $this->view = $this->renderView($this->view,"{{Telefono}}" , $row['Telefono']);
        $this->view = $this->renderView($this->view,"{{Celular}}" , $row['Celular']);
        $this->view = $this->renderView($this->view,"{{Idea}}" , $row['Idea']);
        $this->view = $this->renderView($this->view,"{{Motivacion}}" , $row['Motivacion']);
        $this->view = $this->renderView($this->view,"{{Elecion}}" , $row['Elecion']);
        $this->view = $this->renderView($this->view,"{{Productos}}" , $row['Productos']);
        $this->view = $this->renderView($this->view,"{{Personal_requerido}}" , $row['Personal_requerido']);
        $this->view = $this->renderView($this->view,"{{Grupo_empresarial}}" , $row['Grupo_empresarial']);
        $this->view = $this->renderView($this->view,"{{Equipo_caracteristicas}}" , $row['Equipo_caracteristicas']);
        $this->view = $this->renderView($this->view,"{{Criterios_contratacion}}" , $row['Criterios_contratacion']);
        $this->view = $this->renderView($this->view,"{{Mercado_objetivo}}" , $row['Mercado_objetivo']);
        $this->view = $this->renderView($this->view,"{{Mercado_objetivo_ubica}}" , $row['Mercado_objetivo_ubica']);
        $this->view = $this->renderView($this->view,"{{Competidores}}" , $row['Competidores']);
        $this->view = $this->renderView($this->view,"{{Factor_diferenciador}}" , $row['Factor_diferenciador']);

        //select condiciones_venta
        if($row['Condiciones_venta']=='Venta de contado'){
            $this->view = $this->renderView($this->view,'{{opt_contado}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_credito}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_consignacion}}" , "");
        }else if($row['Condiciones_venta']=='Venta a credito'){
            $this->view = $this->renderView($this->view,"{{opt_contado}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_credito}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_consignacion}}" , "");
        }else{
            $this->view = $this->renderView($this->view,"{{opt_contado}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_credito}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_consignacion}}"' , '"selected');
        }
        $this->view = $this->renderView($this->view,"{{Ubicacion_negocio}}" , $row['Ubicacion_negocio']);
        if($row['Ubicacion_influencia']=='si'){
            $this->view = $this->renderView($this->view,'{{opt_si}}"' , '"checked');
            $this->view = $this->renderView($this->view,"{{opt_no}}" , "");    
        }else{
            $this->view = $this->renderView($this->view,'{{opt_no}}"' , '"checked');
            $this->view = $this->renderView($this->view,"{{opt_si}}" , "");
        }

        $this->view = $this->renderView($this->view,"{{Estrategia_precios}}" , $row['Estrategia_precios']);
        $this->view = $this->renderView($this->view,"{{Canales_distribucion}}" , $row['Canales_distribucion']);
        $this->view = $this->renderView($this->view,"{{Promocion_negocio}}" , $row['Promocion_negocio']);
        $this->view = $this->renderView($this->view,"{{Costo_operacion}}" , $row['Costo_operacion']);

        //select fuentes_financiacion
        if($row['Fuentes_financiacion']=='Recursos propios'){
            $this->view = $this->renderView($this->view,'{{opt_recursos}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_entidades}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_otrafuente}}" , "");
        }else if($row['Fuentes_financiacion']=='Credito con entidades financieras'){
            $this->view = $this->renderView($this->view,"{{opt_recursos}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_entidades}}"' , '"selected');
            $this->view = $this->renderView($this->view,"{{opt_otrafuente}}" , "");
        }else{
            $this->view = $this->renderView($this->view,"{{opt_recursos}}" , "");
            $this->view = $this->renderView($this->view,"{{opt_entidades}}" , "");
            $this->view = $this->renderView($this->view,'{{opt_otrafuente}}"' , '"selected');
        }

        $this->view = $this->renderView($this->view,"{{Tiempo_retorno_inversion}}" , $row['Tiempo_retorno_inversion']);
        $this->view = $this->renderView($this->view,"{{Como_estimo_precio}}" , $row['Como_estimo_precio']);
        $this->view = $this->renderView($this->view,"{{Costo_producto}}" , $row['Costo_producto']);
        $this->view = $this->renderView($this->view,"{{Asuntos_finanza}}" , $row['Asuntos_finanza']);
        $this->view = $this->renderView($this->view,"{{Desarrollo_producto}}" , $row['Desarrollo_producto']);
        $this->view = $this->renderView($this->view,"{{Tecnologia_requerida}}" , $row['Tecnologia_requerida']);
        $this->view = $this->renderView($this->view,"{{Infraestructura_requerida}}" , $row['Infraestructura_requerida']);
        $this->view = $this->renderView($this->view,"{{Regulaciones_operacion}}" , $row['Regulaciones_operacion']);
        $this->view = $this->renderView($this->view,"{{Tipo_persona}}" , $row['Tipo_persona']);
        $this->view = $this->renderView($this->view,"{{Aspectos_mejorar}}" , $row['Aspectos_mejorar']);
        $this->showView($this->view);
    }

    public function ventanaEditarDiag(){
        $ventana = $this->getTemplate("./app/views/components/ventana-consultar.html");
        $menu = $this->getTemplate("./app/views/components/menu-login.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}","Editar Diagnostico Idea");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        $this->view = $this->renderView($this->view, "{{TITULO_VENTANA}}", "Editar diagnostico de la idea");
        $this->view = $this->renderView($this->view, "{{PLACEHOLDER}}", "Ingrese el numero de la cedula");
        $this->view = $this->renderView($this->view, "{{TIPO_OPERACION}}", "EDITAR");
        $this->showView($this->view);    

    }
    public function ventanaConsultarDiag(){
        $ventana = $this->getTemplate("./app/views/components/ventana-consultar.html");
        $menu = $this->getTemplate("./app/views/components/menu-login.html");
        $this->view = $this->renderView($this->view, "{{TITULO}}","Consultar Diagnostico Idea");
        $this->view = $this->renderView($this->view, "{{SESION}}", $menu);
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $ventana);
        $this->view = $this->renderView($this->view, "{{TITULO_VENTANA}}", "Consulta el diagnostico de la idea");
        $this->view = $this->renderView($this->view, "{{PLACEHOLDER}}", "Ingrese el numero de la cedula");
        $this->view = $this->renderView($this->view, "{{TIPO_OPERACION}}", "CONSULTAR");
        $this->showView($this->view);    

    }

    public function consultarCcDiagIdea($cc, $tipo_operacion){
        $array=$this->diagnosticoIdeaModel->consultarCcDiagIdea($cc);
        $sizeArray=sizeof($array);
        $option="";
        $form=$form=$this->getTemplate("./app/views/components/form-ventana-diag-idea.html");

        if($tipo_operacion=="EDITAR"){
            $form = $this->renderView($form, "{{TITULO2}}","Seleccione el diagnostico que desea editar.");
            $form = $this->renderView($form, "{{RUTA}}","seleccionar-editar-diagnostico-idea");
            $form = $this->renderView($form, "{{VALOR_BOTON}}","EDITAR DIAGNOSTICO IDEA");
        }else{
            $form = $this->renderView($form, "{{TITULO2}}","Seleccione el diagnostico que desea consultar.");
            $form = $this->renderView($form, "{{RUTA}}","seleccionar-consultar-diagnostico-idea");
            $form = $this->renderView($form, "{{VALOR_BOTON}}","CONSULTAR DIAGNOSTICO IDEA");
        }


        $form=$this->renderView($form, "{{TAMANIO_ARRAY}}",$sizeArray+1);
        if($sizeArray>0){
            foreach($array as $element){
                list($fecha)=split(" ", $element["Fecha"], 2);
                $option=$option."<option value='".$element["Num_consecutivo"]."'>01-000".$element["Num_consecutivo"]."/".$fecha."/".$element["Idea"]."</option>";  
            }
            $form=$this->renderView($form, "{{OPTION}}",$option);
            echo $form;
        }else{
            echo "<h2>No Existen Diagnosticos</h2>";

        }


    }


}

?>