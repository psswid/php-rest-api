<?php
/**
*contains properties and methods for "product" database queries.
 */

class Product
{

    //Db connection and table
    private $conn;
    private $table_name = 'products';

    //Object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    //Constructor with db conn
    public function __construct($db)
    {
        $this->conn = $db;
    }


    //Read product
    function read(){

        //select all
        $query = "SELECT
                    c.name AS category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                  FROM
                  " . $this->table_name . " p
                  LEFT JOIN
                    categories c ON p.category_id = c.id
                  ORDER BY
                    p.created DESC";

        //prepare
        $stmt = $this->conn->prepare($query);

        //execute
        $stmt->execute();

        return $stmt;

    }


    //read single product
    function readOne(){

        //read single record
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                   LEFT JOIN
                    categories c ON p.category_id = c.id
                   WHERE
                   p.id = ? LIMIT 0,1";

        //prepare
        $stmt = $this->conn->prepare($query);

        //bind id of product
        $stmt->bindParam(1, $this->id);

        //execute
        $stmt->execute();

        //fetch row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //set values to update
        $this->name=$row['name'];
        $this->price=$row['price'];
        $this->description=$row['description'];
        $this->category_id=$row['category_id'];
        $this->category_name=$row['category_name'];

    }



    //update product
    function update(){

        //update query
        $query = "UPDATE
                    " . $this->table_name. "
                    SET
                        name=:name,
                        price=:price,
                        description=:description,
                        category_id=:category_id
                    WHERE
                        id=:id";

        //prepare
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));

        //bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        //execute
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    //delete product
    function delete(){

        //delete query
        $query = " DELETE FROM " . $this->table_name . " WHERE id = ?";

        //prepare
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        //bind id
        $stmt->bindParam(1, $this->id);

        //execute
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    //search products
    function search($keywords){

        //select all query
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                  FROM " . $this->table_name. " p
                  LEFT JOIN
                    categories c ON p.category_id = c.id
                  WHERE
                    p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                  ORDER BY
                    p.created DESC";

        //prepare
        $stmt =$this->conn->prepare($query);

        //sanitize
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        //bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        //execute
        $stmt->execute();

        return $stmt;
    }

    //read products with pagination
    public function readPaging($from_record_num, $records_per_page){

        //select
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                  FROM " . $this->table_name . " p
                  LEFT JOIN
                    categories c ON p.category_id = c.id
                  ORDER BY p.created DESC
                  LIMIT ?, ?";

        //prepare
        $stmt = $this->conn->prepare($query);

        //bind
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        //execute
        $stmt->execute();

        //return values from db
        return $stmt;
    }


    //paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];

    }
}
