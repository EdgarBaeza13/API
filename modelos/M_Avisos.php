<?php

class M_Avisos extends \DB\SQL\Mapper 
{
    public function __construct()
    {
        parent::__construct( \Base::instance()->get('DB'), 'aviso');
    }
    
    
}