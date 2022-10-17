<?php
namespace App\Models;

use CodeIgniter\Model;

class MarcaModel extends Model{
	protected $table = 'marca';
	protected $primaryKey = 'IdMarca';

	protected $returnType = 'array';
	protected $allowedFields = ['Nombre','Pais','Ciudad', 'Estado'];

	protected $validationRules = [
		'Nombre' => 'required|alpha_numeric|min_length[3]|max_length[35]',
		'Pais' => 'required|min_length[3]|max_length[30]',
		'Ciudad' => 'required|min_length[3]|max_length[20]'
	];


	protected $skipValidation = false;


}