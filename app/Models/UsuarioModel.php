<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model{
	protected $table = 'usuario';
	protected $primaryKey = 'IdUsuario';

	protected $returnType = 'array';
	protected $allowedFields = ['Login','Clave','IdPersona','Foto','Estado','IdRol','Intentos'];

	protected $validationRules = [
		'Login' => 'required|alpha_numeric|min_length[3]|max_length[35]',
		'Clave' => 'required|min_length[3]|max_length[60]',
		'IdPersona' => 'required|integer|id_valid_pers',

		'Estado' => 'required|numeric',
		'IdRol' => 'required|integer|id_valid_rol',
		'Intentos' => 'required'
	];

	protected $validationMessages = [
		'Login' => [
			'required' => 'Debe Ingresar su Nombre de Usuario',
			'alpha_numeric' => 'Solo Letras, Numeros y Simbolos login'
		],
		'Clave'=>[
			'required' => 'Debe Ingresar una Clave',
			'alpha_numeric' => 'Solo Letras, Numeros y Simbolos'
		],
		'IdPersona'=> [
			'id_valid_pers' => 'Debe Ingresar una ID Valida'
		]
	];

	protected $skipValidation = false;

	public function listusu()
	{
		$consulta = $this->db->table($this->table);
		$consulta->select('persona.Nombres, persona.Apellidos, persona.Cedula, persona.Telefono, persona.Ciudad, persona.Email, usuario.IdUsuario, usuario.Login, usuario.Foto, usuario.Estado, rol.Nombre as Rol, persona.IdPersona');
		$consulta->join('persona', 'usuario.IdPersona = persona.IdPersona');
		$consulta->join('rol', 'rol.IdRol = usuario.IdRol');


		$query =$consulta->get();
		return $query->getResult();
	}


	public function listperfil($id=null)
	{
		$consulta = $this->db->table($this->table);
		$consulta->select('persona.Nombres, persona.Apellidos, persona.Cedula, persona.Ciudad, Persona.Telefono, persona.Email, usuario.IdUsuario, usuario.Login, usuario.Foto, usuario.Estado, rol.Nombre as Rol, persona.IdPersona, rol.IdRol, usuario.Clave');
		$consulta->join('persona', 'usuario.IdPersona = persona.IdPersona');
		$consulta->join('rol', 'rol.IdRol = usuario.IdRol');
		$consulta->where('usuario.IdUsuario', $id);

		$query =$consulta->get();
		return $query->getResult();
	}


}