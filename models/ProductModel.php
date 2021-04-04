<?php
namespace models;

class ProductModel {
    public $category_id;
    public $name;
    public $price;
    public $description;
    public $image;
    public $quantity;
    public $active;
    public $region_id;
    public $id;

    public function __construct($product,$file) {
        $this->category_id = $product["category_id"];
        $this->name = $product["name"];
        $this->price = $product["price"];
        $this->description = $product["description"];
        $this->image = $file["image"];
        $this->quantity = $product["quantity"];
        $this->active = $product["active"];
        $this->region_id = $product["region_id"];
        if(isset($product["id"])){
            $this->id = $product["id"];
        }
    }
}