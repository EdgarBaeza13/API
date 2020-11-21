<?php

class Clientes_Ctrl
{
    public $M_Cliente = null;

    public function __construct() 

    {
      $this->M_Cliente = new M_Clientes();
    }

    public function crear($f3)
    {
        $this->M_Cliente->set('nombre', $f3->get('POST.nombre'));
        $this->M_Cliente->set('direccion', $f3->get('POST.direccion'));
        $this->M_Cliente->set('telefono', $f3->get('POST.telefono'));
        $this->M_Cliente->set('referencia', $f3->get('POST.referencia'));
        $this->M_Cliente->set('fecha', date("Y-m-d"));
        $this->M_Cliente->save();
        echo json_encode([
            'mensaje' => 'Cliente agregado',
            'info'=> [
                'id' => $this->M_Cliente->get('id_cliente')

            ]
        ]);
        
       
    }

    public function consultar($f3)
    {
        $idcliente= $f3->get('PARAMS.idcliente');
        $this->M_Cliente->load(['id_cliente = ?', $idcliente]);
        $msg= "";
        $item = array();

        if($this->M_Cliente->loaded() > 0){
            $msg = "Cliente encontrado";
            $item = $this->M_Cliente->cast();
        } else {
            $msg = "El Cliente no existe";
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
       $result= $this->M_Cliente->find(['nombre LIKE ?', '%' . $f3->get('POST.texto') . '%']);
       $items= array();
       foreach($result as $cliente){
           $items[] = $cliente->cast();
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
        $idcliente= $f3->get('POST.idcliente');
        $this->M_Cliente->load(['id_cliente = ?', $idcliente]);
        $msg= "";

        if($this->M_Cliente->loaded() > 0){
            $msg = "Cliente eliminado";
            $this->M_Cliente->erase();
        } else {
            $msg = "El Cliente no existe";
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> []
        ]);

    }

    public function actualizar($f3)
    {
        $idcliente= $f3->get('PARAMS.idcliente');
        $this->M_Cliente->load(['id_cliente = ?', $idcliente]);
        $msg= "";
        $info = array();

        if($this->M_Cliente->loaded() > 0){
            $this->M_Cliente->set('nombre', $f3->get('POST.nombre'));
            $this->M_Cliente->set('direccion', $f3->get('POST.direccion'));
            $this->M_Cliente->set('telefono', $f3->get('POST.telefono'));
            $this->M_Cliente->set('referencia', $f3->get('POST.referencia'));
            $this->M_Cliente->set('fecha', $f3->get('POST.fecha'));

            $this->M_Cliente->save();
            $msg = "Cliente actuaizado";
            $info ['id'] = $this->M_Cliente->get('id_cliente');
            
        } else {
            $msg = "El Cliente no existe";
            $info['id']=0;
        }
        echo json_encode([
            'mensaje' => $msg,
            'info'=> $info
        ]);

    }
}