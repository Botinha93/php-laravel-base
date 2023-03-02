<?php

namespace App\Http\Services;


class GenericSingleton{

    private static $instances = [];

    protected function __construct() { 
    }


    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Classe é um singletom");
    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
    public static function __callStatic($name, $arguments)
    {
        return call_user_func(array(Self::getInstance(),$name ), $arguments);
    }

}
?>