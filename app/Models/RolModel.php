<?php namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model{
	protected $table = 'rol';
	protected $primaryKey = 'IdRol';

	protected $returnType = 'array';
	protected $allowedFields = ['Nombre','Descripcion','Estado'];

	protected $validationRules = [
	
	];



	protected $skipValidation = false;
}