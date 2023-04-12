<?php

namespace Model;

class Servicio extends ActiveRecord {
    // BBDD configuracion
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
    //Validación nombre y precio
    public function validar(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de servicio es obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][] = 'El precio de servicio es obligatorio';
        }
        if(!is_numeric($this->precio)){
            self::$alertas['error'][] = 'Formato de precio no válido';
        }

        return self::$alertas;
    }
}