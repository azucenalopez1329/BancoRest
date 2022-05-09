<?php namespace App\Controllers\API;

use App\Models\RolModel;
use CodeIgniter\RESTful\ResourceController;

class Roles extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new RolModel());
    }
    
    public function index()
    {
        $roles = $this->model->findAll();
        return $this->respond($roles);
    } 

    public function create()
    {
        try {

            $rol = $this->request->getJSON();
            if($this->model->insert($rol)):
                $rol->id = $this->model->insertID();
                return $this->respondCreated($rol);
            else:
                return $this->failValidationError($this->model->validation->listErrors());
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

            $rol = $this->model->find($id);
            if($rol == null)
               return $this->failNotFound('No se ha encontrado un rol con el id: '.$id);

            return $this->respond($rol);

        } catch (\Exception $e) {
          return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function update($id = null)
    {
        try {

            if($id == null)
               return $this->failValidationError('No se ha pasado un Id valido');

            $rolVerificado = $this->model->find($id);
            if($rolVerificado == null)
               return $this->failNotFound('No se ha encontrado un rol con el id: '.$id);

            $rol = $this->request->getJSON();

            if($this->model->update($id, $rol)):
                $rol->id = $id;
                return $this->respondUpdated($rol);
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

            $rolVerificado = $this->model->find($id);
            if($rolVerificado == null)
               return $this->failNotFound('No se ha encontrado un rol con el id: '.$id);

            if($this->model->delete($id)):
                return $this->respondDeleted($rolVerificado);
            else:
                return $this->failServerError('No se ha podido eliminar el registro');
            endif;

        } catch (\Exception $e) {
           return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
}