<?php  

/**
* Controller para todas las funcionalidades de la empresa
* Incluye portar datos desde Excel hasta PHP y MySQL
*/
class EmpresaController extends AppController
{
	function before_filter()
	{
		View::template('empresa');
	}
	
	public function index()
	{
		//Panel principal de la empresa
	}

	public function administrar()
	{
		//Donde se exportaran los empleados desde Excel a la BD de UWorks
	}

	public function perfil()
	{
		//Carga de datos del perfil de la empresa, así como de sus categorías
	}

	public function comprar()
	{
		//Entrada para la compra de puntos por parte de la empresa
	}


}

?>