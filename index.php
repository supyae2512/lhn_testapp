<?php
require_once "bootstrap.php";

use Controllers\TaskController;

$endPoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($endPoint) {
    case '/tasks':
        $taskController = new TaskController();
       break;
       
    case '/others':
       // require __DIR__ . '/controllers/others.php';
       break;
    default: 
        header("Location: index.html");
        exit;
}


?>