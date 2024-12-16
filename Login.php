<?php
include "Conn.php";

$dbConn =  connect($db);


function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}


    $query = $dbConn->prepare("CALL sp_login('".$_GET['user']."');");
    try{
        $query->execute();
    }catch(Exception $e){
        $err = array('error' => true,
                    'description'=> "Conection error");
        return json_encode($err);
    }
    
    
    $cuenta = $query->rowCount();
    if($cuenta > 0)
    {
        while($row = $query->fetch(PDO::FETCH_NUM))
        {

            if(password_verify($_GET['contrasena'],$row[4]))
            {
                $headers = ['alg'=>'HS256','typ'=>'JWT'];
                $headers_encoded = base64url_encode(json_encode($headers));

                $payload = ['sub'=>$row[0],'name'=>$row[1],'email'=>$row[2], 'level'=>$row[3],
                'time'=>3600];
                $payload_encoded = base64url_encode(json_encode($payload));

                $key = 'secret';
                $signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded",$key,true);
                $signature_encoded = base64url_encode($signature);

                $token = "$headers_encoded.$payload_encoded.$signature_encoded";
                echo json_encode(array("error"=>false,
                                       "token"=>$token));
                exit();
            }else
            {
                $err = array('error' => true);
                echo json_encode($err);
            }
        }

    }else{
        $err = array('error' => true);
        echo json_encode($err);
    }


?>