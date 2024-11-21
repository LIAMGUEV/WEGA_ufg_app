<?php
require("conn.class.php");
require("validaciones.inc.php");

class Persona{
    public $idpersona;
    public $nombres;
    public $apellidos;
    public $fnac;
    public $telefono;
    public $email;
    public $conexion;
    public $validaciones;

    public function __construct(){
        $this->conexion = new DB();
        $this->validaciones = new Validaciones();
    }

    public function setIdPersona($idpersona){
        $this->idpersona = intval($idpersona);
    }

    public function getIdPersona($idpersona){
        return = intval($this->idpersona);
    }

    public function setNombres($nombres){
        $this->nombres = $nombres;
    }

    public function getNombres(){
        return $this->nombres;
    }

    public function setApellidos($apellidos){
        $this->setApellidos = $apellidos;
    }

    public function getApellidos(){
        return $this->apellidos;
    }

    public function setFnac($fnac){
        $this->fnac = $fnac;
    }

    public function getFnac(){
        return $this->fnac;
    }

    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    public function getTelefono(){
        return $this->telefono;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    /**fin de los getters y setters */

    /**inicio de los metodos para procesamiento de datos */

    public function obtenerPersona(int $idpersona){
        if($idpersona > 0){
            $resultado = $this->conexion->run('SELECT * FROM persona WHERE id_persona=' . $idpersona);
            $array = array("mensaje"=>"Registros encontrados","valores"=>$resultado->fetch());
            return $array;
        }else{
            $array = array("mensaje"=>"No se pudo ejecutar la consulta, el parametro ID es incorrecto","valores"=>"");
        }
    }

    public function nuevapersona($nombres,$apellidos,$fnac,$telefono,$email){
        $bandera_validacion = 0;

        if($this->validacion::verificar_solo_letras(trim($nombres),true)){
            $this->setNombres($nombres);
        }else{
            $bandera_validaciones++;
        }

        if($this->validacion::verificar_solo_letras(trim($apellidos),true)){
            $this->setApellidos($apellidos);
        }else{
            $bandera_validacion++;
        }

        if($this->validacion::verificar_fecha($fnac,"Y-m-d")){
            $this->setFnac($fnac);
        }else{
            $bandera_validacion++;
        }

        if($this->validacion::validar_telefono(trim($telefono))){
            $this->setTelefono($telefono);
        }else{
            $bandera_validacion++;
        }

        if($this->validacion::validar_email($email)){
            $this->setEmail($email);
        }else{
            $bandera_validacion++;
        }

        if($bandera_validacion === 0){
            $parametros = array (
                "nom" => $this->getNombres(),
                "ape" => $this->getApellidos(),
                "fnac" => $this->getFnac(),
                "email" => $this->getEmail()
            );
            $resultado = $this->conexion->run('INSERT INTO persona(nombres,apellidos,fnac,telefono,email) VALUES (:nom,ape,:tel,:email);' ,$parametros);
            if($this->conexion->n > 0 and $this->conexion->id > 0){

                $resultado = $this->obtenerpersona($this->conexion->id);
                $array = array("mensaje"=>"se ha registrado la persona correctamente","valores"=>$resultado);
                return $array;
            }else{
                $array = array("mensaje"=>"hubo un problema al registrar la persona","valores"=>"");
                return $array;
            }
        }else{
            $array = array("mensaje"=>"existe al menos un campo obligatorio que no se ha enviado","valores"=>"");
                return $array;
        }
    }

}
?>