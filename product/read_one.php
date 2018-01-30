<?php
/**
 * Created by PhpStorm.
 * User: Stef
 * Date: 26.01.2018
 * Time: 18:07
 */

//Req headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset:UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access");

//Include db and object

include_once '../config/database.php';
include_once '../objects/Product.php';

//New instances

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

//Set ID of product to be edited
$product->id = isset($_GET['id']) ? $_GET['id']: die;

//Read details of edited product
$product->readOne();

//Create array
$product_arr = array(
    "id" => $product->id,
    "name" => $product->name,
    "description" => $product->description,
    "price" => $product->price,
    "category_id" => $product->category_id,
    "category_name" => $product->category_name
);

print_r(json_encode($product_arr));
