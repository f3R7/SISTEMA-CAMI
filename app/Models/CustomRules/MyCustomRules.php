<?php 
namespace App\Models\CustomRules;

use App\Models\PersonaModel;
use App\Models\RolModel;
use App\Models\PermisoModel;
use App\Models\UsuarioModel;
use App\Models\ServicioModel;
use App\Models\Ficha_KinesicaModel;

class MyCustomRules{
	public function id_valid_pers(int $id):bool{
		$model=new PersonaModel();
		$persona= $model->find($id);

		return $persona == null ? false : true;
	}

	public function id_valid_rol(int $id):bool{
		$model=new RolModel();
		$rol= $model->find($id);

		return $rol == null ? false : true;
	}
	public function id_valid_permiso(int $id):bool{
		$model=new PermisoModel();
		$permiso= $model->find($id);

		return $permiso == null ? false : true;
	}
	public function id_valid_usuario(int $id):bool{
		$model=new UsuarioModel();
		$usuario= $model->find($id);

		return $usuario == null ? false : true;
	}
	public function id_valid_servicio(int $id):bool{
		$model=new ServicioModel();
		$servicio= $model->find($id);

		return $servicio == null ? false : true;
	}

	public function valid_login(string $login):bool{
		$model=new UsuarioModel();
		$usuario = $model->where('Login', $login)->first();

		return $usuario != null ? false : true;
	}

		public function id_valid_ficha(int $id):bool{
		$model=new Ficha_KinesicaModel ();
		$Ficha_Kinesica= $model->find($id);

		return $Ficha_Kinesica == null ? false : true;
	}

}