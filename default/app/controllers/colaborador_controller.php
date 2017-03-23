<?php

/**
* Controller para los colaboradores (Empleados)
*/
class ColaboradorController extends AppController
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
				$this->items = New Carrito;
				//$this->cantidad = (New Carrito)->get_content();
	    }
	}

	public function entrar()
	{
		//Login del form para ingresar al sitio
		//Usar el template del landing
		View::template('default');
		//Login del proveedor
		if(Input::hasPost("correo", "clave")){
	      $auth = New Colaborador;
	      if($auth->login()){
	        Redirect::to("colaborador/");
	      }
	    }
	}

	public function cambiar($id)
	{
		//Método para realizar cambio de clave del colaborador
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

	public function cerrar()
	{
		//Cierre del inicio de sesión
		View::template(null);
        (New Colaborador)->logout();
        Redirect::to('colaborador/entrar');
	}

	public function index()
	{
		//Panel de control del colaborador
		$id = Session::get('id', 'colaborador');
		if($id == null){
			Redirect::to('colaborador/entrar');
		}
	}

	public function servicios()
	{
		//Servicios que se le ofrecen al colaborador
	}

	public function productos()
	{
		//Productos que se le ofrecen al colaborador
	}

	public function perfil($id = null)
	{
		//Perfil del colaborador
		if($id == null){
			//Si la identidad es nula entonces redireccionar al Login
			Redirect::to('colaborador/entrar');
		}
		//Carga de BD de datos del proveedor
		$this->colaborador = (New Colaborador)->find($id); //Crear el acceso a ->perfil($id)
		$this->compras = (New Ventas)->find_by_proveedor_id($id);
	}

	public function editar($id)
	{
		//Editar el perfil del colaborador
		$colaborador = (New Colaborador)->find($id);
		$this->colaborador = $colaborador;
		if(Input::hasPost('id')){
			//Si envía el formulario entonces se edita los datos
			$colaborador->nombre = Input::post('nombre');
			$colaborador->correo = Input::post('correo');
			$colaborador->correo2 = Input::post('correo2');
			$colaborador->telefono = Input::post('telefono');
			$colaborador->telefono2 = Input::post('telefono2');
			$colaborador->comuna = Input::post('comuna');
			if($colaborador->save()){
				//Si se guardo
				Flash::valid('Se ha editado sus datos exitosamente');
			}
			else{
				Flash::error('No se ha podido editar sus datos correctamente');
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
			$resultado = (New Colaborador)->enviar($correo);
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
		$datos = (New Colaborador)->find_by_codigo($codigo);
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

	public function categorias()
	{
		//Categorias de servicios
	}

	public function resultados()
	{
		//Resultados de todos los productos según la busqueda
		$tipo = Input::post('tipo');
		$servicios = Input::post('servicios');
		//$region = Input::post('region');
		$comuna = Input::post('comunas');
		$this->resultados = (New Publicaciones)->encontrar($tipo, $servicios, $comuna);
	}

	public function detalles($id)
	{
		//Detalles del producto o servicio que se vaya a solicitar mediante un ID
		$datos = (New Publicaciones)->find($id);
		//Datos del proveedor
		$proveedor = (New Proveedor)->find($datos->proveedor_id);
		//Datos de la comuna
		$comunas = (New Comunas)->find_all_by_proveedor_id($proveedor->id);
		$this->datos = $datos;
		$this->proveedor = $proveedor;
		$this->comunas = $comunas;
	}
}

?>
