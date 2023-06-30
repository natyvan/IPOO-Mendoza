<?php
include_once "BaseDatos.php";

class Pasajero{
    //atributos

    private $documento;
    private $nombre;
    private $apellido;
    private $telefono;
    private $objViaje;
    private $mensajeConsulta;
    
    //constructor
    public function __construct(){
        $this->documento=0;
        $this->nombre="";
        $this->apellido="";
        $this->telefono="";
        $this->objViaje= new Viaje();
    }

    public function cargar($dni,$nombre,$ape,$tel,$objViaje){	
	    $this->setDocumento($dni);
		$this->setNombre($nombre);
		$this->setApellido($ape);
		$this->setTelefono($tel);
		$this->setObjViaje($objViaje);
    }

 
    //Getter y Setter

    public function getDocumento() {
        return $this->documento;
    }

   
    public function setDocumento($dni){
        $this->documento = $dni;
    }

    
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

   
    public function getApellido() {
        return $this->apellido;
    }

  
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

  
    public function getObjViaje() {
        return $this->objViaje;
    }

   
    public function setObjViaje($idviaje){
        $this->objViaje = $idviaje;
    }


    public function getMensajeConsulta() {
        return $this->mensajeConsulta;
    }


    public function setMensajeConsulta($mensaje){
        $this->mensajeConsulta = $mensaje;

    }

    
    public function __toString(){
        return ("
		Documento: {$this->getDocumento()} \n
		Nombre: {$this->getNombre()} \n
		Apellido: {$this->getApellido()} \n
		Telefono: {$this->getTelefono()} \n
		Id Viaje: {$this->getObjViaje()->getIdViaje()}\n");

    }

   
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaPasajero="Select * from pasajero where pdocumento=".$dni;
		//$resp= false;

        $pasajero=null;

		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){
				if($row2=$base->Registro()){
                 
                    $objViaje= new Viaje();
                    $objV=$objViaje->Buscar($row2['idviaje']);
                    $pasajero= new Pasajero();
                    $pasajero->cargar($dni,$row2['pnombre'],$row2['papellido'],$row2['ptelefono'],$objV);
                    //$resp= true;
				}				
			
		 	}	else {
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }		
		 //return $resp;
         return $pasajero;
	}	

    public static function listar($condicion=""){
	    $arregloPasajero = null;
		$base=new BaseDatos();
		$consultaPasajeros="Select * from pasajero ";
		if ($condicion!=""){
		    $consultaPasajeros=$consultaPasajeros.' where '.$condicion;
		}
		$consultaPasajeros.=" order by pdocumento ";
	
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajeros)){				
				$arregloPasajero= array();
				while($row2=$base->Registro()){
				   
                    $dni=$row2['pdocumento'];
					/*
					$nombre=$row2['pnombre'];
					$apellido=$row2['papellido'];
					$telefono=$row2['ptelefono'];
					
                    $objViaje= new Viaje();
                    $objV=$objViaje->Buscar($row2['idviaje']);
                    
				
					$pasajero=new Pasajero();
					$pasajero->cargar($dni,$nombre,$apellido,$telefono,$objV);
					*/
					$pasa=new Pasajero();
					$pasajero=$pasa->Buscar($dni);
					array_push($arregloPasajero,$pasajero);
	
				}
				
			
		 	}	else {
				
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }	
		 return $arregloPasajero;
	}	

    
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;

		$consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido,ptelefono,idviaje) 
				VALUES (".$this->getDocumento().",'".$this->getNombre()."','".$this->getApellido()."','".$this->getTelefono()."','".$this->getObjViaje()->getIdViaje()."')";
		
		if($base->Iniciar()){

            $resp=  true;
            if($base->Ejecutar($consultaInsertar)){
			//if($id = $base->devuelveIDInsercion($consultaInsertar)){
            //    $this->setIdPasajero($id);
			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

            
		} else {
				$this->setMensajeConsulta($base->getError());
			
		}
		return $resp;
	}

   

    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
        
        $consultaModifica="UPDATE pasajero SET pnombre='{$this->getNombre()}',papellido='{$this->getApellido()}',
        ptelefono='{$this->getTelefono()}',idviaje='{$this->getObjViaje()->getIdViaje()}' WHERE pdocumento='{$this->getDocumento()}' ";
		//echo $consultaModifica;
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
	

    
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;

		if($base->Iniciar()){
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getDocumento();
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