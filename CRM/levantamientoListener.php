<?php

include "../Conn.php";

$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $body = file_get_contents('php://input');
    $input = json_decode($body,true);
    $sql;

    $sql = $dbConn->prepare("CALL prueba");

    try{
        $sql->execute();
        header("HTTP/1.1 200 OK");;
        exit();
    }catch(Exception $e){
        echo json_encode(array("error"=>true,
                                "description"=>$e.msgfmt_get_error_message));
        exit();
    }


}


header("HTTP/1.1 400 Bad Request");

?>