<?php
namespace App\Models;

use CodeIgniter\Model;

class DetalleIngresoModel extends Model{
	protected $table = 'detalle_ingreso';
	protected $primaryKey = 'IdDetalle';

	protected $returnType = 'array';
	protected $allowedFields = ['IdProducto','IdIngreso','Cantidad', 'PrecioIngreso', 'SubTotal'];

	protected $validationRules = [
		'IdProducto' => 'required',
		'SubTotal' => 'required'
	];


	protected $skipValidation = false;


}