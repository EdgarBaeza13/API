<?php

class Equipos_Ctrl
{
    public $M_Equipo = null;

    public function __construct() 

    {
      $this->M_Equipo = new M_Equipos();
      $id = 0;
    }

    public function crear($f3)
    {
        $this->M_Equipo->set('tipo', $f3->get('POST.tipo'));
        $this->M_Equipo->set('capacidad', $f3->get('POST.capacidad'));
        $this->M_Equipo->set('tecnologia', $f3->get('POST.tecnologia'));
        $this->M_Equipo->set('marca', $f3->get('POST.marca'));
        $this->M_Equipo->set('ubicacion', $f3->get('POST.ubicacion'));
        $this->M_Equipo->set('fecha', date("Y-d-m"));
        $this->M_Equipo->set('cliente', $f3->get('POST.cliente'));
        $this->M_Equipo->save();
        $id= $this->M_Equipo->get('id_equipo');
        echo json_encode([
            'mensaje' => 'Equipo agregado',
            'info'=> [
                'id' => $id

            ]
        ]);
        
       
    }

    public function consultar($f3)
    {
        $idequipo= $f3->get('PARAMS.idequipo');
        $this->M_Equipo->load(['id_equipo = ?', $idequipo]);
        $msg= "";
        $item = array();

        if($this->M_Equipo->loaded() > 0){
            $msg = "Equipo encontrado";
            $item = $this->M_Equipo->cast();
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
       $result= $this->M_Equipo->find(['tipo LIKE ?', '%' . $f3->get('POST.texto') . '%']);
       $items= array();
       foreach($result as $equipo){
           $items[] = $equipo->cast();
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
        $idequipo= $f3->get('POST.idequipo');
        $this->M_Equipo->load(['id_equipo = ?', $idequipo]);
        $msg= "";

        if($this->M_Equipo->loaded() > 0){
            $msg = "Equipo eliminado";
            $this->M_Equipo->erase();
        } else {
            $msg = "El Equipo no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function actualizar($f3)
    {
        $idequipo= $f3->get('PARAMS.idequipo');
        $this->M_Equipo->load(['id_equipo = ?', $idequipo]);
        $msg= "";
        $info = array();

        if($this->M_Equipo->loaded() > 0){
            $this->M_Equipo->set('tipo', $f3->get('POST.tipo'));
            $this->M_Equipo->set('capacidad', $f3->get('POST.capacidad'));
            $this->M_Equipo->set('tecnologia', $f3->get('POST.tecnologia'));
            $this->M_Equipo->set('marca', $f3->get('POST.marca'));
            $this->M_Equipo->set('ubicacion', $f3->get('POST.ubicacion'));
            $this->M_Equipo->set('fecha', $f3->get('POST.fecha'));
            $this->M_Equipo->set('cliente', $f3->get('POST.cliente'));

            $this->M_Equipo->save();
            $msg = "Equipo actuaizado";
            $info ['id'] = $this->M_Equipo->get('id_equipo');
            
        } else {
            $msg = "El Equipo no existe";
            $info['id']=0;
            
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> $info
        ]);

    }
}