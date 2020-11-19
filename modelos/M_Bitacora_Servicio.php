<?php

class M_Bitacora_Servicio extends \DB\SQL\Mapper 
{
    public function __construct()
    {
        parent::__construct( \Base::instance()->get('DB'), 'bitacora_servicio');
    }
    
    
}