<?php

class Servicios_Ctrl
{

    public $M_Servicio = null;

    public function __construct() 

    {
      $this->M_Servicio = new M_Servicios();
    }

    public function crear($f3)
    {
        $this->M_Servicio->set('tipo', $f3->get('POST.tipo'));
        $this->M_Servicio->set('descripcion', $f3->get('POST.descripcion'));
        $this->M_Servicio->save();
        echo json_encode([
            'mensaje' => 'Servicio agregado',
            'info'=> [
                'id' => $this->M_Servicio->get('id_servicio')

            ]
        ]);
        
       
    }

    public function consultar($f3)
    {
        $idservicio= $f3->get('PARAMS.idservicio');
        $this->M_Servicio->load(['id_servicio = ?', $idservicio]);
        $msg= "";
        $item = array();

        if($this->M_Servicio->loaded() > 0){
            $msg = "Equipo encontrado";
            $item = $this->M_Servicio->cast();
        } else {
            $msg = "El Equipo no existe";
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
       $result= $this->M_Servicio->find(['tipo LIKE ?', '%' . $f3->get('POST.texto') . '%']);
       $items= array();
       foreach($result as $servicio){
           $items[] = $servicio->cast();
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
        $idservicio= $f3->get('POST.idservicio');
        $this->M_Servicio->load(['id_servicio = ?', $idservicio]);
        $msg= "";

        if($this->M_Servicio->loaded() > 0){
            $msg = "Servicio eliminado";
            $this->M_Servicio->erase();
        } else {
            $msg = "El Servicio no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function actualizar($f3)
    {
        $idservicio= $f3->get('PARAMS.idservicio');
        $this->M_Servicio->load(['id_servicio = ?', $idservicio]);
        $msg= "";
        $info = array();

        if($this->M_Equipo->loaded() > 0){
            $this->M_Servicio->set('tipo', $f3->get('POST.tipo'));
            $this->M_Servicio->set('descripcion', $f3->get('POST.descripcion'));

            $this->M_Servicio->save();
            $msg = "Servicio actuaizado";
            $info ['id'] = $this->M_Servicio->get('id_bitacora');
            
        } else {
            $msg = "El servicio no existe";
            $info['id']=0;
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }
    
}