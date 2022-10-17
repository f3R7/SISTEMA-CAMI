<?php

namespace App\Controllers;

use App\Models\RestauracionModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use App\Models\UsuarioModel;

class Restauraciones extends ResourceController{

	public function __construct(){
		$this->model = $this->setModel(new RestauracionModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
		$restauraciones = $this->model->findAll();
		return $this->respond($restauraciones);
	}

    public function create(){
        try {
            $entrada = $this->request->getJSON();
            $rest=$this->model->valemail($entrada->email);
            //return $this->respond($rest);
            if (empty($rest)) {
                return $this->respond(array('result'=>'No se encontró el email','status'=>'404'));
            }
            $jwt = $this->generate_JWT($rest);
            //echo strlen($jwt);
            $entrada->IdUsuario=$rest[0]->IdUsuario;
            $entrada->Token=$jwt;
            $entrada->FechaHora=date('Y/m/d h:i:s', time());
            $entrada->Estado='Activo';
            //echo $rest[0]->IdUsuario;
            //return $this->respond($rest->IdUsuario);
            if ($this->model->insert($entrada)) {
                $entrada->id=$this->model->enviaremail($entrada->email, $entrada->Token);
           
                return $this->respond(array('result'=>'Revise su correo electrónico','status'=>'200'));
            }else{
                return $this->failValidationError($this->model->validation->listErrors());
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    protected function generate_JWT($usuario){
        $key = Services::getSecretKey();
        $time = time();
        $payload = [
            'aud' => base_url(),
            'iat' => $time,
            'exp' => $time + 180000,
            'data' =>[
                'IdUsuario' => $usuario[0]->IdUsuario,
                'email' => $usuario[0]->email,
            ]
        ];

        $jwt = JWT::encode($payload, $key);
        return $jwt;
    }


    

    public function update($id=null)
    {
        try {            
            $token = $_GET['token'];
            if ($token==null) {
                return $this->respond(array('result'=>'No se pasó el token','status'=>'404'));
            }else{
                $restauracion=$this->model->where(array('Token'=> $token,'Estado'=>'Activo'))->first();
                //return $this->respond($restauracion);
                if ($restauracion==null) {
                    return $this->respond(array('result'=>'No se encontró el token','status'=>'404'));
                }else{
                    $val=returnid($token);
                    if ($val==null) {
                        return $this->respond(array('result'=>'No se encontró el valor','status'=>'404'));
                    }
                    //echo $val;
                    $recibe = $this->request->getJSON();

                    $usuario= new UsuarioModel();
                    $ususel=$usuario->find($val);
                    $ususel=json_encode($ususel);
                    $ususel=json_decode($ususel);
                    $ususel->Clave=hashPassowrd($recibe->Clave);
                    if ($usuario->update($val, $ususel)) {
                        //$restauracion->id=$id;
                        $restauracion=json_encode($restauracion);
                        $restauracion=json_decode($restauracion);
                        $restauracion->Estado="Inactivo";
                        if ($this->model->update($restauracion->IdRestauracion, $restauracion)) {
                            return $this->respond(array('result'=>'Se actualizó correctamente','status'=>'200'));
                        }else{
                            return $this->respond(array('result'=>'Error al actualizar','status'=>'400'));                        
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
}