<?php namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model{
	protected $table = 'persona';
	protected $primaryKey = 'IdPersona';

	protected $returnType = 'array';
	protected $allowedFields = ['Nombres','Apellidos','Cedula','Telefono','Ciudad','Email'];

	protected $validationRules = [
		'Nombres' => 'required|alpha_space|min_length[3]|max_length[35]',
		'Apellidos' => 'required|alpha_space|min_length[3]|max_length[35]',
		'Cedula' => 'required|numeric|min_length[7]|max_length[15]',
		'Telefono' =>  'required|integer|min_length[8]|max_length[20]',
		'Email' => 'required|valid_email|min_length[5]|max_length[50]',
		'Ciudad' => 'required|min_length[4]|max_length[25]',

	];

	protected $validationMessages = [
		'Nombres' => [
			'required' => 'Debe Ingresar un Nombre',
			'alpha_space' => 'Solo Letras y Espacios',
			'min_length'=> 'Caracteres Minimos 3'
		],
		'Apellidos'=>[
			'required' => 'Debe Ingresar un Apellido',
			'alpha_space' => 'Solo Letras y Espacios',
			'min_length'=> 'Caracteres Minimos 3'
		],
		'Telefono'=>[
			'required' => 'Dede Ingresar un Numero de Celular',
			'integer'=> 'Solo se Admiten Numeros',
			'min_length' => 'El Número Maximo de Caracteres es de 8 '
		],
		'Cedula'=>[
			'required' => 'Debe ingresar un Numero de CI'
		],
		'Email'=>[
			'required' => 'Debe Ingresar un Email',
			'valid_email'=> 'Ingrese un Formato Correcto Ejemplo: Usuario@gmail.com'
		],

		'Ciudad'=>[
			'required' => 'Escriba su ciudad'
		],
	

	];

	protected $skipValidation = false;
	

}
