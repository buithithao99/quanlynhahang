<?php
namespace vms;

class DeleteCartPage
{
    public $item_id;
    public function __construct($params = null)
    {
        session_start();
        $this->item_id = $params[0];
        
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($_SESSION["shopping_cart"])){
            foreach($_SESSION["shopping_cart"] as $key => $values){
                if($this->item_id === $values["item_id"]){
                    unset($_SESSION["shopping_cart"][$key]);
                    header("Location: /cart");
                }
            }
        }
    }
}