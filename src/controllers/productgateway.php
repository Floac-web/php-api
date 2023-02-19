<?php

namespace Controllers;

class ProductGateway{

    private $conn;

    public function __construct($database)
    {
        $this->conn = $database->connect();
    }

    public function getAll(){
        
        $stmt = $this->conn->query("SELECT * FROM products");

        $data = [];

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){

            $data[] = $row;

        }

        return $data;
    }

    public function create($data){

        $stmt = $this->conn->prepare("INSERT INTO products (name,description,price,category_id) VALUES (?,?,?,?)");
        extract($data);
        $stmt->execute(array($name,$description,$price,$category_id));

        return $this->conn->lastInsertId();
    }

    public function get($id){

        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute(array($id));

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($current,$new){

        $sql = "UPDATE products 
        SET name = :name,description = :description,price = :price ,category_id = :category_id
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $new["name"] ?? $current["name"]);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"]);
        $stmt->bindValue(":price", $new["price"] ?? $current["price"]);
        $stmt->bindValue(":category_id", $new["category_id"] ?? $current["category_id"]);
        $stmt->bindValue(":id", $current["id"]);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute(array($id));

        return $stmt->rowCount();
    }
}