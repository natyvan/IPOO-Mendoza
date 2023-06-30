<?php
include_once "BaseDatos.php";

class Empresa{

    //Atributos
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeoperacion;

    //constructor

    public function __construct(){
        $this->idEmpresa= 0;
        $this->nombre="";
        $this->direccion="";
    }

    //Cargar datos
    public function cargar($idempresa,$nombre,$dir){	
	    $this->setIdEmpresa($idempresa);
		$this->setNombre($nombre);
		$this->setDireccion($dir);
    }

    //Getter y Setter
    
    public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    
    public function setIdEmpresa($id){
        $this->idEmpresa = $id;

    }

    
    public function getNombre() {
        return $this->nombre;
    }

    
    public function setNombre($nombre){
        $this->nombre = $nombre;

    }

   
    public function getDireccion() {
        return $this->direccion;
    }

    
    public function setDireccion($dir){
        $this->direccion = $dir;

    }

    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}


    /**
	 * Recupera los datos de una persona por id
	 * @param int $idempresa
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idempresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idempresa;
		$empresa=null;
	
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){
                   
                    $empresa= new Empresa();
                    $empresa->cargar($idempresa,$row2['enombre'],$row2['edireccion']);
                   
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		

		 return $empresa;
	}	


    
	public static function listar($condicion=""){
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresas="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresas=$consultaEmpresas.' where '.$condicion;
		}
		$consultaEmpresas.=" order by idempresa ";
		
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresas)){				
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
				    $id=$row2['idempresa'];
					/*
					$Nombre=$row2['enombre'];
					$Direccion=$row2['edireccion'];
					
				
					$empresa=new Empresa();
					$empresa->cargar($id,$Nombre,$Direccion);
					*/
					$emp=new Empresa();
					$empresa=$emp->Buscar($id);
					array_push($arregloEmpresa,$empresa);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloEmpresa;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;

		$consultaInsertar="INSERT INTO empresa( enombre, edireccion) 
		VALUES ('".$this->getNombre()."','".$this->getDireccion()."');";
		
		//echo $consultaInsertar;
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdEmpresa($id);
			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		
		$consultaModifica="UPDATE empresa SET enombre='".$this->getNombre()."',edireccion='".$this->getDireccion()."'
         WHERE idempresa=".$this->getIdEmpresa();
		//echo $consultaModifica;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

	public function __toString(){
	    return "\n
		ID Empresa: {$this->getIdEmpresa()}\n
		Nombre Empresa: ".$this->getNombre(). "\n 
		Direccion: ".$this->getDireccion()."\n";
			
	}



}
