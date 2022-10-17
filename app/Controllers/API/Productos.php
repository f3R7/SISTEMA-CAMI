<?php

namespace App\Controllers\API;

use App\Models\ProductoModel;
use CodeIgniter\RESTful\ResourceController;

class Productos extends ResourceController{

	public function __construct(){
        
		$this->model = $this->setModel(new ProductoModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }

		$productos = $this->model->findAll();
		return $this->respond($productos);
	}


    public function create(){
        try {

            $producto = $this->request->getJSON();
            
            if ($this->model->insert($producto)) {
                $producto->id=$this->model->insertID();
                return $this->respondCreated($producto);
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
                $producto=$this->model->find($id);
                if ($producto==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($producto);
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
                $productoact=$this->model->find($id);
                if ($productoact==null) {
                    return $this->failNotFound("no se ha encontrado: ".$id);
                }else{
                    $producto = $this->request->getJSON();

                    if ($this->model->update($id, $producto)) {
                        $producto->id=$id;
                        return $this->respondUpdated($producto);
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
                $productover=$this->model->find($id);
                if ($productover==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $producto = json_encode($productover);
                    $producto = json_decode($producto);
                    $producto->Estado=1;
                    if ($this->model->update($id, $producto)) {
                        $producto->id=$id;
                        return $this->respondUpdated($producto);
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
                $productover=$this->model->find($id);
                if ($productover==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $producto = json_encode($productover);
                    $producto = json_decode($producto);
                    $producto->Estado=0;
                    if ($this->model->update($id, $producto)) {
                        $producto->id=$id;
                        return $this->respondUpdated($producto);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }


    public function upfile(){

        try {
            $rand = round(microtime(true));
            $usu = $this->request->getPost('Nombre');
            $file = $_FILES['foto']['name'];
            $size = $_FILES['foto']['size'];
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!empty($file) && $file!="") {
                $formatos=array('jpg', 'png');
                if (in_array($ext, $formatos)) {
                    $path = 'images/'.$usu;
                    if (!file_exists('./'.$path)) {
                        mkdir($path,0777,true);
                    }
                    $path=$path.'/'.$rand.'.'.$ext;

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], './'.$path)) {
                       return $this->respond(array('result'=> $path, 'status' => 'OK'),200);
                    }
                }
            }
            else{
                return $this->failServerError('No se cargo un archivo');
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el ss');
        }
    }


    public function listaproductos(){
        if (!validateAccess(array('Administrador','Cliente' ), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }
            $reserva = $this->model->listaproductos();
            return $this->respond($reserva);
        }


}