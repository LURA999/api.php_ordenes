<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        $sql = $dbConn->prepare("CALL sp_selectCoordenadasSucursal('".$_GET['cve_sucursal']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}



header("HTTP/1.1 400 Bad Request");
?>