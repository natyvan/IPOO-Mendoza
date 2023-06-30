<?php
include_once "BaseDatos.php";

class ResponsableV{
    //atributos

    private $numeroEmpleado;
    private $licencia;
    private $nombre;
    private $apellido;
    private $mensajeConsulta;

    //Constructor
    public function __construct(){
        $this->numeroEmpleado=0;
        $this->licencia="";
        $this->nombre="";
        $this->apellido="";
    }

    public function cargar($numEmpleado,$licencia,$nom,$ape){	
	    $this->setNumeroEmpleado($numEmpleado);
		$this->setLicencia($licencia);
		$this->setNombre($nom);
		$this->setApellido($ape);
    }

    public function getNumeroEmpleado() {
        return $this->numeroEmpleado;
    }

   
    public function setNumeroEmpleado($numero) {
        $this->numeroEmpleado = $numero;
    }

    
    public function getLicencia() {
        return $this->licencia;
    }

   
    public function setLicencia($licencia) {
        $this->licencia = $licencia;
    }

  
    public function getNombre() {
        return $this->nombre;
    }

    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    
    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    
    public function getMensajeConsulta() {
        return $this->mensajeConsulta;
    }

 
    public function setMensajeConsulta($mensajeConsulta) {
        $this->mensajeConsulta = $mensajeConsulta;
    }
    

    //$numEmpleado,$licencia,$nom,$ape
    public function __toString(){
        return ("\n
		Número de empleado: {$this->getNumeroEmpleado()}\n
		Numero de licencia: {$this->getLicencia()}\n
		Nombre: {$this->getNombre()}\n
		Apellido: {$this->getApellido()}\n");
    }


    public function Buscar($numeroE){
		$base=new BaseDatos();
		$consultaResponsable="Select * from responsable where rnumeroempleado=".$numeroE;
		//$resp= false;
		$responsable=null;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){
				if($row2=$base->Registro()){
                   
					$responsable= new ResponsableV();
                    $responsable->cargar($numeroE,$row2['rnumerolicencia'],$row2['rnombre'],$row2['rapellido']);
                    //$resp= true;
				}				
			
		 	}	else {
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }		
		 return $responsable;
	}	

    public static function listar($condicion=""){
	    $arregloResponsable = null;
		$base=new BaseDatos();
		$consultaResponsables="Select * from responsable ";
		if ($condicion!=""){
		    $consultaResponsables=$consultaResponsables.' where '.$condicion;
		}
		$consultaResponsables.=" order by rnumeroempleado ";
		//echo $consultaPersonas;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsables)){				
				$arregloResponsable= array();
				while($row2=$base->Registro()){
				    $numeroE=$row2['rnumeroempleado'];
					/*
					$licencia=$row2['rnumerolicencia'];
					$nombre=$row2['rnombre'];
					$apellido=$row2['rapellido'];
					
					$responsable=new ResponsableV();
					$responsable->cargar($numeroE,$licencia,$nombre,$apellido);
					*/
					$num=new ResponsableV();
					$responsable=$num->Buscar($numeroE);
					array_push($arregloResponsable,$responsable);
	
				}
				
			
		 	}	else {
		 			$this->setMensajeConsulta($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeConsulta($base->getError());
		 	
		 }	
		 return $arregloResponsable;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsable(rnumerolicencia, rnombre,rapellido) 
				VALUES (".$this->getLicencia().",'".$this->getNombre()."','".$this->getApellido()."')";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setNumeroEmpleado($id);
			    $resp=  true;

			}	else {
					$this->setMensajeConsulta($base->getError());
					
			}

		} else {
				$this->setMensajeConsulta($base->getError());
			
		}
		return $resp;
	}

    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		
		$consultaModifica="UPDATE responsable SET rnumerolicencia='".$this->getLicencia()."',rnombre='".$this->getNombre()."'
                           ,rapellido='". $this->getApellido()."' WHERE rnumeroempleado=".$this->getNumeroEmpleado();
		
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
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNumeroEmpleado();
				//echo $consultaBorra;
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