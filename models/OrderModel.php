<?php
namespace models;
use api\v1\UserAPI;
class OrderModel {

    public $category_id;
    public $quantity;
    public $total;
    public $status;
    public $email;
    public $product_id;
    public $id;
    
    public function __construct($order) {
        $this->product_id = $order['product_id'];
        $price = UserAPI::getPriceByProductId($order['product_id']);
        $this->category_id = $order["category_id"];
        $this->quantity = $order["quantity"];
        $this->total = $order["quantity"] * $price->message[0]['price'];
        $this->status = $order["status"];
        $this->email = $order["email"];
        $this->id = $order["id"];
    }
}