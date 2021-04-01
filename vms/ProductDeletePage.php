<?php
namespace vms;
use api\v1\UserAPI;

class ProductDeletePage
{
    public $productId;
    public function __construct($params = null)
    {
        $this->productId = $params[0];
    }
    public function render() {
        UserAPI::deleteProductById($this->productId);
    } 
}