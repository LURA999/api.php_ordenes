<?php

include "Conn.php";

$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    echo("post");
    $body = file_get_contents('php://input');
    $input = json_decode($body,true);
    $fechaHoy = Date('Y-m-d 00:00:00');
    $sql;


    if(isset($_GET['masInsta']) == true){
        //  echo "hola";
        $sql = $dbConn->prepare("CALL sp_InsertOrdenLevInstaladores(
          '".$input['cve_levantamiento']."',
          '".$input['cve_instalador']."', 2)");
  
          $sql->execute();
          $sql->setFetchMode(PDO::FETCH_ASSOC);
          header("HTTP/1.1 200 OK");
          exit();
      }


    if(isset($input["cve_creador"])){
        $sql = $dbConn->prepare("CALL sp_insertarLevantamiento2(
            '".$fechaHoy."',
            ".$input["cve_creador"].",
            ".$input["cve_cliente"].",
            ".$input["cve_sucursal"].",
            ".$input["cve_contacto"].",
            '".$input["coordenadas"]."',
            ".$input["ciudad"].",
            '".$input["servicio"]."',
            ".$input["megas"].",
           
            ".$input["repetidora"].")");
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
    if(isset($_GET['agregarInsta']) == true){
        $sql = $dbConn->prepare("CALL sp_InsertOrdenLevInstaladores(
            '".$input['cve_levantamiento']."',
            '".$input['cve_instalador']."', 3)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] =='DELETE'){

    if(isset($_GET['eli'])==true){
        $sql = $dbConn->prepare("CALL sp_deleteInstaOrdLev('".$_GET['cveOrd']."','".$_GET['cveInsta']."',2)");
        $sql->execute();
        header("HTTP/1.1 200 OK");
        exit();
        }

    $sql = $dbConn->prepare("CALL sp_deleteLevantamiento('".$_GET['cve_levantamiento']."')");
    $sql->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(  isset($_GET['opcion']) == 2){
        $sql = $dbConn->prepare("CALL sp_selectUoM_instaladoreLevOrd('".$_GET['cve_levantamiento']."', 1)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }

    if(isset($_GET['ultima'])==true){
        $sql = $dbConn->prepare("CALL sp_selectUltimoLevantamiento()");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }

    if(isset($_GET['cve_levantamiento'])){
        $sql = $dbConn->prepare("CALL sp_selectLevantamientoID(".$_GET['cve_levantamiento'].")");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }
    if(  isset($_GET['lista']) == true){
        
        $sql = $dbConn->prepare("CALL sp_selectListaInstaldoresLevOrd(1,'".$_GET['cve']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }else  if(isset($_GET['inicioInstalador']) == true){
        $sql = $dbConn->prepare("CALL sp_selectDefaultCalendarioInstalador('".$_GET['cve_usuario']."','".$_GET['nivel']."','".$_GET['fechaInicio']."',1)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
        
    }else if(isset($_GET['calendarioCordi']) == true){
        $sql = $dbConn->prepare("CALL sp_selectInicioCOrdi('".$_GET['cve_usuario']."','".$_GET['nivel']."','".$_GET['fechaInicio']."','0000-00-00' , -2)");
       // echo "INICIO INSTALADOR ORDEN SERVICIO\n\n";
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
    }else  if(isset($_GET['inicioAdmin']) == true){
        $sql = $dbConn->prepare("CALL sp_selectCalendarioAdmin('".$_GET['fechaInicio']."', 1)");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
        
    }

    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])){
        if(isset($_GET['inicioCordi']) == true){
            
            $sql = $dbConn->prepare("CALL sp_selectInicioCordi('".$_GET['cve_usuario']."','".$_GET['nivel']."'
            ,'".$_GET['fechaInicio']."' , '".$_GET['fechaFin']."', 4)");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll()  );
            exit();
        }else{
        $sql = $dbConn->prepare("CALL sp_selectLevantamientos2('".$_GET['cve_usuario']."','".$_GET['nivel']."',
        '".$_GET['estatus']."','".$_GET['fechaInicio']."' , '".$_GET['fechaFin']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
        }
    }else{
        if(isset($_GET['grafica']) == true){
            $sql = $dbConn->prepare("CALL sp_selectTodosLevantamientos()");
            $sql -> execute();
            $sql -> setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode( $sql->fetchAll() );
            exit();
        }else {
            
            if(isset($_GET['inicioCordi']) == true){
                $sql = $dbConn->prepare("CALL sp_selectInicioCordi('".$_GET['cve_usuario']."','".$_GET['nivel']."', '0000-00-00','0000-00-00', 2)");
                $sql -> execute();
                $sql -> setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode( $sql->fetchAll() );
                exit();
            }else{
                $fechaHoy = Date('Y-m-d 23:59:00');
                $fechaPasada = date("Y-m-01 00:00:00");
              //  echo "ENTRO A LA ULTIMA OPCION  ORDEN SERVICIO";
                $sql = $dbConn->prepare("CALL sp_selectLevantamientos('".$_GET['cve_usuario']."','".$_GET['estatus']."','".$_GET['nivel']."',
                '".$fechaPasada."', '".$fechaHoy."')");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode( $sql->fetchAll()  );
                exit();
            }
          
        }
    }
    
}

if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
    echo "HOLA SI ENTRA";
    $body = file_get_contents('php://input');
    $input = json_decode($body,true);
    if(isset($input['fechaProgramadaNueva'])){
        $sql = $dbConn->prepare("CALL sp_updateFechaLevantamiento('".$input['cve_levantamiento']."','".$input['fechaProgramadaNueva']." ".$input['hora'].":00"."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }

    if(isset($input['instalador'])){
        printf("Llega aqui");
        $sql = $dbConn->prepare("CALL sp_updateInstaladorLevantamiento('".$input['cve_levantamiento']."','".$input['instalador']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }

    if(isset($input['cve_vehiculo'])){
        $sql = $dbConn->prepare("CALL sp_updateVehiculo('".$input['cve_levantamiento']."','".$input['cve_vehiculo']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }
    
    if(isset($input['cerrar'])){
        $sql = $dbConn->prepare("CALL sp_cerrarOrden('".$input['cve_orden']."')");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        exit();
    }


/*      $sql = $dbConn->prepare("CALL sp_cerrarLevantamiento(
            ".$input['altura'].",
            '".$input['tipo']."',
            '".$input['tipoTecho']."',
            '".$input['torre']."',
            ".$input['repetidora'].",
            ".$input['rack'].",
            ".$input['linea'].",
            '".$input['descripcion']."',
            ".$input['energia'].",
            ".$input['cve_levantamiento']."
            )");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            exit();*/
}


header("HTTP/1.1 400 Bad Request");

?>