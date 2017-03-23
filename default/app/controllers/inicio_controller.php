<?php  

/**
* Controller Inicio Landing Page
*/
class InicioController extends AppController
{
	
	public function index()
	{
		Redirect::to(index);
	}

	public function nosotros()
	{

	}

	public function servicios()
	{

	}

	public function blog()
	{
		//Carga de datos de noticias
	}

	public function equipo()
	{
		//Vista del equipo de trabajo
	}

	public function contacto()
	{
		//Envio de contacto
		if(Input::hasPost('motivo')){
			//Captura de datos
		}
	}
}

?>