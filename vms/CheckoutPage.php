<?php
namespace vms;
use api\v1\UserAPI;
use models\OrderModel;
class CheckoutPage
{
    public function __construct($params = null)
    {
        session_start();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($_POST['submit'])){
            $order = new OrderModel($_POST);
            UserAPI::checkout($order);
        }
    }
}