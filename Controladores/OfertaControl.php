<?php
use Slim\Http\Request;
use Slim\Http\Response;

class OfertaControl{

  function postOferta(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $oferta = new Oferta;
          $oferta->nombre           =   $data['nombre'];
          $oferta->descripcion      =   $data['descripcion'];
          $oferta->precio           =   $data['precio'];
          $oferta->estado           =   $data['estado'];
          $oferta->domicilio        =   $data['domicilio'];
          $oferta->precioDomicilio  =   $data['precioDomicilio'];
          $oferta->idUsuario        =   $data['idUsuario'];
          $oferta->idPeticion       =   $data['idPeticion'];
          $oferta->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);

          //ENVIAR NOTIFICACION A CLIENTES
          $c = new ClienteControl();
          $dataCliente = $c->getIdPushByPeticionCliente($data['idPeticion']);
          if (count($dataCliente) > 0){
              $titulo = "Respondieron tu peticion";
              $mensaje = $dataCliente['nombrePeticion'];
              $std = 1;
              enviarNotificacion(array($dataCliente['idPush']),$titulo, $mensaje, $std);
          }

      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar oferta", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function OfertasDePeticiones(Request $request, Response $response){
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $pet = Peticion::select("id","nombre","descripcion")->where("idCliente","=",$email)->get();
      $data = null;
      if(count($pet) == 0){
          $response = $response->withStatus(404);
      }else{
          foreach ($pet as $item) {
              $ofe = Oferta::select("empresa.nombre as empresa","empresa.foto as fotoEmpresa",
                                    "sucursal.nombre as sucursal","sucursal.direccion","sucursal.latitud","sucursal.longitud",
                                    "oferta.*")
                              ->join("usuario","usuario.id","=","oferta.idUsuario")
                              ->join("sucursal","sucursal.id","=","usuario.idSucursal")
                              ->join("empresa","sucursal.idEmpresa","=","empresa.email")
                              ->where("idPeticion","=",$item['id'])->get();
              formatearFecha($ofe);
              $data[] = array("Peticion" => $item, "Ofertas" => $ofe);
          }
      }
      $response->getBody()->write(json_encode($data));
      return $response;
  }

  function ofertasByUsuario(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $id = $request->getAttribute('id');
      $data = Oferta::where("idUsuario","=",$id)->get();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function putFoto(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $idOferta = $request->getAttribute('id');
      $data = $request->getBody();
      try{
          $fp = fopen( $idOferta.'.jpg', 'wb' );
          fwrite( $fp, $data);
          fclose( $fp );
          $fo = new FotosOferta;
          $fo->foto     =   $rutaServidor."fotos/".$idOferta.".jpg";
          $fo->idOferta =   $idOferta;
          $fo->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar foto oferta", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

}
