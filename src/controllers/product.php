<?php

namespace Controllers;

class Product
{
    public function __construct(private $gateway)
    {
        
    }

    public function processRequest($method, $id = null)
    {
        if($id){

            $this->processResourceRequest($method, $id);

        }else{

            $this->processColectioRequest($method);

        }
    }

    private function processResourceRequest($method, $id){

        $product = $this->gateway->get($id);

        if(!$product){
            http_response_code(404);
            echo json_encode(["message" => "Product not found"]);
            return;
        }

        switch($method){
            case "GET":
                echo json_encode($product);
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"),true);

                $error = $this->getValidationErrors($data,false);

                if(!empty($error)){
                    http_response_code(422);
                    echo json_encode([
                        "error" => $error
                    ]);
                    break;
                }

                $row = $this->gateway->update($product,$data);

                http_response_code(201);

                echo json_encode([
                    "message" => "updated",
                    "row" => $row
                ]);

                break;
            case "DELETE":
                $rows = $this->gateway->delete($id);

                echo json_encode([
                    "message" => "deleted " .$id. " product",
                    "rows" => $rows
                ]);

                break;
            default:
                http_response_code(405);
                header("Allow: GET POST PATCH");
                break;
        }

        
    }

    private function processColectioRequest($method){

        switch($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"),true);

                $error = $this->getValidationErrors($data,true);

                if(!empty($error)){
                    http_response_code(422);
                    echo json_encode([
                        "error" => $error
                    ]);
                    break;
                }

                $id = $this->gateway->create($data);

                http_response_code(201);

                echo json_encode([
                    "message" => "created",
                    "id" => $id
                ]);

                break;
            default:
                http_response_code(405);
                header("Allow: GET POST");
                break;
        }
    }

    private function getValidationErrors($data, $is_new){


        if(count($data) === 0){
            return "enter correct data to create";
        }

        $key_array = ["name","description","price","category_id"];

        foreach($key_array as $key){
            if(!array_key_exists($key,$data) && !!$is_new){
                return $key . " is required";
            }
        }
        
    }
}
