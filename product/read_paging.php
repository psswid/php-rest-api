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

//Req includes
include_once '../config/core.php';
include_once '../shared/Utilities.php';
include_once '../config/database.php';
include_once '../objects/Product.php';

//utilities
$utilities = new Utilities();

//Db conn and instances
$database = new Database();
$db=$database->getConnection();

$product = new Product($db);


//query products
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();


print_r($stmt);
echo $num;



//If more than 0 records
if($num>0){

    //products array
    $products_arr=array();
    $products_arr["records"]=array();
    $products_arr["paging"]=array();

    //retrieve table content
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        extract($row);

        $product_item = array(
            "id"            =>  $id,
            "name"          =>  $name,
            "description"   =>  html_entity_decode($description),
            "price"         =>  $price,
            "category_id"   =>  $category_id,
            "category_name" =>  $category_name
        );

        array_push($products_arr["records"], $product_item);
    }

    //include paging
    $total_rows=$product->count();
    $page_url="{$home_url}/product/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"]=$paging;

    echo json_encode($products_arr);
}else{
    echo json_encode(array(
        "message" => "No products found.")
    );
}