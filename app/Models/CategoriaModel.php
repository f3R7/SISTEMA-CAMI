<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model{
	protected $table = 'categoria';
	protected $primaryKey = 'IdCategoria';

	protected $returnType = 'array';
	protected $allowedFields = ['Nombre','Estado'];

	protected $validationRules = [
		'Nombre' => 'required|alpha_numeric|min_length[3]|max_length[50]',
	];


	protected $skipValidation = false;


}