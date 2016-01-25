<?php
use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioControl{

  function login(Request $request, Response $response){
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      $cont = 0;
      $std = 0;
      $roles = array();
      $dataCliente = Cliente::select("email","nombres","apellidos")
                      ->where('email', '=', $data['usuario'])
                      ->where('pass', '=', $data['pass'])
                      ->first();
      if($dataCliente != null){
          $cont ++;
          array_push($roles,"cliente");
      }
      $dataEmpresa = Empresa::select("email","nombre","foto")
                      ->where('email', '=', $data['usuario'])
                      ->where('pass', '=', $data['pass'])
                      ->first();
      if($dataEmpresa != null){
          $cont ++;
          array_push($roles,"empresa");
      }
      $dataUsuario = Usuario::select("id","nombres","apellidos","idSucursal","idCategoria")
                      ->where('id', '=', $data['usuario'])
                      ->where('pass', '=', $data['pass'])
                      ->first();
      if($dataUsuario != null){
          $cont ++;
          array_push($roles,"usuario");
      }
      if($cont > 0){
        $response = $response->withStatus(200);
        $std = 1;
      }else{
        $response = $response->withStatus(404);
      }
      $response->getBody()->write(json_encode(array("std" => $std, "roles" => $roles)));
      return $response;
  }

  function postUsuarios(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $usuario = new Usuario;
          $usuario->id            =   $data['id'];
          $usuario->nombres       =   $data['nombres'];
          $usuario->apellidos     =   $data['apellidos'];
          $usuario->pass          =   $data['pass'];
          $usuario->idSucursal    =   $data['idSucursal'];
          $usuario->idCategoria   =   $data['idCategoria'];
          $usuario->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "El usuario ya existe", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function putIdPushByUsuario(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $id = $request->getAttribute('id');
      $data = json_decode($request->getBody(),true);
      try{
          $usuario = Usuario::where('id', '=', $id)->first();
          $usuario->idPush   =   $data['idPush'];
          $usuario->save();
          $respuesta = json_encode(array('msg' => "Modificado correctamente", "std" => 1, "obj" => $cliente));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al modificar", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function getIdPushByCategorias($idCategoria) {
      $temp = Usuario::select("usuario.id","usuario.idPush")
                        ->where("idCategoria","=",$idCategoria)
                        ->get();
      $data = array();
      foreach ($temp as $item) {
          array_push($data,$item['idPush']);
      }
      return $data;
  }
}
