<?php
include "Conn.php";
$dbConn = connect($db);
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $fechaHoy = Date('Y-m-d 23:00:00');
    $fechaPasada = date("Y-m-01");
    $sql;
    $sql = $dbConn->prepare("CALL reporte_instaladores('".$_GET['fechaInicio']."','".$_GET['fechaFin']." 23:00:00')");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 OK");
    echo json_encode($sql->fetchAll());
    exit();
}
header("HTTP/1.1 400 Bad Request");
?>