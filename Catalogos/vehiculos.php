<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        $sql = $dbConn->prepare("CALL sp_selectVehiculos");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
        $body = file_get_contents('php://input');
        $input = json_decode($body,true);
        $sql = $dbConn->prepare("CALL sp_insertarVehiculo(
        '".$input['descripcion']."',
        '".$input['placa']."',
        ".$input['ciudad'].")");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        $sql = $dbConn->prepare("CALL sp_deleteVehiculo(".$_GET['cve_vehiculo'].")");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
}

if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
        $body = file_get_contents('php://input');
        $input = json_decode($body,true);
        if(isset($input['estatus'])){
                $sql = $dbConn->prepare("CALL sp_updateEstatusVehiculo(".$input['cve_vehiculo'].",".$input['estatus'].")");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header('HTTP/1.1 200 ok');
                exit();
        }

        if(isset($input['cve_orden'])){
                $sql = $dbConn->prepare("CALL sp_updateOrdenVehiculo(".$input['cve_orden'].",".$input['cve_vehiculo'].")");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header('HTTP/1.1 200 ok');
                exit();
        }

        if(isset($input['cve_vehiculo'])){
                $sql = $dbConn->prepare("CALL sp_updateVehiculo(".$input['cve_levantamiento'].",".$input['cve_vehiculo'].")");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header('HTTP/1.1 200 ok');
                exit();
        }
}


header("HTTP/1.1 400 Bad Request");
?>