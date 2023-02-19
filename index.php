<?php

use App\ErrorHandler;
use Controllers\Product;
use Controllers\ProductGateway;

include "./src/core/init.php";
require_once __DIR__ . "/vendor/autoload.php";


set_error_handler("App\ErrorHandler::handleError");
set_exception_handler("App\ErrorHandler::handleExeption");

header("Content-type: aplication/json; charset=UTF-8");

$url_params = explode("/",$_GET["url"]);

$colection = $url_params[0];
$method = $_SERVER["REQUEST_METHOD"];
$id = $url_params[1] ?? null;






if($colection === "products"){
    $database = new Database;
    $gateway = new ProductGateway($database);
    $controller = new Product($gateway);
    $controller->processRequest($method,$id);
}



