<?php

/**
 * Modelo para accesar a todos los datos de compras del colaborador
 */
class Compras extends ActiveRecord
{
  //Agregar datos al carro de compras
  public function agregar($articulo, $cantidad = 1)
  {
    //Agrega al carrito
    if($articulo->oferta == null){
      $precio = $articulo->precio;
    }
    else{
      $precio = $articulo->oferta;
    }
    if($articulo->cantidad > 0){
      //Aquí el seteo de la variable
    }
    else{
      return 'No se puede realizar compra de este producto/servicio ya que no se
      dispone en Stock'; //Limpiar mejor esta opción
    }

    $producto = array(
                "id"			=>		  $articulo->id,
                "cantidad"		=>	$cantidad,
                "precio"		=>		$precio,
                "nombre"		=>		$articulo->nombre
              );

    return $producto;

  }

}


?>
