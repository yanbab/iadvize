<?php
/**
 * iAdvize - vdm 
 * PHP test
 * 
 * author : yanbab@gmail.com
 */

// Composer Autoloader
require "../vendor/autoload.php";

// VDM model (fetch, save and retrieve vdm stories)
require "./vdmHelper.php";

// Config
$config = require "./config.php";

// App : Slim object
$app = new \Slim\Slim($config);

// PDO : db wrapper
try {
  $app->pdo = new PDO(
    $config["pdo.dsn"],
    $config["pdo.user"], 
    $config["pdo.pass"], [
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch( PDOException $e ) {
  die("Fatal error : can't connect to database, please check your settings.");
}

// notORM : simple data access
$app->db = new NotORM($app->pdo);

// API routes

$app->get("/", function() {
  echo "Usage : /api/posts/id; /api/posts/?author=yanbab";
});

$app->get("/api/posts(/)", function() use ($app) {
  $stories = $app->db->vdm;
  if(isset($_GET['from'])) {
    $stories->where("date > ?", $_GET['from']);
  }
  if(isset($_GET['to'])) {
    $stories->where("from > ?", $_GET['to']);
  }
  if(isset($_GET['author'])) {
    $stories->where("author = ?", $_GET['author']);
  }
  // transforms result object to array
  $stories = array_map('iterator_to_array', iterator_to_array($stories));
  // remove indexes from array
  $stories = array_values((array)$stories);
  //print_r($stories);die;
  echo json_encode([
    "posts" => $stories,
    "count" => count($stories)
  ]);
});

$app->get("/api/posts/:id", function($id) use ($app)  {
  $post = $app->db->vdm->where("id = ?", $id);
  echo json_encode([
    "post" => $post->fetch()
  ]);
});

$app->get("/api/fetch(/:total)", function($total = 200) use ($app)  {
  echo "Fetching $total stories... ";
  $vdm = new vdmHelper;
  $stories = $vdm->fetchStories($total);
  $vdm->storeStories($app->db, $stories);
  echo "Done !";
  //print_r($stories);
});


// run them all
$app->run();
