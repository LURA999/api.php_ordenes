<?php

include "../Conn.php";

$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{   
    if(isset($_FILES['file'])){
     echo json_encode(array('error'=>false));
    }else{
    echo json_encode(array('error'=>true));
    }
}
?>