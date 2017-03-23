<?php

/**
* Modelo de acceso a los datos del proveedor y toda la logica del producto/servicio
*/
class Proveedor extends ActiveRecord
{

	//Métodos de sesión para el proveedor
	//
	//Inicio de métodos para el control de sesiones de los usuarios

    public function login()
    {
      $auth = Auth2::factory('model');
      $auth->setModel('proveedor');
      $auth->setLogin('correo');
      $auth->setPass('clave');
      $auth->setAlgos('md5');
      $auth->setSessionNamespace('proveedor');
      $auth->setFields(array('id', 'nombre'));

      if($auth->identify()) return true;

      Flash::error($auth->getError());
      return false;
    }

    public function logout()
    {
      Auth2::factory('model')->logout();
    }

    public function logged()
    {
      return Auth2::factory('model')->isValid();
    }

    //Fin de los metodos

    public function registrar()
    {
    		$usuario = (New Proveedor);
      	$usuario->nombre = Input::post('nombre');
      	$usuario->empresa = Input::post('empresa');
	    	$usuario->correo = Input::post('correo');
	    	$usuario->web = Input::post('web');
	    	$usuario->telefono = Input::post('telefono');
      	$usuario->direccion = Input::post('direccion');
      	$usuario->region = Input::post('region');
      	//$usuario->comuna = Input::post('comuna'); Este codigo se debe ejecutar
      	$usuario->delivery = Input::post('delivery');
      	$usuario->rut = Input::post('rut');
    		$usuario->clave = md5(Input::post('clave').'uW0rK5');
      	$correo = Input::post('correo'); //Correo para mandar notificación al proveedor de su registro
      	$clave = Input::post('clave'); //Clave para enviarle la clave en formato texto

      	if($usuario->save()){
          $numero = $_POST["comuna"];
              $count = count($numero);
              for ($i = 0; $i < $count; $i++) {
                  $comuna = (New Comunas);
                  $comuna->proveedor_id = $usuario->id;
                  $comuna->comuna = $numero[$i];
                  $comuna->save();
              }
      		return true;
      	}
      	else{
      		return false;
      	}

      	//Verdadero finalidad del registro notificar mediante un correo al proveedor de que ya se encuentra registrado.

	      /*$buscar = (New Proveedor)->find_by_correo(Input::post('correo'));
	      if(!$buscar){
	        if($usuario->save()){
	          if(Email::registro($correo, $clave)){
	            return true;
	          }
	        }
	      }
          else{
            return false;
          }*/
    }

    public function editar($id)
    {
      $proveedor = (New Proveedor)->find($id);
      $proveedor->save(Input::post('proveedor'));
      //Editar perfil del proveedor mediante un ID
      $resultado = (New Comunas)->delete_all("proveedor_id = $id");
      //return $resultado;
      $comunas = $_POST['comuna'];
      $count = count($comunas);
      for ($i=0; $i < $count; $i++) {
        $datos = (New Comunas);
        $datos->proveedor_id = $id;
        $datos->comuna = $comunas[$i];
        $datos->save();
      }
      return true;
    }

    public function perfil($id)
    {
      //Codigo para mostrar todo el perfil del proveedor
      //$resultado = (New Proveedor)->find_by_sql("");
    }

		public function enviar($correo)
		{
			//Metodo para enviar correo al usuario con codigo

			//Genera codigo aleatorio para este usuario
			$codigo = md5(uniqid().rand(1,10000));
			//Consulta a quien pertenece el correo
			$datos = (New Proveedor)->find_by_correo($correo);
			$datos->codigo = $codigo;
			$datos->save();
			//Termino de ingresar el codigo a la BD
      $from = "recuperacion@uworks.tk";
			$header = "From:" . $from . "\nReply-To:" . $from . "\n";
			$header = $header . "X-Mailer:PHP/" . phpversion() . "\n";
			$header = $header . "Mime-Version: 1.0\n";
			$header = $header . "Content-Type: text/html";

			$asunto = "Recuperación de contraseña";
			$html = "Para poder recuperar el correo de clic en el siguiente enlace
			 <a href='http://www.uworks.tk/proveedor/restaurar/$codigo'>Reestablecer contraseña</a>
			 de cualquier forma sino logra entrar a la URL contactarse por el formulario de contacto
			 , no responder este correo ya que es un correo automatizado.";
			$destino = $correo;
			mail($destino, $asunto, $html, $header) or die("Su mensaje no pudo enviarse.");
      return true;
		}

    public function conversion($objeto)
    {
      //Metodo para saber cuales son los checkbox que están marcados.
      $arreglo = array();
      foreach ($objeto as $key) {
        $arreglo[] = $key->comuna;
      }
      return $arreglo;
    }

}

?>
