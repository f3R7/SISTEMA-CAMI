<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model{
	protected $table = 'producto';
	protected $primaryKey = 'IdProducto';

	protected $returnType = 'array';
	protected $allowedFields = ['IdCategoria','IdMarca', 'Nombre', 'Precio', 'Stock', 'Descripcion', 'Imagen', 'Estado'];

	protected $validationRules = [
		'IdCategoria' => 'required|integer',
		'IdMarca' => 'required|integer',
		'Nombre' => 'required|min_length[3]|max_length[50]',
		'Precio' => 'required|decimal',
		'Stock'  => 'required|integer',
		'Descripcion' => 'required|min_length[3]|max_length[500]',
	
	];
	protected $skipValidation = false;

	public function listaproductos()
	{
		$consulta = $this->db->table($this->table);
		$consulta->select('IdProducto,producto.Nombre, producto.Precio, producto.Stock, producto.Descripcion,
		producto.Estado, producto.Imagen, producto.IdMarca, producto.IdCategoria,
		 categoria.Nombre as category, marca.Nombre as marcas');
		$consulta->join('marca', 'marca.IdMarca = producto.IdMarca');
		$consulta->join('categoria', 'categoria.IdCategoria = producto.IdCategoria');
		$query =$consulta->get();
		return $query->getResult();
	}

	public function detalleproducto($id=null)
	{
		$consulta = $this->db->table($this->table);
		$consulta->select('*, producto.Nombre,  marca.Nombre as marcas, categoria.Nombre as category');
		$consulta->join('categoria', 'producto.IdCategoria = categoria.IdCategoria');
		$consulta->join('marca', 'marca.IdMarca = producto.IdMarca');
		$consulta->where('producto.IdProducto', $id );
		$query =$consulta->get();
		return $query->getResult();
	}

}