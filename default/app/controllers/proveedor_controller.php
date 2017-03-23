<?php

/**
* Controller de proveedor se hacen todas las gestiones
* y flujo de trabajo del módulo
*/
class ProveedorController extends AppController
{
	public function before_filter()
	{
		View::template('proveedor');
		$valido = New Proveedor;
	    $accion = Router::get('action');
	    if(!$valido->logged() and $accion != 'entrar' and $accion != 'registrar'
			and $accion != 'recuperar' and $accion != 'restaurar' and $accion != 'contrato'){
	      Redirect::to("proveedor/entrar");
	    }
	    else{
	    	$this->identidad = Session::get('id', 'proveedor');
	    	$this->nombrep = Session::get('nombre', 'proveedor');
	    }
	}

	public function index()
	{
		//Panel del proveedor
		$id = Session::get('id', 'proveedor');
		$usuario = (New Proveedor)->find($id);
		$this->usuario = $usuario;
	}

	public function entrar()
	{
		//Usar el template del landing
		View::template('default');
		//Login del proveedor
		if(Input::hasPost("correo", "clave")){
	      $auth = New Proveedor;
	      if($auth->login()){
	        Redirect::to("proveedor/");
	      }
	    }
	}

	public function registrar()
	{
		//Registro del proveedor a la BD
		//Se debe agregar el checkbox de terminos y condiciones y debe ser un enlace tipo _blank
		View::template('default');
		if(Input::hasPost('correo')){
			if((New Proveedor)->registrar()){
				Flash::valid('Registro exitoso, ya puede iniciar sesión con su cuenta, lo estamos redirigiendo al inicio de sesión');
          		Input::delete();
          		Redirect::to('', '5');
			}
			else{
				Flash::error('No se pudo registrar, intente más tarde o comuniquese con soporte');
			}
		}
	}

	public function editar($id)
	{
		$this->nombre = array('Cerrillo' => 'cerrillo', 'Cerro Navía'=>'cerro navia', 'Conchalí'=>'conchali', 'El Bosque'=>'el bosque', 'Estación Central'=>'estacion central', 'Huechuraba'=>'huechuraba', 'Independencia'=>'independencia', 'La Cisterna'=>'la cisterna', 'La Florida'=>'la florida', 'La Pintana'=>'la pintana', 'La Granja'=>'la granja', 'La Reina'=>'la reina', 'Las Condes'=>'las condes', 'Lo Barnechea'=>'lo barnechea', 'Lo Espejo'=>'lo espejo', 'Lo Prado'=>'lo prado', 'Macúl'=>'macul', 'Maipú'=>'maipu', 'Ñuñoa'=>'nunoa', 'Pedro Aguirre Cerca'=>'pedro aguirre cerca', 'Peñalolen'=>'penalolen', 'Providencia'=>'providencia', 'Pudahuel'=>'pudahuel', 'Quilicura'=>'quilicura', 'Quinta Normal'=>'quinta normal', 'Recoleta'=>'recoleta', 'Renca'=>'renca', 'San Miguel'=>'san miguel', 'San Joaquín'=>'san joaquin', 'San Ramón'=>'san ramon', 'Santiago'=>'santiago', 'Vitacura'=>'vitacura');
		$this->tipo = array('SI'=>'SI', 'NO'=>'NO');
		//Editar el perfil del proveedor según el identificador
		$this->proveedor = (New Proveedor)->find($id);
		$datos = (New Comunas)->find_all_by_proveedor_id($id);
		$this->comunas = (New Proveedor)->conversion($datos); //Retorna un array
		//Inicia la edición con el envío de los datos del form
		if(Input::hasPost('id')){
			//Si el formulario tiene submit
			$datos = (New Proveedor)->editar($id);
			if($datos){
				//Redireccionar a la pagina principal de la web
				Flash::valid('Su perfil fue editado correctamente');
				Redirect::to("proveedor/perfil/$id", '3');
			}
		}
	}

	public function contrato()
	{
		//Es la vista de contrato
	}

	//ID del proveedor para carga de BD
	public function perfil($id = '')
	{
		//Perfil del proveedor

		//Carga de BD de datos del proveedor
		$this->proveedor = (New Proveedor)->find($id); //Crear el acceso a ->perfil($id)
		$this->comunas = (New Comunas)->find_all_by_proveedor_id($id);
		$this->ventas = (New Ventas)->find_by_proveedor_id($id);
		if($id == null){
			//Si la identidad es nula entonces redireccionar al Login
			Redirect::to('proveedor/entrar');
		}

	}

	public function cambiar($id)
	{
		//Cambiar clave del proveedor
		if(Input::hasPost('antiguo')){
			//Validar si la clave antigua es igual a la actual
			$antiguo = Input::post('antiguo');
			$antiguo = md5($antiguo.'uW0rK5');
			$proveedor = (New Proveedor)->find($id);
			if($proveedor->clave != $antiguo){
				Input::delete();
				Flash::error('Usted introdujo su clave actual de forma incorrecta, verifique y vuelva a intentar');
			}
			else{
				if(Input::post('clave') === Input::post('clave2')){
					$cambio = Input::post('clave');
					$proveedor->clave = md5($cambio.'uW0rK5');
					if($proveedor->save()){
						//Guardar datos una vez que verifique que ambas claves son iguales y la actual es correcta
						Input::delete();
						Flash::valid('Su clave has sido actualizada exitosamente');
					}
				}
				else{
					Input::delete();
					Flash::error('Las 2 claves no son iguales, verifique que sean iguales e introduzcalas de nuevo');
				}
			}
		}
	}

	public function recuperar()
	{
		//Abrir en el template por defecto
		View::template('default');
		if(Input::hasPost('correo')){
			//Si detecta el envio del formulario
			$correo = Input::post('correo');
			$resultado = (New Proveedor)->enviar($correo);
			if($resultado){
				//Si envio el codigo al correo es exitoso
				Input::delete();
				Flash::valid('Se le ha enviado un correo con las instrucciones');
			}
			else{
				Flash::error('No se ha podido enviar el correo');
			}
		}

	}

	public function restaurar($codigo)
	{
		View::template('default');
		//Recuperar clave olvidada
		$datos = (New Proveedor)->find_by_codigo($codigo);
		if($datos->codigo){
			//Mostrar el formulario
			if(Input::hasPost('clave')){
				//Verificar que las 2 claves sean iguales
				$clave = Input::post('clave');
				$clave2 = Input::post('clave2');
				if($clave != $clave2){
					//Si no son iguales
					Input::delete();
					Flash::error('Las 2 claves que ingreso no son iguales, verifique e intente de nuevo.');
				}
				else{
					$datos->clave = md5($clave.'uW0rK5');
					$datos->codigo = "";
					if($datos->save()){
						//Verifica si guarda
						Input::delete();
						Flash::valid('La restauración de su contraseña ha sido exitoso, ya
						puede ingresar a su panel de usuario');
					}
				}
			}
		}
		else{
			//Redireccionar a otro
			Redirect::to('proveedor');
		}
	}

	//ID del proveedor para carga de BD
	public function pedidos($id)
	{
		//Carga los datos de pedidos, crear tabla de pedidos
		$this->pedidos = (New Pedidos)->find_by_proveedor_id($id);
	}

	//Lleva ID del proveedor para hacer carga de BD
	public function detalles($id)
	{
		//Detalles del pedido con los datos del trabajador que solicita el producto/servicio
		//Se cargan 4 tablas en la web imagenes, publicaciones, colaborador, comunas.
	}


	public function publicaciones() //Lleva ID del proveedor para hacer carga de BD
	{
		//El ID del proveedor se utiliza para asociar y buscar todos los resultados de todas sus publicaciones
		//Paso a ser totalmente otro modulo para generar mejor el proceso de visualizacion y carga de datos en la vistas

	}

	public function cerrar()
	{
		//Cierre de sesión
		View::template(null);
        (New Proveedor)->logout();
        Redirect::to('proveedor/entrar');
	}
}

?>
