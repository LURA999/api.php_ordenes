<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        $sql = $dbConn->prepare("CALL sp_selectActividades");
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
        $sql = $dbConn->prepare("CALL sp_insertActividad('".$input['actividad']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
        $sql = $dbConn->prepare("CALL sp_deleteActividad('".$_GET['cve_actividad']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
}


?>