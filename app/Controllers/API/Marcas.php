<?php

namespace App\Controllers\API;

use App\Models\MarcaModel;
use CodeIgniter\RESTful\ResourceController;

class Marcas extends ResourceController{

	public function __construct(){
        
		$this->model = $this->setModel(new MarcaModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }

		$marcas = $this->model->findAll();
		return $this->respond($marcas);
	}


    public function create(){
        try {

            $marca = $this->request->getJSON();
            
            if ($this->model->insert($marca)) {
                $marca->id=$this->model->insertID();
                return $this->respondCreated($marca);
            }else{
                return $this->failValidationError($this->model->validation->listErrors());
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    
    public function edit($id=null)
    {
        try {
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id');
            }else{
                $marca=$this->model->find($id);
                if ($marca==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($marca);
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function update($id=null)
    {
        try {
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $marcaact=$this->model->find($id);
                if ($marcaact==null) {
                    return $this->failNotFound("no se ha encontrado: ".$id);
                }else{
                    $marca = $this->request->getJSON();

                    if ($this->model->update($id, $marca)) {
                        $marca->id=$id;
                        return $this->respondUpdated($marca);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }                    
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

      public function activar($id=null)
    {
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $marcaver=$this->model->find($id);
                if ($marcaver==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $marca = json_encode($marcaver);
                    $marca = json_decode($marca);
                    $marca->Estado=1;
                    if ($this->model->update($id, $marca)) {
                        $marca->id=$id;
                        return $this->respondUpdated($marca);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

     public function desactivar($id=null)
    {
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $marcaver=$this->model->find($id);
                if ($marcaver==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $marca = json_encode($marcaver);
                    $marca = json_decode($marca);
                    $marca->Estado=0;
                    if ($this->model->update($id, $marca)) {
                        $marca->id=$id;
                        return $this->respondUpdated($marca);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }


}