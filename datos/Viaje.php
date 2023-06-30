<?php
include_once "BaseDatos.php";

class Viaje{
    //atributos
    
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $importe;
    private $colPasajeros;
    private $mensajeConsulta;

    //Constructor
    public function __construct(){
        $this->idViaje= 0;
        $this->destino= "";
        $this->cantMaxPasajeros="";
        $this->objEmpresa=new Empresa();
        $this->objResponsable=new ResponsableV();
        $this->importe="";
        $this->colPasajeros=[];
    }

    public function cargar($id,$destino,$cantPasajeros,$empresa,$responsable,$importe){
        $this->setIdViaje($id);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantPasajeros);
        $this->setObjEmpresa($empresa);
        $this->setObjResponsable($responsable);
        $this->setImporte($importe);
    }


    //Getter y Setter

    public function getIdViaje() {
        return $this->idViaje;
    }

    public function setIdViaje($id) {
        $this->idViaje = $id;
    }

 
    public function getDestino() {
        return $this->destino;
    }


    public function setDestino($destino) {
        $this->destino = $destino;
    }


    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }


    public function setCantMaxPasajeros($cantPasajeros) {
        $this->cantMaxPasajeros = $cantPasajeros;
    }

  
    public function getObjEmpresa() {
        return $this->objEmpresa;
    }

   
    public function setObjEmpresa($objEmpresa) {
        $this->objEmpresa = $objEmpresa;
    }

   
    public function getObjResponsable() {
        return $this->objResponsable;
    }

    public function setObjResponsable($objResponsable) {
        $this->objResponsable = $objResponsable;
    }

    
    public function getImporte() {
        return $this->importe;
    }

   
    public function setImporte($importe) {
        $this->importe = $importe;
    }

   
    public function getColPasajeros() {
        return $this->colPasajeros;
    }

 
    public function setColPasajeros($pasajeros) {
        $this->colPasajeros = $pasajeros;
    }

   
    public function getMensajeConsulta() {
        return $this->mensajeConsulta;
    }

    public function setMensajeConsulta($mensajeConsulta) {
        $this->mensajeConsulta = $mensajeConsulta;
    }


    /**
     * Busca la información de pasajeros en clase pasajeros
     * Guarda el array obtenido en el atributo @colPasajeros
     *
     * 
     * @return string
     */
    public function mostrarPasajeros(){
        $texto="\n";

        $pasajerosViaje=new Pasajero();

        $condicion= "idviaje='{$this->getIdViaje()}'";
        $listaPasajeros=$pasajerosViaje->listar($condicion);
        $this->setColPasajeros($listaPasajeros);
        //$pasajeros=$this->getColPasajeros();
        $cantPasajeros= count($listaPasajeros);

        if($cantPasajeros==0){
            $texto.="Aún no hay pasajeros cargados en este viaje\n";
        }else{

            for($i=0;$i<$cantPasajeros;$i++){

                $texto.= "Pasajero {$i}"; 
                $texto.= $listaPasajeros[$i]." \n";
            }
        }
        
        return $texto;
    }

    /**
     * Muestra la informacion del viaje
     * @return string
     */
    public function __toString(){
        return ("----------- Datos Viaje ---------- \n
         Id Viaje: {$this->getIdViaje()} \n
         Destino: {$this->getDestino()} \n
         Cantidad Maxima Pasajeros: {$this->getCantMaxPasajeros()}\n
         Importe: {$this->getImporte()}
          ------ Datos Empresa ------
         {$this->getObjEmpresa()}\n
          ------ Datos Responsable ------
         {$this->getObjResponsable()}\n");
        
        /* \n ---------- Datos Pasajeros ----------\n
         {$this->mostrarPasajeros()}\n
         ");*/
    }

    /**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la consulta en un valor 
     * Realiza la consulta y guarda los datos en un objeto
     *
     * @param string $id
     * @return ObjetoViaje
     */
    public function Buscar($id){

		$base=new BaseDatos();
		$consultaViaje="Select * from viaje where idviaje=".$id;
		
		$viaje= null;

        if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){
                 
                    $idViaje= $id;
                    $destino= $row2['vdestino'];
                    $cantPasajeros=$row2['vcantmaxpasajeros'];
                    $importe=$row2['vimporte'];

                    $objEmpresa= new Empresa();
                    $emp=$objEmpresa->Buscar($row2['idempresa']);

                    $objResponsable= new ResponsableV();
                    $objR=$objResponsable->Buscar($row2['rnumeroempleado']);


                    $viaje= new Viaje();
                    $viaje->cargar($id,$destino,$cantPasajeros,$emp,$objR,$importe);
                
				}				
			
		 	}	else {
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }		
	
        return $viaje;
	}	

    /**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la condicion de consulta en una cadena enviada por parametro.
     *
     * @param string $consulta
     * @return array
     */
    public static function listar($condicion=""){
	    $arregloViaje = null;
		$base=new BaseDatos();
		$consultaViajes="Select * from viaje ";

		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by idviaje ";

		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViaje= array();
				while($row2=$base->Registro()){

				    $id=$row2['idviaje'];
                    
                    $vi= new Viaje();
                    $viaje=$vi->Buscar($id);
                    
					array_push($arregloViaje,$viaje);
	
				}
				
			
		 	}	else {
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }	
		 return $arregloViaje;
	}	

    /**
     * Ejecuta una consulta en la Base de Datos.
     * 
     * @return boolean
     */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;

        $consultaInsertar="INSERT INTO viaje(vdestino,vcantmaxpasajeros,idempresa,rnumeroempleado,vimporte)
        VALUES ('{$this->getDestino()}','{$this->getCantMaxPasajeros()}','{$this->getObjEmpresa()->getIdEmpresa()}',
        '{$this->getObjResponsable()->getNumeroEmpleado()}','{$this->getImporte()}')";
		
       // echo $consultaInsertar;
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdViaje($id);
			    $resp=  true;

			}	else {
					$this->setMensajeConsulta($base->getError());
					
			}

		} else {
				$this->setMensajeConsulta($base->getError());
			
		}
		return $resp;
	}



    /**
     * Ejecuta una consulta en la Base de Datos..
     *
     * @return boolean
     */
    public function modificar(){

	    $resp =false; 
	    $base=new BaseDatos();

        $consultaModifica="UPDATE viaje SET vdestino='{$this->getDestino()}',vcantmaxpasajeros='{$this->getCantMaxPasajeros()}',
        idempresa='{$this->getObjEmpresa()->getIdEmpresa()}',rnumeroempleado='{$this->getObjResponsable()->getNumeroEmpleado()}',
        vimporte='{$this->getImporte()}' WHERE idviaje='{$this->getIdViaje()}'";

		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setMensajeConsulta($base->getError());
				
			}
		}else{
				$this->setMensajeConsulta($base->getError());
			
		}
		return $resp;
	}

/**
     * Ejecuta una consulta en la Base de Datos.
     *
     * 
     * @return boolean
     */
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;

		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setMensajeConsulta($base->getError());
					
				}
		}else{
				$this->setMensajeConsulta($base->getError());
			
		}
		return $resp; 
	}
	

}