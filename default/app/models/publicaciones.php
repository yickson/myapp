<?php

/**
* Modelo donde se almacenan las publicaciones
*/
class Publicaciones extends ActiveRecord
{

	public function crear()
	{
		//Metodo para crear un producto/servicio
			if(!empty($_FILES['imagen1']['name'])){
				$imagen1 = Upload::factory('imagen1', 'image');
		    $imagen1->setExtensions(array('jpg', 'jpeg', 'png', 'gif'));
				$imagen1->setMaxSize('1M');
				if($imagen1->isUploaded()){
					$nombre1 = $imagen1->saveRandom();
		      $ruta1 = 'upload/'.$nombre1;
				}
				else{
					//$errores[] = 'Error con la imagen del campo 1';
				}
			}
			else{
				//El script nunca se va a ejecutar ya que es obligatoria la imagen 1
				Flash::error('La primera imagen es obligatoria');
				$ruta1 = ''; //El cual nunca debería ejecutarse
			}
			if(!empty($_FILES['imagen2']['name'])){
				$imagen2 = Upload::factory('imagen2', 'image');
		    $imagen2->setExtensions(array('jpg', 'jpeg', 'png', 'gif'));
				$imagen2->setMaxSize('1M');
				if($imagen2->isUploaded()){
					$nombre2 = $imagen2->saveRandom();
		      $ruta2 = 'upload/'.$nombre2;
				}
				else{
					Flash::error('Error con la imagen 2');
					//return false;
				}
			}
			else{
				$ruta2 = '';
			}
			if(!empty($_FILES['imagen3']['name'])){
				$imagen3 = Upload::factory('imagen3', 'image');
		    $imagen3->setExtensions(array('jpg', 'jpeg', 'png', 'gif'));
				$imagen3->setMaxSize('1M');
				if($imagen3->isUploaded()){
					$nombre3 = $imagen3->saveRandom();
		      $ruta3 = 'upload/'.$nombre3;
				}
				else{
					Flash::error('Error con la imagen 3');
					//return false;
				}
			}
			else{
				$ruta3 = '';
			}
					$producto = (new Publicaciones);
		      $producto->nombre = Input::post('nombre');
		      $producto->descripcion = Input::post('descripcion');
		      $producto->descripcionl = Input::post('descripcionl');
					$producto->categoria = Input::post('categoria');
		      $producto->precio = Input::post('precio');
		      $producto->oferta = Input::post('oferta');
		      $producto->fecha = date('d-m-Y, g:i a');
		      $producto->tipo = Input::post('tipo');
					$producto->cantidad = Input::post('cantidad');
		      $producto->proveedor_id = Input::post('proveedor_id');
		      $producto->imagen1 = $ruta1;
		      $producto->imagen2 = $ruta2;
		      $producto->imagen3 = $ruta3;
					if($producto->save()){
					 return true;
				 	}
					else{
						return false;
					}
	}

	public function imagen($id)
	{
		//Edicion de las imagenes de las publicaciones
		$ruta = array();
		$publicacion = (New Publicaciones)->find($id);
		if(!empty($_FILES['imagen1']['name'])){
			//Si existe alguna imagen en la variables $_FILES
			if($publicacion->imagen1 != null){
				unlink('/home/u873180141/public_html/img/'.$publicacion->imagen1); //Al cambiar de servidor cambiar la url absoluta
				$imagen1 = Upload::factory('imagen1', 'image');
		    $imagen1->setExtensions(array('jpg', 'png', 'gif'));
				$imagen1->setMaxSize('1M');
				$imagen1->isUploaded();
				$nombre1 = $imagen1->saveRandom();
				$ruta[0] = 'upload/'.$nombre1;
			}
		}
		if(!empty($_FILES['imagen2']['name'])){
			if($publicacion->imagen2 != null){
				unlink('/home/u873180141/public_html/img/'.$publicacion->imagen2); //Cambiar por URL absoluta de servidor
				$imagen2 = Upload::factory('imagen2', 'image');
		    $imagen2->setExtensions(array('jpg', 'png', 'gif'));
				$imagen2->setMaxSize('1M');
				$imagen2->isUploaded();
				$nombre2 = $imagen2->saveRandom();
				$ruta[1] = 'upload/'.$nombre2;
			}
			else{
				$imagen2 = Upload::factory('imagen2', 'image');
		    $imagen2->setExtensions(array('jpg', 'png', 'gif'));
				$imagen2->setMaxSize('1M');
				$imagen2->isUploaded();
				$nombre2 = $imagen2->saveRandom();
				$ruta[1] = 'upload/'.$nombre2;
			}
		}
		if(!empty($_FILES['imagen3']['name'])){
			if($publicacion->imagen3 != null){
				unlink('/home/u873180141/public_html/img/'.$publicacion->imagen3); //Cambiar por URL absoluta de servidor
				$imagen3 = Upload::factory('imagen3', 'image');
		    $imagen3->setExtensions(array('jpg', 'png', 'gif'));
				$imagen3->setMaxSize('1M');
				$imagen3->isUploaded();
				$nombre3 = $imagen3->saveRandom();
				$ruta[2] = 'upload/'.$nombre3;
			}
			else{
				$imagen3 = Upload::factory('imagen3', 'image');
		    $imagen3->setExtensions(array('jpg', 'png', 'gif'));
				$imagen3->setMaxSize('1M');
				$imagen3->isUploaded();
				$nombre3 = $imagen3->saveRandom();
				$ruta[2] = 'upload/'.$nombre3;
			}
		}
		return $ruta;
	}

	public function eliminar($id)
	{
		$publicacion = (New Publicaciones)->find($id);
		if($publicacion->imagen1 != null){
			unlink('/home/u873180141/public_html/img/'.$publicacion->imagen1); //Pasar direccion absoluta de la imagen
		}
		if($publicacion->imagen2 != null){
			unlink('/home/u873180141/public_html/img/'.$publicacion->imagen2); //Pasar direccion absoluta de la imagen
		}
		if($publicacion->imagen3 != null){
			unlink('/home/u873180141/public_html/img/'.$publicacion->imagen3); //Pasar direccion absoluta de la imagen
		}

		//Borrar todo los datos en el artículo
		if($publicacion->delete($id)){
			return true; //retornar verdadero
		}
		else{
			return false;
		}
	}

	public function editarimagen($id)
	{
		$publicacion = (New Publicacion)->find($id);
		$datos = array();
		if($_FILES['imagen1']){
				if($publicacion->imagen != null){
					$path = PUBLIC_PATH.'img/upload/'.$publicacion->imagen1;
					unlink($path);
				}
				else{
					$imagen1 = Upload::factory('imagen1', 'image');
	    			$imagen1->setExtensions(array('jpg', 'png', 'gif'));
	    			$imagen1->isUploaded();
	    			$nombre1 = $imagen1->saveRandom();
	    			$ruta1 = PUBLIC_PATH.'img/upload'.$nombre1;
	    			$datos[0] = $ruta1;
				}
			}
		if($_FILES['imagen2']){
				if($publicacion->imagen != null){
					$path = PUBLIC_PATH.'img/upload/'.$publicacion->imagen1;
					unlink($path);
				}
				else{
					$imagen2 = Upload::factory('imagen2', 'image');
	    			$imagen2->setExtensions(array('jpg', 'png', 'gif'));
	    			$imagen2->isUploaded();
	    			$nombre2 = $imagen2->saveRandom();
	    			$ruta2 = PUBLIC_PATH.'img/upload'.$nombre2;
	    			$datos[1] = $ruta2;
				}
		}
		if($_FILES['imagen3']){
			if($publicacion->imagen != null){
				$path = PUBLIC_PATH.'img/upload/'.$publicacion->imagen1;
				unlink($path);
			}
			else{
				$imagen3 = Upload::factory('imagen3', 'image');
					$imagen3->setExtensions(array('jpg', 'png', 'gif'));
					$imagen3->isUploaded();
					$nombre3 = $imagen3->saveRandom();
					$ruta3 = PUBLIC_PATH.'img/upload'.$nombre3;
					$datos[2] = $ruta3;
			}
		}

	}

	public function encontrar($tipo, $categoria, $comuna) //$region $comuna
	{
		$datos = (New Publicaciones)->find_all_by_sql("SELECT  p.id, p.imagen1 as imagen, p.nombre from publicaciones p, comunas c where tipo = '$tipo' and categoria = '$categoria' and comuna = '$comuna' and p.proveedor_id = c.proveedor_id");
		return $datos;
	}
}
?>
