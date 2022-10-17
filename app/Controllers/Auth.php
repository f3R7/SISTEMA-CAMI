<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PersonaModel;
use App\Models\ProductoModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends BaseController{
	use ResponseTrait;

	public function __construct(){
		helper('secure_password');
	}

	public function listproductos(){
		$prod = new ProductoModel();
		$productos = $prod->FindAll();
		return $this->respond($productos);
	}


	public function detalleproducto($id=null){
        if ($id==null) {
            return $this->failValidationError('No se pasó una Id');
        }
        else{
			$prod = new ProductoModel();
            $producto = $prod->detalleproducto($id);
            return $this->respond($producto);
        }     
	}



	public function login(){
		try {
			$username = $_GET['Login'];
			$clave = $_GET['Clave'];
			$usuario = new UsuarioModel();
			$validacion = $usuario->where('Login', $username)->first();
			if ($validacion == null) {
				return $this->failNotFound('Usuario no encontrado');
			}
			if (verifyPassword($clave, $validacion["Clave"])) {
				$jwt = $this->generate_JWT($validacion);
				return $this->respond(['Token' => $jwt], 201);
			}else{
				return $this->failValidationError('Contraseña equivocada');
			}
		} catch (\Exception $e) {
			return $this->failServerError('Ha ocurrido un error en el servidor');
		}
	}

    public function create(){
        try {
			$persona = $this->request->getJSON();
            $temp = $persona->Clave;
            $temp=hashPassowrd($temp);
            $per = new PersonaModel();
          if ($per->insert($persona)) {
            $id=$per->insertID();
                $items = array('Clave' => $temp, 'Estado' => 'Activo', 'Intentos' => '0', 'Login'=>$persona->Login, 'Imagen' => $persona->Imagen,'IdPersona' => $id,'IdRol' => 2);   
				$items=json_encode($items);
                $items=json_decode($items);
                $usuario= new UsuarioModel();
                if ($usuario->insert($items)) {
                    $items->id=$usuario->insertID();
                    return $this->respondCreated($items);
                }else{
                    return $this->failValidationError($usu->validation->listErrors());
                }
            }else{
                return $this->failValidationError($this->model->validation->listErrors());
            }
        } catch (Exception $e) {
            return $this->failServerError('Se Produjo un Error en el Servidor');
        }
    }

    public function percreate(){
        try {
            $usuario = $this->request->getJSON();
			$usu = new PersonaModel();
            if ($usu->insert($usuario)) {
                $usuario->id=$usu->insertID();
                return $this->respondCreated($usuario);
            }else{
                return $this->failValidationError($usu->validation->listErrors());
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
				'usuario' => $usuario['Login'],
				'rol' => $usuario['IdRol'],
				'id' => $usuario['IdUsuario'],
				'persona' => $usuario['IdPersona'],
			]
		];

		$jwt = JWT::encode($payload, $key);
		return $jwt;
	}


	

}