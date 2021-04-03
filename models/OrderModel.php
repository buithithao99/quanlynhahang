<?php
namespace models;

class OrderModel {

    public $category_id;
    public $quantity;
    public $total;
    public $status;
    public $price;
    public $email;

    public function __construct($order) {
        $this->category_id = $order["category_id"];
        $this->quantity = $order["quantity"];
        $this->total = $order["total"];
        $this->status = $order["status"];
        $this->price = $order["price"];
        $this->email = $order["email"];
    }
}