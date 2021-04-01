<?php
namespace models;

class OrderModel {

    public $product;
    public $product_qty;
    public $total;
    public $status;
    public $user_id;

    public function __construct($order) {
        $this->product = $order["product"];
        $this->product_qty = $order["product_qty"];
        $this->total = $order["total"];
        $this->status = $order["status"];
        $this->user_id = $order["user_id"];
    }
}