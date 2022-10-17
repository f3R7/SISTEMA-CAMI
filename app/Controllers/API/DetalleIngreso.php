<?php

namespace App\Controllers\API;

use App\Models\DetalleIngresoModel;
use CodeIgniter\RESTful\ResourceController;

class DetalleIngreso extends ResourceController{

	public function __construct(){
        
		$this->model = $this->setModel(new DetalleIngresoModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }

		$ingresos = $this->model->findAll();
		return $this->respond($ingresos);
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

    

    public function pruebas(){

        try {
            $ingreso = $this->request->getJSON();
           // $this->respond($ingreso->productos[0]->cantidad);

           $cantidad = $ingreso->productos[0]->cantidad;
           $precio = $ingreso->productos[0]->detalles->precio;
           $sub = $cantidad * $precio;



            return $this->respond($tam);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    









    /*
    public function edit($id=null)
    {
        try {
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id');
            }else{
                $ingreso=$this->model->find($id);
                if ($ingreso==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($ingreso);
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
                $ingresoact=$this->model->find($id);
                if ($ingresoact==null) {
                    return $this->failNotFound("no se ha encontrado: ".$id);
                }else{
                    $ingreso = $this->request->getJSON();

                    if ($this->model->update($id, $ingreso)) {
                        $ingreso->id=$id;
                        return $this->respondUpdated($ingreso);
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
                $ingresover=$this->model->find($id);
                if ($ingresover==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $ingreso = json_encode($ingresover);
                    $ingreso = json_decode($ingreso);
                    $ingreso->Estado=1;
                    if ($this->model->update($id, $ingreso)) {
                        $ingreso->id=$id;
                        return $this->respondUpdated($ingreso);
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
                $ingresover=$this->model->find($id);
                if ($ingresover==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $ingreso = json_encode($ingresover);
                    $ingreso = json_decode($ingreso);
                    $ingreso->Estado=0;
                    if ($this->model->update($id, $ingreso)) {
                        $ingreso->id=$id;
                        return $this->respondUpdated($ingreso);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }
*/

}