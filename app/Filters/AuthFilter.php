<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Models\RolModel;

class AuthFilter implements FilterInterface{
	use ResponseTrait;

	public function before(RequestInterface $request, $arguments = null){
		try {
		
			$key = Services::getSecretKey();
			$authHeader = $request->getServer('HTTP_AUTHORIZATION');
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Headers: X-API-KEY, Origin,X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
			if ($authHeader == null) {
				return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'No se ha enviado el token jwt de autorizacion');
			}
			$arr = explode(' ', $authHeader);
			$jwt = $arr[1];

			$jwt = JWT::decode($jwt, $key, ['HS256']);

			$rolmodel = new RolModel();
			$rol = $rolmodel->find($jwt->data->rol);
			if ($rol == null) {
				return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'El rol enviado es inválido');
			}
			return true;
		}
		 catch (ExpiredException $e) {
			return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'El token ha expirado');
		}
		 catch (\Exception $e) {
			return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Ha ocurrido un error en el servidor');
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

	}
}