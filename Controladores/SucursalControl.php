<?php
use Slim\Http\Request;
use Slim\Http\Response;

class SucursalControl{

  function getAllSucursales(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = Sucursal::all();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function getSucursalesByEmpresa(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = Sucursal::select("sucursal.*")
                        ->join("empresa","sucursal.idEmpresa","=","empresa.email")
                        ->where("empresa.email","=",$email)
                        ->get();
      if(count($data) == 0){
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($data);
      return $response;
  }

  function postSucursales(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $sucursal = new Sucursal;
          $sucursal->nombre          =   $data['nombre'];
          $sucursal->direccion       =   $data['direccion'];
          $sucursal->latitud         =   $data['latitud'];
          $sucursal->longitud        =   $data['longitud'];
          $sucursal->idEmpresa       =   $data['idEmpresa'];
          $sucursal->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "La sucursal ya existe", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }
}
