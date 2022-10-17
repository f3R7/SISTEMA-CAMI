<?php

use Config\Services;
use Firebase\JWT\JWT;
use App\Models\RolModel;

function validateAccess($roles, $authHeader){
	if (!is_array($roles)) {
		return false;
	}

	$key = Services::getSecretKey();
	$arr = explode(' ', $authHeader);
	$jwt = $arr[1];
	$jwt = JWT::decode($jwt, $key, ['HS256']);
	$rolmodel = new RolModel();
	$rol = $rolmodel->find($jwt->data->rol);
	if ($rol == null) {
		return false;
	}

	if (!in_array($rol["Nombre"], $roles)) {
		return false;
	}

	return true;
}

function retid($id, $authHeader){
	$key = Services::getSecretKey();
	$arr = explode(' ', $authHeader);
	$jwt = $arr[1];
	$jwt = JWT::decode($jwt, $key, ['HS256']);
	if ($jwt->data->id != $id) {
		return false;
	}
	return true;
}

function retidper($id, $authHeader){
	$key = Services::getSecretKey();
	$arr = explode(' ', $authHeader);
	$jwt = $arr[1];
	$jwt = JWT::decode($jwt, $key, ['HS256']);
	if ($jwt->data->persona != $id) {
		return false;
	}
	return true;
}
function returnid($authHeader){
	$key = Services::getSecretKey();
	$jwt = JWT::decode($authHeader, $key, ['HS256']);
	return $jwt->data->IdUsuario;
}
