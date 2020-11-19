<?php

class Inicio_Ctrl

{
    public function Obtener_Totales($f3)
    {
        $M_Cliente = new M_Clientes();
        $M_Equipo = new M_Equipos();
        $M_Servicio = new M_Servicios();
        $M_Bitacora = new M_Bitacoras();

        echo json_encode([
            'mensaje' => '',
            'info'=> [
                'clientes' =>  $M_Cliente->count(),
                'equipos' => $M_Equipo->count(),
                'servicios' => $M_Servicio->count(),
                'bitacoras' => $M_Bitacora->count(),
    
            ]
        ]);
    }
}