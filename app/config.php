<?php 
// iadvize - config.php

return [    

  /* Slim */

  // 'mode' => 'development',
  // 'debug' => true,

  /* Database */

  "pdo.dsn" => "mysql:host=localhost;dbname=iadvize",
  "pdo.user" => "root",
  "pdo.pass" => "",
  
  /* url used by the CLI script to fetch data */
  
  "vdm.url" => "http://localhost/Sites/Clients/iadvize/app/index.php/api/fetch"
  
];