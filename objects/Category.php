<?php
/**
 * Created by PhpStorm.
 * User: Stef
 * Date: 26.01.2018
 * Time: 18:06
 *
 * contains properties and methods for "category" database queries.
 */

class Category
{
    //db conn and table
    private $conn;
    private $table_name = "categories";

    //object properties
    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //used by select drop-down list
    public function readAll(){

        $query = "SELECT 
                    id, name, description 
                  FROM " . $this->table_name . " ORDER BY name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    //used by select drop-down list
    public function read(){

        $query = "SELECT 
                    id, name, description 
                 FROM " . $this->table_name . " 
                 ORDER BY name";

        $stmt=$this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}