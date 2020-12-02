<?php

class Avisos_Ctrl
{
    public $M_Aviso = null;

    public function __construct() 

    {
      $this->M_Aviso = new M_Avisos();
      $id = 0;
    }

    public function consultar($f3)
    {
        $idaviso= $f3->get('PARAMS.idaviso');
        $this->M_Aviso->load(['id_aviso = ?', $idaviso]);
        $msg= "";
        $item = array();

        if($this->M_Aviso->loaded() > 0){
            $msg = "aviso encontrado";
            $item = $this->M_Aviso->cast();
        } else {
            $msg = "El aviso no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> [
                'item' => $item

            ]
        ]);


    }

    public function listado($f3)
    {
       $result= $this->M_Aviso->find(['tipo LIKE ?', '%' . $f3->get('POST.texto') . '%']);
       $items= array();
       foreach($result as $aviso){
           $items[] = $aviso->cast();
       }
       echo json_encode([
        'mensaje' => count($items) > 0 ? '' : 'Aun no hay registros',
        'info'=> [
            'items' => $items,
            'total' => count($items)
        ]
    ]);
        
    }

    public function eliminar($f3)
    {
        $idaviso= $f3->get('POST.idaviso');
        $this->M_Aviso->load(['id_aviso = ?', $idaviso]);
        $msg= "";

        if($this->M_Aviso->loaded() > 0){
            $msg = "aviso eliminado";
            $this->M_Aviso->erase();
        } else {
            $msg = "El aviso no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function actualizar($f3)
    {
        $idaviso= $f3->get('PARAMS.idaviso');
        $this->M_Aviso->load(['id_aviso = ?', $idaviso]);
        $msg= "";
        $info = array();

        if($this->M_Aviso->loaded() > 0){
            $this->M_Aviso->set('id_cliente', $f3->get('POST.id_equipo'));
            $this->M_Aviso->set('fecha', $f3->get('POST.fecha'));
            $this->M_Aviso->set('precio', $f3->get('POST.precio'));
            $this->M_Aviso->set('estado', $f3->get('POST.estado'));

            $this->M_Aviso->save();
            $msg = "aviso actuaizado";
            $info ['id'] = $this->M_Aviso->get('id_aviso');
            
        } else {
            $msg = "El aviso no existe";
            $info['id']=0;
            
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> $info
        ]);

    }

    public function avisos($f3)
    {
        $this->M_Aviso->cliente = 'SELECT nombre FROM cliente WHERE id_cliente= bitacora.id_cliente';
       //$result= $this->M_Bitacora->find(['fechaprox >= NOW() - INTERVAL 2 DAY', $f3->get('POST.fechaprox') ]);
       $result= $this->M_Aviso->find(['fecha  <=  DATE_SUB(NOW(),INTERVAL 6 MONTH)', $f3->get('POST.fecha') ]);
       $items= array();
       foreach($result as $aviso){
           $items[] = $aviso->cast();
       }
       echo json_encode([
        'mensaje' => count($items) > 0 ? '' : 'Aun no hay registros',
        'info'=> [
            'items' => $items,
            'total' => count($items)
        ]
    ]);
        
    }
}