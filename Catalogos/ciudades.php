<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        if(isset($_GET['usuariociudades'])){
                $sql = $dbConn->prepare("CALL sp_selectUsuarioCiudades(".$_GET['cve_usuario'].")");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode( $sql->fetchAll()  );
                exit();
        }

        $sql = $dbConn->prepare("CALL sp_selectCiudades");
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
        $sql = $dbConn->prepare("CALL sp_insertarCiudad('".$input['nombre']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}


?>