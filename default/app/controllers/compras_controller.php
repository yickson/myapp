<?php

/**
* Controller para todo el carrito de compra con PHP
*/
class ComprasController extends AppController
{

	public function before_filter()
	{
		View::template('colaborador');
		$valido = New Colaborador;
	    $accion = Router::get('action');
	    if(!$valido->logged() and $accion != 'entrar' and $accion != 'registrar'
			and $accion != 'recuperar' and $accion != 'restaurar'){
	      Redirect::to("colaborador/entrar");
	    }
	    else{
	    	$this->identidad = Session::get('id', 'colaborador');
	    	$this->nombrep = Session::get('nombre', 'colaborador');
				$this->puntos = Session::get('puntos', 'colaborador');
				$this->carrito = (New Carrito);
				//$this->cantidad = (New Carrito)->get_content();
				$this->items = New Carrito;
	    }
	}

	public function index()
	{
		//Page principal para comprar por parte del colaborador
		$this->compras = (New Compras)->find_by_colaborador_id($this->identidad);
		$this->carrito = (New Carrito)->get_content();
		$this->total = (New Carrito);
	}

	public function agregar($id)
	{
		//Este es un metodo para realizar la captura del producto/servicio para el carrito
		View::template(null);
		//Busqueda en publicaciones para pasar al carrito
		$datos = (New Publicaciones)->find($id);
		$resultado = (New Compras)->agregar($datos);
		$this->carrito->add($resultado);
		//Redirect::to('compras');
	}

	public function agregar2($id)
	{
		if(Input::hasPost('cantidad')){
			//El usuario envío una cantidad de un artículo
			$cantidad = Input::post('cantidad');
			$datos = (New Publicaciones)->find($id);
			$resultado = (New Compras)->agregar($datos, $cantidad);
			$this->carrito->add($resultado);
			Redirect::to('compras');
		}
	}

	//Metodo de prueba para la cantidad de items agregados al carrito
	public function cantidad()
	{
		View::template(null);
		echo $this->items->articulos_total();
	}

	public function borrar($codigo)
	{
		//Borrar un artículo de la lista del carrito
		View::template(null);
		$this->carrito->remove_producto($codigo);
		Redirect::to('compras');
	}

	public function eliminar()
	{
		//Eliminar carro de compra
		View::template(null);
		$this->carrito->destroy();
		Redirect::to('compras');
	}

	public function verificar($id)
	{
		//Verifica e introduce datos para el envío al cliente
		$this->carro = (New Carrito);
		//Llamando a la BD para verificar los datos del colaborador
		$this->datos = (New Colaborador)->find($id);
		//Verificando si hay un form del carro
		if(Input::hasPost('verificar')){
			if(Input::post('puntos') != null){
				$puntos = Input::post('puntos');
			}
			else{
				$puntos = 0;
			}
			Redirect::to('compras/finalizado'); //Redirecciona al final del carro
		}
	}

	public function finalizado()
	{
		//Paso final para realizar la compra del colaborador
		$id = $this->identidad; //Identidad del colaborador
		$this->carrito = (New Carrito)->get_content(); //Contenido del carro
		$this->carro = (New Carrito); //Datos del carro hasta ahora
		$this->datos = (New Colaborador)->find($id); //Datos del colaborador
	}
}

?>
