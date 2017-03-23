<?php

/**
* Controlador para la gestión de todas las publicaciones
* Es decir Productos/Servicios que ofrezca el proveedor
*/
class PublicacionesController extends AppController
{

	public function before_filter()
	{
		View::template('proveedor');
		$valido = New Proveedor;
	    $accion = Router::get('action');
	    if(!$valido->logged()){
	      Redirect::to("proveedor/entrar");
	    }
	    else{
	    	$this->identidad = Session::get('id', 'proveedor');
	    	$this->nombrep = Session::get('nombre', 'proveedor');
	    }
	}

	public function index()
	{
		View::template('proveedor');
		//Panel de las publicaciones
		$id = Session::get('id', 'proveedor');
		if($id == null){
			Redirect::to('proveedor/entrar');
		}
		$this->publicaciones = (New Publicaciones)->find_all_by_proveedor_id($id);
	}

	public function mostrar($id)
	{
		//Mostrar todas las publicaciones
		$this->publicaciones = (New Publicaciones)->find_all_by_proveedor_id($id, 'order: id desc');
	}

	public function crear($id)
	{
		if(Input::hasPost('nombre')){
			//Inicia si el usuario envia el form con el campo nombre del producto/servicio
			$publicacion = (New Publicaciones)->crear($id);
			if($publicacion){
				Flash::valid('El artículo ha sido creado exitosamente');
				Input::delete();
          		//Redirect::to('', '3');
			}
			else{
				Flash::error('No se ha podido registrar la publicación');
			}
		}
	}

	public function ver($id)
	{
		$this->publicacion = (New Publicaciones)->find($id);
		$this->imagenes = (New Imagenes)->find_all_by_sql("SELECT imagenes.url as url from imagenes, publicaciones where publicaciones.id = $id and imagenes.publicaciones_id = $id");
	}

	public function editar($id)
	{
		//Variable para pasarla a la vista de edición
		$this->publicacion = (New Publicaciones)->find($id);
		$this->tipo = array('Servicio'=>'servicio', 'Producto'=>'producto');

		if(Input::hasPost('id')){
			$imagen = (New Publicaciones)->imagen($id);
			$datos = (New Publicaciones)->find($id);
			$datos->nombre = Input::post('nombre');
			$datos->descripcion = Input::post('descripcion');
			$datos->descripcionl = Input::post('descripcionl');
			$datos->tipo = Input::post('tipo');
			$datos->cantidad = Input::post('cantidad');
			//$datos->categoria = $datos->categoria;
			$datos->precio = Input::post('precio');
			$datos->oferta = Input::post('oferta');
			if($imagen != null){
				if($imagen[0] != null){
					$datos->imagen1 = $imagen[0];
				}
				if($imagen[1] != null){
					$datos->imagen2 = $imagen[1];
				}
				if($imagen[2] != null){
					$datos->imagen3 = $imagen[2];
				}
			}
			if($datos->save()){
				Flash::valid('El artículo ha sido modificado exitosamente');
          		//Redirect::to('', '3');
			}
			else{
				Flash::error('No se pudo editar el formulario');
			}
		}
	}

	public function eliminar($id)
	{
		View::template(null);
		$publicacion = (New Publicaciones);
		if($publicacion->eliminar($id)){
			//Flash::valid('Se elimino el artículo exitosamente');
			Redirect::to('publicaciones');
		}
		else{
			Flash::error('No se pudo borrar el producto/servicio, intente de nuevo');
		}
	}
}

?>
