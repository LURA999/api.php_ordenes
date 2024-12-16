<?php
    // header("Access-Control-Allow-Origin: *");
    // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    //         header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");         
    //     }
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PATCH");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

$db = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'OrdenesTrabajoo3' //Cambiar al nombre de tu base de datos
];

  function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8", $db['username'], $db['password']);

          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }

//   function bindAllValues($statement, $params)
//   {
// 		foreach($params as $param => $value)
//     {
// 				$statement->bindValue(':'.$param, $value);
// 		}
// 		return $statement;
//    }

?>