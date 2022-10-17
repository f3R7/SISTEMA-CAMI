<?php

namespace App\Controllers\API;

use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class Usuarios extends ResourceController{

	public function __construct(){
        
		$this->model = $this->setModel(new usuarioModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
            return $this->failServerError('El rol no tiene acceso a esta api');
        }
		//$usuarios = $this->model->findAll();
		//return $this->respond($usuarios);
            //echo $id;
            $usuarios = $this->model->listusu();
            return $this->respond($usuarios);
	}





    public function create(){
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            $usuario = $this->request->getJSON();
            $temp = $usuario->Clave;
            $usuario->Clave=hashPassowrd($temp);
            $usuario->Estado='1';
            $usuario->Intentos='1';
            
            if ($this->model->insert($usuario)) {
                $usuario->id=$this->model->insertID();
                return $this->respondCreated($usuario);
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
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id');
            }else{
                $usuario=$this->model->find($id);
                if ($usuario==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($usuario);
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function update($id=null)
    {
        try {
            if (!validateAccess(array('Cliente'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $usuarioverif=$this->model->find($id);
                if ($usuarioverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $usuario = $this->request->getJSON();
                    if (!retid($id, $this->request->getServer('HTTP_AUTHORIZATION'))) {
                        return $this->failValidationError('El Id no corresponde');
                    }

                    if ($this->model->update($id, $usuario)) {
                        $usuario->id=$id;
                        return $this->respondUpdated($usuario);
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
            $usu = $this->request->getPost('Login');
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




    public function desactivar($id=null)
    {
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $usuarioverif=$this->model->find($id);
                if ($usuarioverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $usuario = json_encode($usuarioverif);
                    $usuario = json_decode($usuario);
                    $usuario->Estado=0;
                    if ($this->model->update($id, $usuario)) {
                        $usuario->id=$id;
                        return $this->respondUpdated($usuario);
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
                $usuarioverif=$this->model->find($id);
                if ($usuarioverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $usuario = json_encode($usuarioverif);
                    $usuario = json_decode($usuario);
                    $usuario->Estado=1;
                    if ($this->model->update($id, $usuario)) {
                        $usuario->id=$id;
                        return $this->respondUpdated($usuario);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }           
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }


    public function editperfil($id=null)
    {
        try {
            if (!validateAccess(array('Administrador', 'Cliente', 'Medico'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id');
            }else{

                
            $usuario = $this->model->listperfil($id);
                if ($usuario==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($usuario);
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }



}