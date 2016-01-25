<?php
use Slim\Http\Request;
use Slim\Http\Response;

class PromocionControl{

  function postPromocion(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $promocion = new Promocion;
          $promocion->nombre          =   $data['nombre'];
          $promocion->descripcion     =   $data['descripcion'];
          //$promocion->foto            =   $data['foto'];
          $promocion->fechaFinal      =   $data['fechaFinal'];
          $promocion->idUsuario       =   $data['idUsuario'];
          $promocion->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);

          //INSERTAR CATEGORIAS EN CATEGORIASPROMOCION
          try{
              for ($i=0; $i < count($data['categorias']) ; $i++) {
                  $cp = new CategoriasPromocion;
                  $cp->idCategoria = $data['categorias'][$i];
                  $cp->idPromocion = $promocion->id;
                  $cp->save();
              }
          }catch(Exception $err){
          }

          //ENVIAR NOTIFICACION A LOS CLIENTES
          $c = new ClienteControl();
          $dataCliente = $c->getIdPushByCategorias($data['categorias']);
          if (count($dataCliente) > 0){
              $titulo = "Nueva promocion";
              $mensaje = $data['nombre'];
              $std = 1;
              enviarNotificacion($dataCliente,$titulo, $mensaje, $std);
          }

      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar promocion", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function putFoto(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $idPromocion = $request->getAttribute('id');
      $data = $request->getBody();
      try{
          $fp = fopen( $idPromocion.'.jpg', 'wb' );
          fwrite( $fp, $data);
          fclose( $fp );
          $fo = Promocion::where('id','=',$idPromocion)->first();
          $fo->foto       =   $rutaServidor."fotos/".$idPromocion.".jpg";
          $fo->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar foto promocion", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function promocionesByUsuario(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $id = $request->getAttribute('id');
      $data = Promocion::where("idUsuario","=",$id)->get();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function promocionesByCategorias(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      $data = Promocion::select("promocion.*")
                        ->join("categoriaspromocion","categoriaspromocion.idPromocion","=","promocion.id")
                        ->whereIn("categoriaspromocion.idCategoria",$data['categorias'])
                        ->distinct()
                        ->get();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }
}
