<?php
require_once "./app/model/model.php";
include_once "./app/model/personaDTO.php";

class personaDAO extends Model {

   public function editarForm($form){
        $this->connect();
        $update = "UPDATE `diagnostico_idea` SET `Asesor`= '".$form['Asesor']."', `Nombres`= '".$form['Nombres']."', `Apellidos`= '".$form['Apellidos']."', `CC`='".$form['CC']."',
             `Posicion`= '".$form['Posicion']."', `Telefono`= '".$form['Telefono']."', `Celular`= '".$form['Celular']."', `Idea`= '".$form['Idea']."',
             `Motivacion`= '".$form['Motivacion']."', `Elecion`= '".$form['Elecion']."', `Productos`= '".$form['Productos']."',
             `Personal_requerido`= '".$form['Personal_requerido']."', `Grupo_empresarial`= '".$form['Grupo_empresarial']."',
             `Equipo_caracteristicas`= '".$form['Equipo_caracteristicas']."', `Criterios_contratacion`= '".$form['Criterios_contratacion']."',
             `Mercado_objetivo`= '".$form['Mercado_objetivo']."', `Mercado_objetivo_ubica`= '".$form['Mercado_objetivo_ubica']."',
             `Competidores`= '".$form['Competidores']."', `Factor_diferenciador`= '".$form['Factor_diferenciador']."',
             `Condiciones_venta`= '".$form['Condiciones_venta']."', `Ubicacion_negocio`= '".$form['Ubicacion_negocio']."',
             `Ubicacion_influencia`= '".$form['Ubicacion_influencia']."', `Estrategia_precios`= '".$form['Estrategia_precios']."',
             `Canales_distribucion`= '".$form['Canales_distribucion']."', `Promocion_negocio`= '".$form['Promocion_negocio']."',
             `Costo_operacion`= '".$form['Costo_operacion']."', `Fuentes_financiacion`= '".$form['Fuentes_financiacion']."',
             `Tiempo_retorno_inversion`= '".$form['Tiempo_retorno_inversion']."', `Como_estimo_precio`= '".$form['Como_estimo_precio']."',
             `Costo_producto`= '".$form['Costo_producto']."', `Asuntos_finanza`= '".$form['Asuntos_finanza']."', `Desarrollo_producto`= '".$form['Desarrollo_producto']."',
             `Tecnologia_requerida`= '".$form['Tecnologia_requerida']."', `Infraestructura_requerida`= '".$form['Infraestructura_requerida']."',
             `Regulaciones_operacion`= '".$form['Regulaciones_operacion']."', `Tipo_persona`= '".$form['Tipo_persona']."',
             `Aspectos_mejorar`= '".$form['Aspectos_mejorar']."' WHERE `Num_consecutivo`=".$form['Num_consecutivo'];

        $query = $this->query($update);
        $this->terminate();
    }

    public function agregarForm($form){
        $this->connect();
        foreach($form as $clave => $valor){
            if($valor==""){
                $form[$clave]="NULL";
            }else{
                $form[$clave]="'".$valor."'";
            }
        }
        
    
      $insert= "INSERT INTO user (Nombres, Apellidos, CC, Posicion, Telefono, Celular) VALUES (".''.$form['Nombres'].',
      '.$form['Apellidos'].',
      '.$form['CC'].',
      '.$form['Posicion'].',
      '.$form['Telefono'].',
      '.$form['Celular'].');'; 
        
 
        $this->query($insert);
        $consulta="SELECT * FROM user where CC=".$form['CC']."";
        $array=array();
        $query=$this->query($consulta);
        while($row=mysqli_fetch_array($query)){

        $persona=new personaDTO($row["Nombres"],$row["Apellidos"], $row["CC"],$row["Posicion"],$row["Telefono"],$row["Celular"]);

        
        array_unshift($array,$persona);
        }
        
     /*                           
                                
     foreach($array as $element){
         echo $element->nombres."-";
     }
        $this->terminate();
        
    */
        return "saassa";
    }

    public function consultarForm($consecutivo){
        $this->connect();
        $consulta = "SELECT * FROM diagnostico_idea WHERE Num_consecutivo = ".$consecutivo;
        $query = $this->query($consulta);
        $this->terminate();
        $row= mysqli_fetch_array($query);
        return $row;
    }


    public function consultarCcDiagIdea($cc){
        $this->connect();
        $consulta = "SELECT Num_consecutivo, Fecha, Idea FROM diagnostico_idea  WHERE CC=".$cc;
        $query = $this->query($consulta);
        $this->terminate();
        $array=array();
        while($row = mysqli_fetch_array($query)){
            array_unshift($array,$row);
        }
        return $array;
    }

}

?>
