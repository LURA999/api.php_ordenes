<?php


include "Conn.php";

$dbConn =  connect($db);

if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
      $sql = $dbConn->prepare("CALL sp_deleteUsuario('".$_GET['cve']."')");
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode( array("error"=>false));
      exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
      if(isset($_GET['cve'])){
            $sql = $dbConn->prepare("CALL sp_selectUsuarios('".$_GET['cve']."')");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
      }

      if(isset($_GET['uncorreo']) == true){
            $sql = $dbConn->prepare("CALL sp_selectCorreo(".$_GET['cv'].")");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
        }

      if(isset($_GET['tipo_usuario'])){
            $sql = $dbConn->prepare("CALL sp_selectUsuarios('".$_GET['cve']."')");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
      }
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
      $body = file_get_contents('php://input');
      $input = json_decode($body,true);
      $sql = $dbConn->prepare("CALL sp_insertarUsuario('".$input["nombre"]."','".$input["email"]."',
       ".$input["nivel"].",'".password_hash($input["password"], PASSWORD_DEFAULT)."')");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      // header("HTTP/1.1 200 OK");
      // echo json_encode( $sql->fetchAll());
      $usuario = $sql->fetchAll();
      echo($usuario[0]['cve_usuario']);
      foreach($input['ciudades'] as $ciudad){
            echo($ciudad['idciudad']);
            echo($usuario[0]['cve_usuario']);
           $sql = $dbConn->prepare("CALL sp_insertarUsuarioCiudad(".$usuario[0]['cve_usuario'].",".$ciudad['idciudad'].")");
           $sql->execute();
      }
      exit();
}


if($_SERVER['REQUEST_METHOD'] == 'PATCH')
{
      $body = file_get_contents('php://input');
      $input = json_decode($body,true);
      if(isset($input["password"])){
            $sql = $dbConn->prepare("CALL sp_updateContrasena(".$input["cve_usuario"].",'".password_hash($input["password"],PASSWORD_DEFAULT)."');");
            $sql->execute();
            header("HTTP/1.1 200 OK");
      }     
      if(isset($input["email"])){
            $sql = $dbConn->prepare("CALL sp_updateCorreo(".$input["cve_usuario"].",'".$input["email"]."');");
            $sql->execute();
            header("HTTP/1.1 200 OK");
      }  
      if(isset($input["nivel"])){
            $sql = $dbConn->prepare("CALL sp_updateNivel(".$input["cve_usuario"].",'".$input["nivel"]."');");
            $sql->execute();
            header("HTTP/1.1 200 OK");
      }  
      if(isset($input["ultima_sesion"])){
            $sql = $dbConn->prepare("CALL sp_updateUltimaSesion(".$input["cve_usuario"].");");
            $sql->execute();
            header("HTTP/1.1 200 OK");
      }  
      exit();
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>