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
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Req includes
include_once '../config/database.php';
include_once '../objects/Product.php';

//Db conn and instances
$database = new Database();
$db=$database->getConnection();

$product = new Product($db);

//Get post data
$data = json_decode(file_get_contents("php://input"));

//set product values
$product->name          = $data->name;
$product->price         = $data->price;
$product->description   = $data->description;
$product->category_id   = $data->category_id;
$product->created       = date('Y-m-d H:i:s');

//Create product
if($product->create()){
    echo '{';
        echo '"message": "Product was created."';
    echo '}';
}else{
    echo '{';
        echo '"message": "Unable to create product."';
    echo '}';
}

function create(){

    //query insert
    $query = "INSERT INTO
              ". $this->table_name ."
              SET
                name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

    //Prepare
    $stmt = $this->conn->prepare($query);

    //sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->created=htmlspecialchars(strip_tags($this->created));

    //Bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":created", $this->created);

    //execute
    if($stmt->execute()){
        return true;
    }
    return false;
}

