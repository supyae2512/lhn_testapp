<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');
require_once 'model/connection.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// $dbConnection = (new DBConnection())->connection();

?>