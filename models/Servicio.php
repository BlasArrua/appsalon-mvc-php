<?php 
namespace Model;

class Servicio extends ActiveRecord {
    //db
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id','nombre','precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){
        $this -> id = $args['id'] ?? null;
        $this -> nombre = $args['nombre'] ?? '';
        $this -> precio = $args['precio'] ?? '';
    }

    public function validar(){
        if(!$this->nombre){self::$alertas['error'][]='Nombre Obligatorio';}
        if(!$this->precio){self::$alertas['error'][]='Precio Obligatorio';}
        if(!is_numeric($this->precio)){self::$alertas['error'][]='Formato NO VALIDO de precio';}
        return self::$alertas;
    }
}