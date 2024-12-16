<?php

include "Conn.php";

$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $body = file_get_contents('php://input');
    $input = json_decode($body,true);
    $sql;

    if(isset($_GET['masInsta']) == true){
      //  echo "hola";
      $sql = $dbConn->prepare("CALL sp_insertarInstaladoresLevOrd(
        '".$input['cve_orden']."',
        '".$input['cve_instalador']."', 1)");

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }
    
    if($input["fechaProgramada"] == null){
        $sql = $dbConn->prepare("CALL sp_insertOrdenServicio(
            '".$input["idCliente"]."',
            '".$input["idSucursal"]."',
            '".$input["idContacto"]."',
            '".$input["descripcion"]."',
            '".$input["servicio"]."',
            '".$input["idInstalador"]."',
            '".$input["idUsuario"]."',
            null, ".$input["ciudad"].", null, '".$input["coordenadas"]."')");
    }else{
        $sql = $dbConn->prepare("CALL sp_insertOrdenServicio(
            '".$input["idCliente"]."',
            '".$input["idSucursal"]."',
            '".$input["idContacto"]."',
            '".$input["descripcion"]."',
            '".$input["servicio"]."',
            '".$input["idInstalador"]."',
            '".$input["idUsuario"]."',
            '".$input["fechaProgramada"]."',
            ".$input["ciudad"].", null, '".$input["coordenadas"]."')");
    }
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

if($_SERVER['REQUEST_METHOD'] =='DELETE'){

    if(isset($_GET['eli'])==true){
    $sql = $dbConn->prepare("CALL sp_deleteInstaOrdLev('".$_GET['cveOrd']."','".$_GET['cveInsta']."',1)");
    $sql->execute();
    header("HTTP/1.1 200 OK");
    exit();
    }


    $sql = $dbConn->prepare("CALL sp_deleteOrdenServicio('".$_GET['cve_orden']."')");
    $sql->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){ 
 
    if(  isset($_GET['opcion']) == 2){
        $sql = $dbConn->prepare("CALL sp_selectUoM_instaladoreLevOrd('".$_GET['cve_orden']."', 0)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }
    if(isset($_GET['ultima'])==true){
        $sql = $dbConn->prepare("CALL sp_selectUltimaOrden()");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }
    if(  isset($_GET['lista']) == true){
        $sql = $dbConn->prepare("CALL sp_selectCantidadInstaladoresLevOrd(3,'".$_GET['cve']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }

    if(isset($_GET['comentario'])){
        $sql = $dbConn->prepare("CALL sp_selectComentariosOrden('".$_GET['cve_orden']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }

    if(isset($_GET['id_orden'])){
        $sql = $dbConn->prepare("CALL sp_selectOrden('".$_GET['id_orden']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }else if(isset($_GET['inicioInstalador']) == true){
            
        $sql = $dbConn->prepare("CALL sp_selectDefaultCalendarioInstalador('".$_GET['cve_usuario']."','".$_GET['nivel']."','".$_GET['fechaInicio']."', 2)");
       // echo "INICIO INSTALADOR ORDEN SERVICIO\n\n";
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }else if(isset($_GET['calendarioCordi']) == true){
        $sql = $dbConn->prepare("CALL sp_selectInicioCordi('".$_GET['cve_usuario']."','".$_GET['nivel']."','".$_GET['fechaInicio']."','0000-00-00' , -1)");
       // echo "INICIO INSTALADOR ORDEN SERVICIO\n\n";
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }else  if(isset($_GET['inicioAdmin']) == true){
        $sql = $dbConn->prepare("CALL sp_selectCalendarioAdmin('".$_GET['fechaInicio']."', 2)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
        
    }
    
    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])){
        if(isset($_GET['inicioCordi']) == true){
            $sql = $dbConn->prepare("CALL sp_selectInicioCordi('".$_GET['cve_usuario']."','".$_GET['nivel']."'
            ,'".$_GET['fechaInicio']."' , '".$_GET['fechaFin']."', 3)");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
        }else{
            $sql = $dbConn->prepare("CALL sp_selectOrdenesServicio2('".$_GET['cve_usuario']."','".$_GET['nivel']."','".$_GET['cve_orden']."',
            '".$_GET['estatus']."','".$_GET['fechaInicio']."' , '".$_GET['fechaFin']."')");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
        }

    }else{
        if(isset($_GET['grafica']) == true){
            $sql = $dbConn->prepare("CALL sp_selectTodosOrden()");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
            
        }else if(isset($_GET['inicioCordi']) == true){
            $sql = $dbConn->prepare("CALL sp_selectInicioCordi('".$_GET['cve_usuario']."','".$_GET['nivel']."','0000-00-00','0000-00-00' , 1)");
            $sql -> execute();
            $sql -> setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll() );
            exit();
        }else{
            $fechaHoy = Date('Y-m-d');
            $fechaPasada = date("Y-m-01");
           // echo "ENTRO A LA ULTIMA OPCION  ORDEN SERVICIO";
            $sql = $dbConn->prepare("CALL sp_selectOrdenesServicio('".$_GET['id']."','".$_GET['nivel']."','".$_GET['cve_orden']."',
            '".$_GET['estatus']."','".$fechaPasada."', '".$fechaHoy."')");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
    $body = file_get_contents('php://input');
    $input = json_decode($body,true);
    if(isset($input['fechaProgramadaNueva'])){
        $sql = $dbConn->prepare("CALL sp_updateFechaProgramada('".$input['cve_orden']."','".$input['fechaProgramadaNueva']." ".$input['hora'].":00"."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }

    if(isset($input['instalador'])){
        $sql = $dbConn->prepare("CALL sp_actualizarInstalador('".$input['cve_orden']."','".$input['instalador']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }
    
    if(isset($input['comentario'])){
        $sql = $dbConn->prepare("CALL sp_insertarComentarioOrden('".$input['cve_usuario']."','".$input['comentario']."','".$input['cve_orden']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit();
    }

    if(isset($input['cerrar'])){
        $sql = $dbConn->prepare("CALL sp_cerrarOrden('".$input['cve_orden']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }

    if(isset($_GET['agregarInsta']) == true){
        $sql = $dbConn->prepare("CALL sp_InsertOrdenLevInstaladores(0,'".$input['cve_instalador']."', 1)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }
}


header("HTTP/1.1 400 Bad Request");

?>