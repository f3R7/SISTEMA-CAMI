<?php

namespace App\Controllers\API;

use App\Models\PersonaModel;
use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class Personas extends ResourceController{

	public function __construct(){
		$this->model = $this->setModel(new PersonaModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
        if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }
		$personas = $this->model->findAll();
		return $this->respond($personas);
	}

    public function create(){
        try {

             if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            $persona = $this->request->getJSON();
            $temp = $persona->Clave;
            $temp=hashPassowrd($temp);
            
            if ($this->model->insert($persona)) 
            {
                $id=$this->model->insertID();
                $items = array('Clave' => $temp, 'Estado' => '1', 'Intentos' => '0', 'Login'=>$persona->Login, 'Foto' => $persona->Foto,'IdPersona' => $id,'IdRol' => $persona->IdRol);
                $items=json_encode($items);
                $items=json_decode($items);
                $usuario= new UsuarioModel();

                if ($usuario->insert($items)) {
                    $items->id=$usuario->insertID();
                    return $this->respondCreated($items);
                }else{
                    return $this->failValidationError($usuario->validation->listErrors());
                }
            }
            else
            {
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
                $persona=$this->model->find($id);
                if ($persona==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($persona);
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function ci($id=null)
    {
        try {
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id');
            }else{
                $persona=$this->model->where('Ci', $id)->first();
                if ($persona==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                return $this->respond($persona);
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function updateadm($id=null)
    {
        try {
            if (!validateAccess(array('Administrador'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }

            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $personaverif=$this->model->find($id);
                if ($personaverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{
                    $persona = $this->request->getJSON();

                    if ($this->model->update($id, $persona)) {
                        //$id=$persona->Idpersona;

                        $temp = $persona->Clave;
                        $temp=hashPassowrd($temp);
                        $usuarios= new UsuarioModel();
                        $usuario = $usuarios->where('IdPersona', $id)->first();

                        $items = array('Clave' => $temp, 'Estado' => $usuario['Estado'], 'Intentos' => $usuario['Intentos'], 'Login'=>$persona->Login, 'Foto' => $persona->Foto,'IdPersona' => $usuario['IdPersona'],'IdRol' => $persona->IdRol);
                        $items=json_encode($items);
                        $items=json_decode($items);
                        //return $this->respond($items);
                        if ($usuarios->update($usuario['IdUsuario'],$items)) {
                            //$items->id=$usuario->insertID();
                            //return $this->respondCreated($items);
                        }else{
                            return $this->failValidationError($usuario->validation->listErrors());
                        }
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }                    
                }
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
                $personaverif=$this->model->find($id);
                if ($personaverif==null) {
                    return $this->failNotFound("no se ha encontrado: ".$id);
                }
                    if (!retidper($id, $this->request->getServer('HTTP_AUTHORIZATION'))) {
                        return $this->failValidationError('El Id no corresponde');
                    }else{
                    $persona = $this->request->getJSON();

                    if ($this->model->update($id, $persona)) {
                        $persona->id=$id;
                        return $this->respondUpdated($persona);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }                    
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function updatemedico($id=null)
    {
        try {
             if (!validateAccess(array('Administrador','Medico'), $this->request->getServer('HTTP_AUTHORIZATION'))) {
                return $this->failServerError('El rol no tiene acceso a esta api');
            }
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $personaverif=$this->model->find($id);
                if ($personaverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }
                    if (!retidper($id, $this->request->getServer('HTTP_AUTHORIZATION'))) {
                        return $this->failValidationError('El Id no corresponde');
                    }else{
                    $persona = $this->request->getJSON();
                    $personaverif = json_encode($personaverif);
                    $personaverif = json_decode($personaverif);
                    $personaverif->Celular = $persona->Celular;
                    $personaverif->Direccion = $persona->Direccion;

                    if ($this->model->update($id, $personaverif)) {
                        $personaverif->id=$id;
                        return $this->respondUpdated($personaverif);
                    }else{
                        return $this->failValidationError($this->model->validation->listErrors());
                    }                    
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function delete($id=null){
        try {
            if ($id==null) {
                return $this->failValidationError('No se pasó una Id ');
            }else{
                $personaverif=$this->model->find($id);
                if ($personaverif==null) {
                    return $this->failNotFound("No se ha encontrado: ".$id);
                }else{

                    if ($this->model->delete($id)) {
                        echo 'Se ha Eliminado el Regsitro';
                        return $this->respondDeleted($personaverif);
                    }else{
                        return $this->failServerError('No se pudo Eliminar el Registro');
                    }                    
                }
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }        
    }


    

}