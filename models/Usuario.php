<?php 
namespace Model;
class Usuario extends ActiveRecord{
    // db 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args=[]){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //mensajes de validacion para la creacion de la cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){self::$alertas['error'][]='Nombre Obligatorio';}
        if(!$this->apellido){self::$alertas['error'][]='Apellido Obligatorio';}
        if(!$this->email){self::$alertas['error'][]='Email Obligatorio';}
        if(!$this->password){self::$alertas['error'][]='Password Obligatorio';}
        if(!$this->telefono){self::$alertas['error'][]='Telefono Obligatorio';}
        if(strlen($this->password) < 6){self::$alertas['error'][]='El password debe contener al menos 6 caracteres';}
        if(strlen($this->telefono) != 10 ){self::$alertas['error'][]='El telefono debe contener 10 caracteres';}
        return self::$alertas;
    }
    
    public function validarLogin(){
       if(!$this->email){self::$alertas['error'][]='Mail Obligatorio';} 
       if(!$this->password){self::$alertas['error'][]='Password Obligatorio';} 
       return self::$alertas;
    }


    //validar para recuperar password
    public function validarEmail(){
        if(!$this->email){self::$alertas['error'][]='Mail Obligatorio';} 
        return self::$alertas;
    }


    public function validarPassword(){
        if(!$this->password){self::$alertas['error'][]='Password Obligatorio';}
        if(strlen($this->password) < 6){self::$alertas['error'][]='El password debe contener al menos 6 caracteres';}
        return self::$alertas;
    }

    //revisa si existe el usuario
    public function existeUsuario(){
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" .$this->email. "' LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado->num_rows){self::$alertas['error'][] = 'El usuario ya esta registrado';}
        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado){self::$alertas['error'][]='Password INCORRECTO o tu cuenta NO FUE CONFIRMADA';}
        else{return true;}
    }
}
