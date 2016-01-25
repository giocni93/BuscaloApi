<?php
use Slim\Http\Request;
use Slim\Http\Response;

class EmpresaControl{

  function postEmpresas(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $data = json_decode($request->getBody(),true);
      try{
          $empresa = new Empresa;
          $empresa->email     =   $data['email'];
          $empresa->nombre    =   $data['nombre'];
          $empresa->pass      =   $data['pass'];
          $empresa->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "La empresa ya existe", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function putFoto(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $email = $request->getAttribute('email');
      $data = $request->getBody();
      try{
          $fp = fopen( $email.'_E.jpg', 'wb' );
          fwrite( $fp, $data);
          fclose( $fp );
          $empresa = Empresa::where('email', '=', $email)->first();
          $empresa->foto     =   $rutaServidor."fotos/".$email."_E.jpg";
          $empresa->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "Error al guardar foto empresa", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

  function postCalificacion(Request $request, Response $response) {
      $response = $response->withHeader('Content-type', 'application/json');
      $idEmpresa = $request->getAttribute('email');
      $data = json_decode($request->getBody(),true);
      try{
          $ce = new CalificacionEmpresa;
          $ce->calificacion  =   $data['calificacion'];
          $ce->idEmpresa    =   $idEmpresa;
          $ce->save();
          $respuesta = json_encode(array('msg' => "Guardado correctamente", "std" => 1, "obj" => $data));
          $response = $response->withStatus(200);
      }catch(Exception $err){
          $respuesta = json_encode(array('msg' => "erro al calificar", "std" => 0, "err" => $err->getMessage()));
          $response = $response->withStatus(404);
      }
      $response->getBody()->write($respuesta);
      return $response;
  }

}
