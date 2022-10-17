<?php namespace App\Models;

use CodeIgniter\Model;

class RestauracionModel extends Model{
	protected $table = 'restauracion';
	protected $primaryKey = 'IdRestauracion';

	protected $returnType = 'array';
	protected $allowedFields = ['IdUsuario','Token','FechaHora','Estado'];

	protected $validationRules = [
		'IdUsuario' => 'required|integer|id_valid_usuario',
		'Token' => 'required|min_length[200]|max_length[300]',
		'FechaHora'=> 'required|valid_date',
		'Estado'=> 'required|alpha'
	];

	protected $validationMessages = [
         'IdUsuario'=> [
         	'required'  => 'Debe Ingresar un valor',
		 'id_valid_usuario' => 'Debe Ingresar una ID Valida',
		],
		'Token'=> [
		'required' => 'Debe Ingresar un Token',
		'min_length' => 'El Minimo de Caracteres es 60'
		],
		'FechaHora'=>[
			'required' => 'Debe Seleccionar una Fecha y Hora',
			'valid_date'=> 'Datos de Fecha Invalido'
		],
	];

	protected $skipValidation = false;	

	public function valemail($email = null)
	{
		$consulta = $this->db->table('persona');
		$consulta->select('persona.email,usuario.IdUsuario');
		$consulta->join('usuario','usuario.IdPersona = persona.IdPersona');
		$consulta->where('persona.Email', $email);

		$query =$consulta->get();
		return $query->getResult();
	}

	public function enviaremail($email, $token){

		$link='http://localhost/cami/'.$token;
		$to = $email;
        $subject = 'NUevo alerta';
        $message = '<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
		
			<title>Contraseñas</title>
		
		<style>
		.center {
		  margin: auto;
		  text-align: center;
		  width: 50%;
		  border: 1px solid #20ACA0;
		  padding: 10px;
		}
		
		.button {
		  background-color: #20ACA0;
		  border: none;
		  color: white;
		  padding: 10px 15px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  margin: 4px 2px;
		  cursor: pointer;
		}
		</style>
		</head>
		
		<body>
			<div class="center">
		
		<img src="https://scontent.flpb2-2.fna.fbcdn.net/v/t1.6435-9/118951346_912563292600747_4455404583211767858_n.jpg?_nc_cat=104&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=WFWDE8NXF7YAX-v0oKe&_nc_ht=scontent.flpb2-2.fna&oh=00_AT8Ou4ieEDMRSJKpxwyL8I0w7ZSEio_dPWGL05-qNwVraQ&oe=623636AE" alt="" style="width:150px;height:150px;">
		
		
			<p>¡Imdu contraseña, fdfeña.</p>
						
							<a href="'.$link.'" > <button class="button">Reestablecer Contraseña</button> </a>
						</div>
		</body>
		</html>';
    
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('aquilescastro468@gmail.com', 'Confirm Registration');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            echo 'Email successfully sent';
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
	 }
}