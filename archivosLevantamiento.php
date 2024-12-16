<?php

include "Conn.php";

$dbConn = connect($db);
$imagen_nombre = "Vacio";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
     if(isset($_FILES['info'])){
        $imagen_nombre = $_FILES['info']['name'];
        $micarpeta = getcwd()."/fotos/";
        if (!file_exists($micarpeta)) {
            mkdir($micarpeta, 0777, true);
            echo("carpeta creada");
        }
		$directorio_final = $micarpeta.$imagen_nombre; 
        $contador = 1;
        while(file_exists($directorio_final)){
            echo("Si existe");
            $fileNameFull = new SplFileInfo($_FILES['info']['name']);
            $nombreBase = preg_replace("/\.[^.]+$/", "", $fileNameFull);
            $imagen_nombre = $nombreBase."(".$contador.").".$fileNameFull->getExtension();
            $directorio_final = $micarpeta.$imagen_nombre;
            $contador ++;
        }


        if(move_uploaded_file($_FILES['info']['tmp_name'], $directorio_final)){
            $sql = $dbConn->prepare("CALL sp_insertDocumentosLevantamiento(".$_GET['cve_levantamiento'].",'".$imagen_nombre."')");
            $sql->execute();
            $data = array(
                'status' => 'success',
                'code' => 200,
                'msj' => 'Imagen subida'
            );
            $format = (object) $data;
            $json = json_encode($format); 
            echo $json; 
            exit();
        }
    }else{
        $imagen_nombre = "No hay imagen";
        echo("Nel");
        exit();
    }
}


if($_SERVER['REQUEST_METHOD'] == 'GET'){ 


        $sql = $dbConn->prepare("CALL sp_selectDocumentosLevantamiento(".$_GET['cve_levantamiento'].")");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
}




//header("HTTP/1.1 400 Bad Request");

?>