<?php
namespace App\Models;

use CodeIgniter\Model;

class IngresoModel extends Model{
	protected $table = 'ingreso';
	protected $primaryKey = 'IdIngreso';

	protected $returnType = 'array';
	protected $allowedFields = ['Fecha','Total','Estado'];

	protected $validationRules = [
		'Fecha' => 'required',
		'Total' => 'required|decimal'
	];


	protected $skipValidation = false;


}