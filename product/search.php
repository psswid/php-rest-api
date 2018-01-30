<?php
/**
 * Created by PhpStorm.
 * User: Stef
 * Date: 26.01.2018
 * Time: 18:08
 */

//Req headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset:UTF-8");

//Req includes
include_once '../config/database.php';
include_once '../objects/Product.php';

//Db conn and instances
$database = new Database();
$db=$database->getConnection();

$product = new Product($db);

//get keywords
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

//query products
$stmt=$product->search($keywords);
$num=$stmt->rowCount();

//check if more than 0 record found
if($num>0){

  //products array
    $products_arr = array();
    $products_arr["records"] = array();

    //retrieve table contents
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $product_item=array(
            "id"            =>$id,
            "name"          =>$name,
            "description"   =>html_entity_decode($description),
            "price"         =>$price,
            "category_id"   =>$category_id,
            "category_name" =>$category_name
        );

        array_push($products_arr["records"], $product_item);
    }

    echo json_encode($products_arr);
}else{
    echo json_encode(
        array("message" => "No products found.")
    );
}