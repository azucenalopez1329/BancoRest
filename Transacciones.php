<?php namespace App\Controllers\API;

use APP\Models\ClienteModel;
use App\Models\CuentaModel;
use App\Models\TransaccionModel;
use CodeIgniter\RESTful\ResourceController;

class Transacciones extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new TransaccionModel());
    }

    public function index()
    {
        $transacciones = $this->model->findAll();
        return $this->respond($transacciones);
    }

    public function create()
    {
        try {
            
            $transaccion = $this->request->getJSON();
            if($this->model->insert($transaccion)):
                $transaccion->id=$this->model->insertID();
                $transaccion->resultado=$this->model->actualizarFondoCuenta($transaccion->tipo_transaccion_id, $transaccion);
                return $this->respondCreated($transaccion);
            else:
                return  $this->failValidationError($this->model->validation->listErrors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function edit($id = null)
    {
        try {

            if($id == null)
            return $this->failValidationError('No se ha pasado un Id valido');

            $transaccion = $this->model->find($id);
            if($transaccion == null)
                return $this->failNotFound('No se ha encontrado una transaccion con el id: '.$id);

            return $this->respond($transaccion);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function update($id = null)
    {
        try {
            if($id == null)
               return $this->failValidationError('No se ha pasado un Id valido');

            $transaccionVerificada = $this->model->find($id);
            if($transaccionVerificada == null)
               return $this->failNotFound('No se ha encontrado una transaccion con el id: '.$id);

            $transaccion = $this->request->getJSON();

            if($this->model->update($id, $transaccion)):
                $transaccion->id = $id;
                return $this->respondUpdated($transaccion);
            else:
                return $this->failValidationError($this->model->validation->listErrors());
            endif;

        } catch (\Exception $e) {
          return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function delete($id = null)
    {
        try {

            if($id == null)
               return $this->failValidationError('No se ha pasado un Id valido');

            $transaccionVerificada = $this->model->find($id);
            if($transaccionVerificada == null)
               return $this->failNotFound('No se ha encontrado una transaccion con el id: '.$id);

            if($this->model->delete($id)):
                return $this->respondDeleted($transaccionVerificada);
            else:
                return $this->failServerError('No se ha podido eliminar el registro');
            endif;

        } catch (\Exception $e) {
           return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function getTransaccionesByCliente($id = null)
    {
        try {
            $modelCliente = new ClienteModel();

            if($id == null)
                return $this->failValidationError('No se ha pasado un Id valido');

        $cliente = $modelCliente->find($id);
        if($cliente == null)
            return $this->failNotFound('No se ha encontrado un cliente con el id: '.$id);

        $transacciones = $this->model->TransaccionesPorCliente($id);

        return $this->respond($transacciones);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
    }
}

    public function actualizarFondoCuenta($tipoTransaccionId, $monto, $cuentaId)
    {
        $modelCuenta = new CuentaModel();
        $cuenta = $modelCuenta->find($cuentaId);

        switch ($tipoTransaccionId) {
            case 1:
                $cuenta["fondo"] += $monto;
                break;

            case 2:
                $cuenta["fondo"] -= $monto;
        }

        if ($modelCuenta->update($cuentaId, $cuenta)) :
            return array('TransaccionExitosa' => true, 'NuevoFondo' => $cuenta["fondo"]);
        else :
            return array('TransaccionExitosa' => false, 'NuevoFondo' => $cuenta["fondo"]);
        endif;
    }
}
