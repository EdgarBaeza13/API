<?php

class M_Equipos extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct( \Base::instance()->get('DB'), 'equipo');
    }
    
    
}