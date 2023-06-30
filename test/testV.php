<?php

include_once ("..\datos\Viaje.php");
include_once ("..\datos\Empresa.php");
include_once ("..\datos\Pasajero.php");
include_once ("..\datos\ResponsableV.php");


$opcion= -1;


// Menu Principal
while ($opcion!=0){
echo "\n------------- Menu Principal -------------";
echo "\n 1 - Ingresar menu empresa";
echo "\n 2 - Ingresar menu responsable";
echo "\n 3 - Ingresar menu viaje";
echo "\n 4 - Ingresar menu pasajeros";
echo "\n 0 - Salir\n";

$opcion= trim(fgets(STDIN));

    switch ($opcion) {

        case 1:

            $opt=0;
            while($opt!=5){
                
                echo "\n------------- Menu Empresa -------------\n";
                echo "\n 1 - Ingresar datos empresa";
                echo "\n 2 - Modificar datos empresa";
                echo "\n 3 - Eliminar empresa";
                echo "\n 4 - Visualizar datos empresa";
                echo "\n 5 - Volver a menu principal\n";

                $empresa= new Empresa();
                $array=$empresa->listar();
                $opt=trim(fgets(STDIN));
                
                switch ($opt) {
                    case 1:

                        //Solicita datos de la empresa
                        echo "Ingrese el nombre de la empresa \n";
                        $nombreE= trim(fgets(STDIN));
                        echo "Ingrese direccion de la empresa \n";
                        $direccionE= trim(fgets(STDIN));

                        //lista las empresas en BD
                        $empresasCargadas= $empresa->listar($empresa->getIdEmpresa());
                       
                       
                        $cantElementos= count($empresasCargadas);

                        $seguir=true;
                        $i=0;
                        while($i<$cantElementos&& $seguir ){

                            //Si ya existen empresas verifica que no se repita el nombre
                            $emp=$empresasCargadas[$i];
                            if($emp->getNombre() == $nombreE){
                                echo "Ya existe una empresa con el mismo nombre\n";

                                $seguir=false;
                            }
                            $i++;
                            
                        }
                        //Si no existe la empresa, realiza la carga de info
                        if($seguir){
                            
                                $empresa->cargar(null,$nombreE,$direccionE);
                                $empresa->insertar();
                                echo "Se cargo datos de la empresa con exito\n";
                            
                        }

                       
                        break;
                    case 2: //Modificar datos empresa
                    
                        //Lista empresas cargadas en la BD
                        if($array != []){
                            echo "----------------- Empresas -----------------\n";
                        foreach($array as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";

                        echo "Ingrese el id de empresa a modificar\n";
                        $idMod=trim(fgets(STDIN));

                        //Verifica que el id de empresa a modificar exista en la BD
                        if($empresa->Buscar($idMod)==null){
                            echo "El id ingresado no se encuentra en la BD\n";
                        }else{

                        //Carga de nuevos datos
                        echo "Ingrese el nombre nuevo\n";
                        $nombreN=trim(fgets(STDIN));
                        echo "Ingrese la nueva dirección\n";
                        $dirN=trim(fgets(STDIN));
                        
                        //actualiza objempresa de id seleccionado
                        $empresaMod=$empresa->Buscar($idMod);
                        $empresaMod->cargar($idMod,$nombreN,$dirN);
                        
                        if($empresaMod->modificar()){
                            echo "Empresa ha sido modificada exitosamente\n";
                            
                        }else{
                            echo "No se ha podido modificar la impresa de id ingresado\n";
                        }
                        

                    }
                        
                    }else{
                        //Si el listado de empresas es nulo, no existen empresas cargadas
                        echo "No hay empresas cargadas aún\n";
                    };
                    break;

                    case 3: //Eliminar empresa
                       
                        //Muestra empresas cargadas en BD
                        echo "----------------- Empresas -----------------\n";
                        foreach($array as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";

                        $confirm="";
                        echo "Ingrese el id de empresa a eliminar\n";
                        $idE=trim(fgets(STDIN));

                        //Si existe id de empresa a eliminar en BD, la elimina 
                        $empresaEliminar=$empresa->Buscar($idE);

                        if($empresaEliminar == null){
                            echo "ID de empresa ingresado no reconocido\n";
                        }else{
                            echo "Esto eliminará la empresa y viajes asociados, desea continuar? S/N\n";
                            $confirm= trim(fgets(STDIN));
                            if($confirm== 's' || $confirm=='S'){
                                //Primero elimina todos los viajes de la empresa
                                $viajeE= new Viaje();
                                $condicion="idempresa='{$idE}'";
                                $arrayVE= $viajeE->listar($condicion);
                                foreach ($arrayVE as $value) {
                                    $value->eliminar();
                                }
                                $viajeE=$viajeE->listar($condicion);

                                if($empresaEliminar->eliminar()){
                                     echo "Empresa eliminada exitosamente\n";
                                 }else {
                                    echo "No se ha podido eliminar empresa, ya que tiene viajes asignados\nDeberá modificar esto antes de continuar\n";
                                 }

                            }
                            
                        }
                        break;
                    
                    case 4: //Visualizar empresas

                        //Muestra empresas cargadas en BD
                        if($array != []){
                            echo "----------------- Empresas -----------------\n";
                        foreach($array as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";
                     }
                
                     break;
                     case 5: echo "Menu principal...";
                     break;
                default:
                        echo "Opcion no identificada\n";
                        break;
            
            }
            
        
        }break;


        case 2: 

            $opt=0;

            //Menu empleado responsable
            while($opt!=5){
                echo "\n------------- Menu Responsable -------------\n";
                echo "\n 1 - Ingresar responsable";
                echo "\n 2 - Modificar datos responsable";
                echo "\n 3 - Eliminar responsable";
                echo "\n 4 - Visualizar datos responsable";
                echo "\n 5 - Volver a menu principal\n";

                $opt=trim(fgets(STDIN));

                $responsable= new ResponsableV();
                $arrayR= $responsable->listar();
                

                switch ($opt) {
                    case 1:
                        //$numEmpleado,$licencia,$nom,$ape
                        echo "Ingrese número de licencia del empleado responsable\n";
                        $licencia=trim(fgets(STDIN));

                        //verifica si hay empleados en listado de responsables 
                        if($arrayR!= null){

                            $i=0;
                            $cantR= count($arrayR);
                            $seguir=true;
                        
                            //Verifica si ya existe un empleado responsable con mismo numero licencia
                             while($i<$cantR && $seguir){
                                if($arrayR[$i]->getLicencia()== $licencia ){
                                     echo "Ya existe un empleado responsable con este numero de licencia\n";
                                    $seguir=false;
                                }
                                $i++;

                             }
                        }
                        //Si no hay datos cargados en BD realiza carga de nuevo responsable
                        if($arrayR== null || $seguir){
                            
                            echo "Ingrese el nombre del empleado responsable\n";
                            $nombre=trim(fgets(STDIN));
                            echo "Ingrese apellido del empleado responsable\n";
                            $apellido=trim(fgets(STDIN));
                            $responsable->cargar(null,$licencia,$nombre,$apellido);
                            $responsable->insertar();
                            echo ("Datos ingresados \n".$responsable->__toString());
            
                        }
                        
                        break;
                    
                    case 2: //Modificar datos

                            //verifica si hay datos responsable
                            if($arrayR!=null){

                                echo "----------------- Responsables -----------------\n";
                                foreach($arrayR as $value){
                                    echo $value;
                                }
        
                                echo "--------------------------------------------\n";
        
                                echo "Ingrese el id del empleado responsable a modificar\n";
                                $idMod=trim(fgets(STDIN));

                                //Busca el obj responsable correspondiente a id ingresado por el usuario
                                if(!($responsable->Buscar($idMod))){
                                    echo "El id ingresado no se encuentra en la BD\n";
                                }else{

                                    

                                    //Si existe el obj responsable realiza la modificacion
                                    //Solicitu de datos
                                    echo "Ingrese nuevo numero de licencia\n";
                                    $licenciaN=trim(fgets(STDIN));
                                    echo "Ingrese el nombre nuevo\n";
                                    $nombreN=trim(fgets(STDIN));
                                    echo "Ingrese nuevo apellido\n";
                                    $ape=trim(fgets(STDIN));
                                    
                                    $existe=false;
                                    foreach ($arrayR as $value) {
                                        if($value->getLicencia() == $licenciaN);{
                                            $existe=true;
                                        }
                                        
                                    }

                                    
                                    if(($existe)){
                                        echo "Ya existe el el numero de licencia ingresado en la BD\n";
                                    }else{
                                        //modificacion de datos
                                        $responsableMod=$responsable->Buscar($idMod);
                                        $responsableMod->cargar($idMod,$licenciaN,$nombreN,$ape);
                            
                                        if($responsableMod->modificar()){
                                        echo "Responsable ha sido modificado exitosamente\n";
                                        }else{
                                            echo "No se ha podido modificar los datos del empleado de id ingresado\n";
                                        }
                                    }
                                }

                            }else{
                                echo "No hay empleados responsables cargados aún\n";
                            }

                    break;
                    
                    case 3: //Elimina empleado responsable

                        //Muestra por pantalla responsables cargados en la BD
                        echo "----------------- Responsables -----------------\n";
                        foreach($arrayR as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";

                        echo "Ingrese el id del empleado responsable a eliminar\n";
                        $idE=trim(fgets(STDIN));

                        //Verifica si el responsable de id ingresado por usuario existe en la BD
                        //Si existe responsable lo elimina
                        $responsableEliminar=$responsable->Buscar($idE);
                        
                        if($responsableEliminar->eliminar()){
                            echo "Empleado responsable eliminado exitosamente\n";
                        }else {
                            echo "No se ha podido eliminar responsable, ya que tiene viajes asignados\nDeberá modificar esto antes de continuar\n";
                        }
                        
                    break;
                    case 4:
                        //Visualizar responsables

                        //Muestra los responsables cargados en la BD
                        if($arrayR != []){
                            echo "----------------- Responsables -----------------\n";
                        foreach($arrayR as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";
                     }

                    break;

                    case 5: echo "Menu principal...";
                    break;

                    default:
                        echo "Opcion no identificada\n";
                        break;
                }
            }
                break;

        case 3:

            $opt=0;

            while($opt!=5){

                echo "\n------------- Menu Viajes -------------\n";
                echo "\n 1 - Ingresar datos nuevo viaje";
                echo "\n 2 - Modificar datos de viaje";
                echo "\n 3 - Eliminar viaje";
                echo "\n 4 - Visualizar datos viaje";
                echo "\n 5 - Volver a menu principal\n";

                $viaje= new Viaje();
                $arrayV= $viaje->listar();

                $opt=trim(fgets(STDIN));

                switch ($opt) {
                    case 1: //Ingreso de viaje
                        
                        //Solicita datos de viaje
                        echo "Ingrese el destino\n";
                        $dest=trim(fgets(STDIN));
                        echo "Ingrese cantidad maxima de pasajeros\n";
                        $cantM= trim(fgets(STDIN));
                        echo "Ingrese importe \n";
                        $importe= trim(fgets(STDIN));

                        $emp=new Empresa();
                        $empresas= $emp->listar();

                        //Verifica que exista informacion de empresas de viajes antes de crear viaje
                        if($empresas==null){
                            echo "Aún no hay informacion de empresas\n
                                Por favor ingrese los datos de la empresa antes de continuar con la carga de viaje\n";
                            
                        }else{

                            //Mostrar empresas disponibles
                            echo "Seleccione la empresa\n";
                            
                            foreach ($empresas as $unaEmpr) {
                                echo $unaEmpr;
                            }

                            //Solicita que elija la empresa de viaje
                            echo "Ingrese el id de la empresa\n";
                            $idEmp= trim(fgets(STDIN));

                            $empresa= $emp->Buscar($idEmp);

                            //Verifica que la seleccion del usuario sea correcta
                            if($empresa ==null){
                                echo "Id empresa seleccionada desconocida\n";
                            }else{
                                
                                //VSolicita info de empleado
                                echo "Seleccione id de empleado responsable\n";
                                $resp= new ResponsableV();
                                $listaR= $resp->listar();

                                //Verifica que existan responsables antes de crear viaje
                                if($listaR== null){
                                    echo "Aún no hay informacion de empleado responsable\n
                                    Por favor ingrese los datos del responsable antes de continuar con la carga de viaje\n";
                                }else{

                                    foreach ($listaR as $responsable) {
                                        echo $responsable;
                                    }
                                    echo "Ingrese el id del responsable\n";
                                    $idResp=trim(fgets(STDIN));
                                    $responsable= $resp->Buscar($idResp);

                                    //Verifica que el numero empleado elegido por el usuario sea correcto
                                    if($responsable==null){
                                        echo "Id de responsable ingresado desconocido\n";
                                    }else{

                                        //Con los datos requeridos verifica si el viaje se repite en destino,cantpasajeros,importe  y empresa
                                        $condicion= "vdestino='{$dest}' and vcantmaxpasajeros={$cantM} and vimporte={$importe} and idempresa={$empresa->getIdEmpresa()}"; 

                                        $confirm="";
                                        $arrayV=$viaje->listar($condicion);

                                        //Si el viaje se repite consulta al usuario si se trata del mismo u otro viaje nuevo
                                        if( $arrayV !=null){
                                            echo "Ya existe un viaje con iguales valores de destino,
                                            empresa,importe y cantidad maxima de pasajeros.\n
                                            Desea continuar y agregar el viaje de todas formas? S/N";
                                            $confirm=trim(fgets(STDIN));
                                            
                                        }
                                        if($viaje->listar($condicion) ==null || $confirm== 's' || $confirm=='S'){
                                        //echo "No se repite";

                                            //Carga el nuevo viaje
                                                $viaje->cargar(null,$dest,$cantM,$empresa,$responsable,$importe);
                                                
                                                if($viaje->insertar()){
                                                    echo "Se cargo viaje exitosamente\n";
                                                }else{
                                                    echo "No se pudo cargar viaje\n";
                                                    echo $viaje->__toString();
                                                }
                                                
                                        }
                                 
                                    }
                                }

                            }
                        }
                                
 
                        
                        break;
                    case 2://Modificar datos de viaje
                            
                        //Muestra viajes disponibles en BD para modificar
                        if($arrayV!=null){

                            echo "----------------- Viajes -----------------\n";
                            foreach($arrayV as $value){
                                echo $value;
                            }
    
                            echo "--------------------------------------------\n";
    
                            echo "Ingrese el id del viaje a modificar\n";
                            $idMod=trim(fgets(STDIN));

                            //Verifica que el id de viaje a modificar, ingresado por el usuario existe en la BD
                            if(!($viaje->Buscar($idMod))){
                                echo "El id ingresado no se encuentra en la BD\n";
                            }else{
    
                                //Solicita nuevos datos
                                echo "Ingrese nuevo destino\n";
                                $destinoN=trim(fgets(STDIN));
                                echo "Ingrese la nueva cantidad maxima de pasajeros\n";
                                $cantN=trim(fgets(STDIN));
                                echo "Ingrese nuevo importe\n";
                                $importeN=trim(fgets(STDIN));
                                
                                $emp=new Empresa();
                                $empresas= $emp->listar();
                                
                                //Muestra empresas disponibles
                                echo "Seleccione la empresa\n";
                            
                                foreach ($empresas as $unaEmpr) {
                                    echo $unaEmpr;
                                }

                                echo "Ingrese el nuevo id de la empresa\n";
                                $idEmp= trim(fgets(STDIN));
                            
                                $empresaN= $emp->Buscar($idEmp);

                                echo "Seleccione el nuevo id de empleado responsable\n";
                                $resp= new ResponsableV();
                                $listaR= $resp->listar();

                                //Muestra empleados responsables cargados
                                foreach ($listaR as $unEmp) {
                                    echo $unEmp;
                                }
                                echo "Ingrese el nuevo id de empleado responsable\n";
                                $numEmp=trim(fgets(STDIN));
                                $empleadoN=$resp->Buscar($numEmp);

                                $viajeMod=$viaje->Buscar($idMod);
                                $viajeMod->cargar($idMod,$destinoN,$cantN,$empresaN,$empleadoN,$importeN);
                    
                                //realiza modificacion del viaje
                                if($viajeMod->modificar()){
                                echo "Los datos del viaje han sido modificados exitosamente\n";
                                }else{
                                    echo "No se ha podido modificar los datos del viaje de id ingresado\n";
                                }
                            }

                        }else{
                            echo "No hay viajes cargados aún\n";
                        }

                        break;
                    case 3://Elimina viajes
                        //Muestra viajes disponibles
                        echo "----------------- Viajes -----------------\n";
                        foreach($arrayV as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";

                        echo "Ingrese el id del viaje a eliminar\n";
                        $idE=trim(fgets(STDIN));

                        $viajeEliminar=$viaje->Buscar($idE);
                        //Elimina viaje y pasajeros del viaje
                        
                        if($viajeEliminar ==null){
                            echo "El ID ingresado no se encuentra en la BD\n";
                        }else{
                            echo "Esta seguro de borrar el viaje y datos pasajeros? S/N \n";
                            $confirm=trim(fgets(STDIN));

                            if($confirm=='s' || $confirm=='S'){

                                if($viajeEliminar->eliminar()){
                                    echo "Viaje eliminado exitosamente\n";
                                }else {
                                    echo "No se ha podido eliminar el viaje\n";
                                }
                            }
                        }
                        break;
                    case 4: //Visualizar datos de viajes

                        //Muestra todos los viajes disponibles
                        if($arrayV != []){
                            echo "----------------- Viajes -----------------\n";
                        foreach($arrayV as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";
                     }else{
                        echo "Aún no hay viajes cargados\n";
                     }
                    break;
                    
                    case 5: echo "Menu principal...";
                    break;
                    
                    default:
                        echo "Opcion ingresada no reconocida\n";
                        break;
                }
            }
            

            
        break;

        case 4:
            $opt=0;
            while($opt!=5){
                
                echo "\n------------- Menu Pasajeros -------------\n";
                echo "\n 1 - Ingresar datos nuevo pasajero";
                echo "\n 2 - Modificar datos de pasajero";
                echo "\n 3 - Eliminar pasajero";
                echo "\n 4 - Visualizar datos pasajeros";
                echo "\n 5 - Volver a menu principal\n";

                
                $pasajero= new Pasajero();

                //$pasajero->__toString();
                $arrayP= $pasajero->listar();
                

                $opt=trim(fgets(STDIN));

                switch ($opt) {
                    case 1://Ingreso de datos de pasajeros

                        echo "Ingrese el numero de documento del pasajero que desea cargar\n";
                        $dni=trim(fgets(STDIN));
                        //$arrayP[]=$pasajero;

                        if($dni!=null){
                        $cantPasajeros=count($arrayP);
                        //Verifica si hay pasajeros cargados
                        if($arrayP != [] ){
                        
                            $seguir=true;
                            $i=0;
                            
                            //Si hay pasajeros, verifica si se repite dni
                            while($i<$cantPasajeros && $seguir){

                                if($arrayP[$i]->getDocumento()== $dni){

                                    echo "Ya existe un pasajero cargado con el numero de documento ingresado\n";
                                    $seguir=false;

                                }
                                $i++;                             
                            }
                            
                        }
                        //Si no hay pasajeros cargados o no existe pasajero con dni ingresado, realiza la carga de datos
                        if( $arrayP ==[] ||$seguir){
                                
                            echo "Ingrese el nombre del pasajero\n";
                            $nom=trim(fgets(STDIN));
                            echo "Ingrese el apellido del pasajero\n";
                            $ape=trim(fgets(STDIN));
                            echo "Ingrese el telefono del pasajero\n";
                            $tel=trim(fgets(STDIN));

                            //Solicita el id de viaje a ingresar de pasajero
                            echo "Deberá seleccionar el id del viaje a asignar: \n";
                            $viaje= new Viaje();
                            $arrayV= $viaje->listar();

                            //verifica que haya viajes cargados a los cuales agregar pasajero
                            if(count($arrayV)==0 ){
                                echo "Aún no hay viajes cargados\nDebe cargar un viaje antes de continuar cargando pasajeros\n";

                            }else{
                                foreach($arrayV as $viaje){
                                echo $viaje;
                            }
                            echo "Ingrese el id del viaje a asignar\n";
                            $idV= trim(fgets(STDIN));

                            //Verifica que id de viaje ingresado por el usuario exista en la BD
                            $objViaje=$viaje->Buscar($idV);

                            if($objViaje== null){
                                echo "Id de viaje desconocido\n";
                            }else{
                            $pasajero->cargar($dni,$nom,$ape,$tel,$objViaje);

                            //Realiza la carga de datos de pasajero del viaje correspondiente
                            if($pasajero->insertar()){
                                echo "Pasajero ingresado exitosamente\n";
                                $pasajero->__toString();
                            }else{
                                echo "No ha sido posible ingresar pasajero\n";
                            }
                            }
                            }
                            
                        }
                    }else{
                        echo "Debe ingresar el documento\n";
                    }
                        break;
                    
                    case 2://Modificar pasajeros

                        //Muestra pasajeros cargados en la BD
                        if($arrayP!=null){

                            echo "----------------- Pasajeros -----------------\n";
                            foreach($arrayP as $value){
                                echo $value."\n";
                            }
    
                            echo "--------------------------------------------\n";
    
                            echo "Ingrese el documento del pasajero que se desea modificar\n";
                            $dniMod=trim(fgets(STDIN));

                            //Verifica que el dni del pasajero a modificar sea correcto
                            if(!($pasajero->Buscar($dniMod))){
                                echo "El id ingresado no se encuentra en la BD\n";
                            }else{
    
                                //Solicita nuevos datos
                                echo "Ingrese nuevo nombre\n";
                                $nombreN=trim(fgets(STDIN));
                                echo "Ingrese nuevo apellido de pasajeros\n";
                                $apeN=trim(fgets(STDIN));
                                echo "Ingrese nuevo telefono\n";
                                $telN=trim(fgets(STDIN));
                                
                                $viajes=new Viaje();
                                $arrayV= $viajes->listar();
                                
                                echo "Seleccione el viaje del pasajero\n";
                            
                                foreach ($arrayV as $unViaje) {
                                    echo $unViaje;
                                }

                                echo "Ingrese el nuevo id del viaje\n";
                                $idV= trim(fgets(STDIN));
                            
                                $viajeN= $viajes->Buscar($idV);
                                echo $viajeN;
                                
                                //Realiza la modificacion de datos del pasajero
                                $pasajeroMod=$pasajero->Buscar($dniMod);
                                $pasajeroMod->cargar($dniMod,$nombreN,$apeN,$telN,$viajeN);
                    
                                if($pasajeroMod->modificar()){
                                echo "Los datos del pasajero han sido modificados exitosamente\n";
                                }else{
                                    echo "No se ha podido modificar los datos del pasajero de dni ingresado\n";
                                }
                            }

                        }else{
                            echo "No hay pasajeros cargados aún\n";
                        }


                            
                    break;
                    case 3: //Eliminar pasajeros
                        echo "----------------- Pasajeros -----------------\n";
                        foreach($arrayP as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";

                        echo "Ingrese el documento del pasajero a eliminar\n";
                        $idE=trim(fgets(STDIN));

                        //Verifica que el dni del pasajero a eliminar se encuentre en la BD
                        $pasajeroEliminar=$pasajero->Buscar($idE);
                        
                        echo "Esta seguro de borrar el pasajero de este viaje? S/N \n";
                        $consfirm=trim(fgets(STDIN));

                        if($confirm=='s' || $confirm=='S'){

                            if($pasajeroEliminar->eliminar()){
                                echo "Pasajero eliminado exitosamente\n";
                            }else {
                                echo "No se ha podido eliminar el pasajero\n";
                            }
                        }
                        
                    break;
                    
                    case 4:
                        
                        //Visualizar datos de pasajeros

                        //Solicita que el usuario elija el viaje del cual se mostrara los pasajeros
                        $viaje= new Viaje();

                        $arrayV= $viaje->listar();
                        if($arrayV!= null){
                        echo "Seleccione el id del viaje para visualizar los pasajeros del mismo\n";
                        if($arrayV != []){
                            echo "----------------- Viajes -----------------\n";
                        foreach($arrayV as $value){
                            echo $value;
                        }

                        echo "--------------------------------------------\n";
                        }
                        echo "Ingrese el id del viaje\n";
                        $idV=trim(fgets(STDIN));

                        //Muestra los datos de los pasajeros del viaje solicitado
                        $viajeBuscado= $viaje->Buscar($idV);
                        echo "Datos de los pasajeros de viaje seleccionado\n";
                        echo $viajeBuscado->mostrarPasajeros(); 
                        //echo $viajeBuscado->__toString();

                    }else{
                        echo "No hay viajes cargados, por lo tanto no hay pasajeros aún\n";
                    }
                    break;
                    
                        
                    case 5: echo "Menu principal...";
                    break;

                    default:
                        echo "Opcion no encontrada\n";
                        break;
                }
            }
            
        break;
        case 0: echo "Saliendo...\n";break;
        default:
            echo "Opcion no reconocida\n";
            break;
 
}
}
