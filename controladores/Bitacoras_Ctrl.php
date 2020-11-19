<?php

class Bitacoras_Ctrl
{
    public $M_Bitacora = null;
    public $M_Bitacora_Servcio = null;

    public function __construct() 

    {
      $this->M_Bitacora = new M_Bitacoras();
      $this->M_Bitacora_Servicio = new M_Bitacora_Servicio();
    }

    public function crear($f3)
    {
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime ( '+6 month' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $this->M_Bitacora->set('id_cliente', $f3->get('POST.id_cliente'));
        $this->M_Bitacora->set('id_equipo', $f3->get('POST.id_equipo'));
        $this->M_Bitacora->set('id_servicio', $f3->get('POST.id_servicio'));
        $this->M_Bitacora->set('fecha', date("Y-m-d"));
        $this->M_Bitacora->set('fechaprox', $nuevafecha);
        $this->M_Bitacora->set('diagnostico', $f3->get('POST.diagnostico'));
        $this->M_Bitacora->set('precio', $f3->get('POST.precio'));
        $this->M_Bitacora->save();
        echo json_encode([
            'mensaje' => 'Bitacora creada',
            'info'=> [
                'id' => $this->M_Bitacora->get('id_bitacora')

            ]
        ]);
        
       
    }

    public function borrar_servicio($f3)
    {
        $this->M_Bitacora->load(['id_bitacora = ?', $f3->get('PARAMS.idbitacora')]);
        if ($this->M_Bitacora->loaded() > 0){
            $this->M_Bitacora_Servicio->load(['bitacora_id_bitacora = ? AND id = ?', $f3->get('PARAMS.idbitacora'), $f3->get('POST.item_id')]);

            if($this->M_Bitacora_Servicio->loaded() > 0) {
                $this->M_Bitacora_Servicio->erase();
                echo json_encode([
                    'mensaje' => 'Servicio borrado',
                    'info'=> null
                ]);
            } else {
                echo json_encode([
                    'mensaje' => 'No pudo ser borrado',
                    'info'=> null
                ]);
            } 
        } else {
            echo json_encode([
                'mensaje' => 'La bitacora no existe',
                'info'=> []
            ]);
        }
    }

    public function agregar_servicio($f3)
    {
        $this->M_Bitacora->load(['id_bitacora = ?', $f3->get('PARAMS.idbitacora')]);
        if ($this->M_Bitacora->loaded() > 0){
            $this->M_Bitacora_Servicio->load(['bitacora_id_bitacora = ? AND servicio_id_servicio = ?', $f3->get('PARAMS.idbitacora'), $f3->get('POST.servicio_id_servicio')]);

            $existe = $this->M_Bitacora_Servicio->loaded() > 0;

            $this->M_Bitacora_Servicio->set('bitacora_id_bitacora', $f3->get('PARAMS.idbitacora'));
            $this->M_Bitacora_Servicio->set('servicio_id_servicio', $f3->get('POST.servicio_id_servicio'));
            $this->M_Bitacora_Servicio->set('precio', $f3->get('POST.precio'));
            if(!$existe) {
                $this->M_Bitacora_Servicio->save();
            } else {
                $this->M_Bitacora_Servicio->update();
            }
           
            echo json_encode([
                'mensaje' => 'Servicio agregado',
                'info'=> [
                    'id' => $this->M_Bitacora_Servicio->get('id')

                ]
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'La bitacora no existe',
                'info'=> []
            ]);
        }
    }

    public function consultar($f3)
    {
        $idbitacora= $f3->get('PARAMS.idbitacora');
        $this->M_Bitacora->load(['id_bitacora = ?', $idbitacora]);
        $msg= "";
        $item = array();

        if($this->M_Bitacora->loaded() > 0){
            $msg = "bitacora encontrada";
            $item = $this->M_Bitacora->cast();
            $this->M_Bitacora_Servicio->tipo = 'SELECT tipo FROM servicio WHERE id_servicio = bitacora_servicio.servicio_id_servicio';
            $result= $this->M_Bitacora_Servicio->find(['bitacora_id_bitacora = ?', $this->M_Bitacora->get('id_bitacora')]);
            $item['items'] = array();
            foreach($result as $r) {
                $item['items'][]= $r->cast();
            }
        } else {
            $msg = "La bitacora no existe";
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
        $this->M_Bitacora->cliente = 'SELECT nombre FROM cliente WHERE id_cliente= bitacora.id_cliente';
       $result= $this->M_Bitacora->find();
       $items= array();
       foreach($result as $bitacora){
           $items[] = $bitacora->cast();
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
        $idbitacora= $f3->get('POST.idbitacora');
        $this->M_Bitacora->load(['id_bitacora = ?', $idbitacora]);
        $msg= "";

        if($this->M_Bitacora->loaded() > 0){
            $msg = "bitacora eliminado";
            $this->M_Bitacora->erase();
        } else {
            $msg = "El bitacora no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function actualizar($f3)
    {
        $idbitacora= $f3->get('PARAMS.idbitacora');
        $this->M_Bitacora->load(['id_bitacora = ?', $idbitacora]);
        $msg= "";
        $info = array();

        if($this->M_Bitacora->loaded() > 0){
            $this->M_Bitacora->set('id_cliente', $f3->get('POST.id_cliente'));
            $this->M_Bitacora->set('id_equipo', $f3->get('POST.id_equipo'));
            $this->M_Bitacora->set('id_servicio', $f3->get('POST.id_servicio'));
            $this->M_Bitacora->set('fecha', $f3->get('POST.fecha'));
            $this->M_Bitacora->set('diagnostico', $f3->get('POST.diagnostico'));
            $this->M_Bitacora->set('precio', $f3->get('POST.precio'));

            $this->M_Bitacora->save();
            $msg = "bitacora actualizada actuaizada";
            $info ['id'] = $this->M_Bitacora->get('id_bitacora');
            
        } else {
            $msg = "La bitacora no existe";
            $info['id']=0;
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function reporte($f3)
    {
        $f1= $f3->get('POST.fechainicio');
        $f2= $f3->get('POST.fechafin');
        $this->M_Bitacora->cliente = 'SELECT nombre FROM cliente WHERE id_cliente= bitacora.id_cliente';
       $result= $this->M_Bitacora->find(['fecha BETWEEN ? AND ?', $f1, $f2]);
       $items= array();
       foreach($result as $bitacora){
           $items[] = $bitacora->cast();
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