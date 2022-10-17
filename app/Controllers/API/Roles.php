<?php

namespace App\Controllers\API;

use App\Models\RolModel;
use CodeIgniter\RESTful\ResourceController;

class Roles extends ResourceController{

	public function __construct(){
		$this->model = $this->setModel(new RolModel());
        helper('secure_password');
        helper('acces_rol');
	}

	public function index(){
		$roles = $this->model->findAll();
		return $this->respond($roles);
	}

   


}