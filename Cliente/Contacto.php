<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        $sql = $dbConn->prepare("CALL sp_selectContactosSucursal('".$_GET['cve_sucursal']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
      $body = file_get_contents('php://input');
      $input = json_decode($body,true);
       $sql = $dbConn->prepare("CALL sp_insertContacto('".$input['nombre']."',
       '".$input['telefono']."',
       '".$input['extension']."' ,
       '".$input['correo']."',
       ".$input['idsucursal'].")");
      $sql->execute();
      header("HTTP/1.1 200 OK");
      exit();
}

header("HTTP/1.1 400 Bad Request");
?>