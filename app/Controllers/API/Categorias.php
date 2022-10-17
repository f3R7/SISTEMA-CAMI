<?php

namespace App\Controllers\API;

use App\Models\CategoriaModel;
use CodeIgniter\RESTful\ResourceController;

class Categorias extends ResourceController{

	public function __construct(){
        
		$this->model = $this->setModel(new CategoriaModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }

		$categorias = $this->model->findAll();
		return $this->respond($categorias);
	}


    public function create(){
        try {

            $categoria = $this->request->getJSON();
            
            if ($this->model->insert($categoria)) {
                $categoria->id=$this->model->insertID();
                return $this->respondCreated($categoria);
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
                $categoria=$this->model->find($id);
                if ($categoria==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($categoria);
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
                $categoriaact=$this->model->find($id);
                if ($categoriaact==null) {
                    return $this->failNotFound("no se ha encontrado: ".$id);
                }else{
                    $categoria = $this->request->getJSON();

                    if ($this->model->update($id, $categoria)) {
                        $categoria->id=$id;
                        return $this->respondUpdated($categoria);
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
                $categoriaver=$this->model->find($id);
                if ($categoriaver==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $categoria = json_encode($categoriaver);
                    $categoria = json_decode($categoria);
                    $categoria->Estado=1;
                    if ($this->model->update($id, $categoria)) {
                        $categoria->id=$id;
                        return $this->respondUpdated($categoria);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

     public function desactivar($id)
    {
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $categoriaver=$this->model->find($id);
                if ($categoriaver==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $categoria = json_encode($categoriaver);
                    $categoria = json_decode($categoria);
                    $categoria->Estado=0;
                    if ($this->model->update($id, $categoria)) {
                        $categoria->id=$id;
                        return $this->respondUpdated($categoria);
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