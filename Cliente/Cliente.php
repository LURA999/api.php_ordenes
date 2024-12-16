<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
      if(isset($_GET['id_cliente'])){
            $sql = $dbConn->prepare("CALL sp_selectClienteId(".$_GET['id_cliente'].")");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
      }
        $sql = $dbConn->prepare("CALL sp_selectClientes");
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
       $sql = $dbConn->prepare("CALL sp_insertCliente('".$input['nombre']."',
       '".$input['rfc']."',
       '".$input['estado']."' ,
       '".$input['calle']."',
       ".$input['numero'].",
       '".$input['ciudad']."',
       '".$input["colonia"]."',
       '".$input['cp']."',
       '".$input['fecha_creacion']." 00:00:00',
       ".$input['id_usuario'].",
       ".$input['id_origen'].",
       ".$input['id_estado'].",
       '".$input['descripcion']."',
       '".$input['coordenadas']."',
       '".$input['estatus']."',
       '".$input['ultimo_movimiento']."',
       '".$input['alerta']."',
       '".$input['propietario']."',
       '".$input['persona_acargo']."',
       '".$input['vendedor']."',
       '".$input['color']."')");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll() );
      exit();
}


header("HTTP/1.1 400 Bad Request");

?>