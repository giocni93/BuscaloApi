<?php
use Slim\Http\Request;
use Slim\Http\Response;

class ClienteControl{

  function getAllClientes(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = Cliente::all();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function putFoto(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = $request->getBody();
      try{
          $fp = fopen( $email.'_C.jpg', 'wb' );
          fwrite( $fp, $data);
          fclose( $fp );
          $cliente = Cliente::where('email', '=', $email)->first();
          $cliente->foto     =   $rutaServidor."fotos/".$email."_C.jpg";
          $cliente->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar foto cliente", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  public function getIdPushByCategorias($vec) {
      $temp = Cliente::select("cliente.email","cliente.idPush")
                        ->join("categoriascliente","cliente.email","=","categoriascliente.idCliente")
                        ->join("categoria","categoria.id","=","categoriascliente.idCategoria")
                        ->whereIn("categoria.id",$vec)
                        ->distinct()
                        ->get();
      $data = array();
      foreach ($temp as $item) {
          array_push($data,$item['idPush']);
      }
      return $data;
  }

  public function getIdPushByPeticionCliente($id) {
      $data = Cliente::select("cliente.email","cliente.idPush","peticion.nombre as nombrePeticion")
                        ->join("peticion","cliente.email","=","peticion.idCliente")
                        ->where("peticion.id","=",$id)
                        ->first();
      return $data;
  }

  function getClientes(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = Cliente::where('email', '=', $email)->first();
      if($data == null){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function postClientes(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $cliente = new Cliente;
          $cliente->email     =   $data['email'];
          $cliente->nombres   =   $data['nombres'];
          $cliente->apellidos =   $data['apellidos'];
          $cliente->pass      =   $data['pass'];
          $cliente->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "El cliente ya existe", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function putClientes(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = json_decode($request->getBody(),true);
      try{
          $cliente = Cliente::where('email', '=', $email)->first();
          $cliente->nombres   =   $data['nombres'];
          $cliente->apellidos =   $data['apellidos'];
          $cliente->pass      =   $data['pass'];
          $cliente->save();
          $respuesta = json_encode(array('msg' => "Modificado correctamente", "std" => 1, "obj" => $cliente));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al modificar", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function putIdPushByCliente(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = json_decode($request->getBody(),true);
      try{
          $cliente = Cliente::where('email', '=', $email)->first();
          $cliente->idPush   =   $data['idPush'];
          $cliente->save();
          $respuesta = json_encode(array('msg' => "Modificado correctamente", "std" => 1, "obj" => $cliente));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al modificar", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }
}
