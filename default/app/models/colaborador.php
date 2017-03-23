<?php

/**
* Modelo de acceso a los datos del colaborador
*/
class Colaborador extends ActiveRecord
{

	//Métodos de sesión para el colaborador
	//
	//Inicio de métodos para el control de sesiones de los usuarios

    public function login()
    {
      $auth = Auth2::factory('model');
      $auth->setModel('colaborador');
      $auth->setLogin('correo');
      $auth->setPass('clave');
      $auth->setAlgos('md5');
      $auth->setSessionNamespace('colaborador');
      $auth->setFields(array('id', 'nombre', 'puntos'));

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

		//Finaliza los metodos del login

    //Restauración de la clave del usuario
    public function enviar($correo)
		{
			//Metodo para enviar correo al usuario con codigo

			//Genera codigo aleatorio para este usuario
			$codigo = md5(uniqid().rand(1,10000));
			//Consulta a quien pertenece el correo
			$datos = (New Colaborador)->find_by_correo($correo);
			$datos->codigo = $codigo;
			$datos->save();
			//Termino de ingresar el codigo a la BD
      $from = "recuperacion@uworks.cl";
			$header = "From:" . $from . "\nReply-To:" . $from . "\n";
			$header = $header . "X-Mailer:PHP/" . phpversion() . "\n";
			$header = $header . "Mime-Version: 1.0\n";
			$header = $header . "Content-Type: text/html";

			$asunto = "Recuperación de contraseña";
			$html = "Para poder recuperar el correo de clic en el siguiente enlace
			 <a href='http://proveedores.uworks.cl/colaborador/restaurar/$codigo'>Reestablecer contraseña</a>
			 de cualquier forma sino logra entrar a la URL contactarse por el formulario de contacto
			 , no responder este correo ya que es un correo automatizado."; //Al cambiar de servidor cambiar URL de la restauracion de clave
			$destino = $correo;
			mail($destino, $asunto, $html, $header) or die("Su mensaje no pudo enviarse.");
      return true;
		}

}

?>
