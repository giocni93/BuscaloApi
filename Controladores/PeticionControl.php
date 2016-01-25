<?php
use Slim\Http\Request;
use Slim\Http\Response;

class PeticionControl{

  function postPeticion(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $peticion = new Peticion;
          $peticion->nombre         =   $data['nombre'];
          $peticion->descripcion    =   $data['descripcion'];
          $peticion->idCategoria    =   $data['idCategoria'];
          $peticion->idCliente      =   $data['idCliente'];
          $peticion->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);

          //ENVIAR NOTIFICACION A EMPRESAS
          $u = new UsuarioControl();
          $dataUsuarios = $u->getIdPushByCategorias($data['idCategoria']);
          if (count($dataUsuarios) > 0){
              $titulo = "Nueva peticion";
              $mensaje = $data['nombre'];
              $std = 1;
              enviarNotificacion($dataUsuarios,$titulo, $mensaje, $std);
          }

      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar peticion", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function peticionesByCliente(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = Peticion::select("peticion.*","categoria.nombre as categoria")
                        ->join("categoria","categoria.id","=","peticion.idCategoria")
                        ->where("peticion.idCliente","=",$email)
                        ->get();
      formatearFecha($data);
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function peticionesByCategoria(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $categoria = $request->getAttribute('id');
      $data = Peticion::where("idCategoria","=",$categoria)->get();
      formatearFecha($data);
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function putFoto(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $idPeticion = $request->getAttribute('id');
      $data = $request->getBody();
      try{
          $fp = fopen( $idPeticion.'.jpg', 'wb' );
          fwrite( $fp, $data);
          fclose( $fp );
          $fo = new FotosPeticion;
          $fo->foto       =   $rutaServidor."fotos/".$idPeticion.".jpg";
          $fo->idPeticion =   $idPeticion;
          $fo->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar foto peticion", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

}
