<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        $sql = $dbConn->prepare("CALL sp_selectSucursales('".$_GET['cve_cliente']."')");
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
       $sql = $dbConn->prepare("CALL sp_insertSucursal('".$input['nombre']."',
       '".$input['calle']."',
       '".$input['numero']."' ,
       '".$input['colonia']."',
       '".$input['ciudad']."',
       '".$input['estado']."',
       '".$input["cp"]."',
       '".$input['servicio']."',
       ".$input['idcliente'].",
       '".$input['coordenadas']."')");
      $sql->execute();
      header("HTTP/1.1 200 OK");
      exit();
}


?>