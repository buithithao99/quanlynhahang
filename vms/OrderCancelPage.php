<?php
namespace vms;
use api\v1\UserAPI;

class OrderCancelPage
{
    public $orderId;
    public function __construct($params = null)
    {
        $this->orderId = $params[0];
    }
    public function render() {
        UserAPI::cancelOrderById($this->orderId);
    } 
}