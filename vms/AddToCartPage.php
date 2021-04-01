<?php
namespace vms;

class AddToCartPage
{
    public function __construct($params = null)
    {
        session_start();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($_POST['submit'])){
            if(isset($_SESSION["shopping_cart"])){
                $item_array_id = array_column($_SESSION["shopping_cart"],"item_id");
                if(!in_array($_POST["id"],$item_array_id)){
                    $count = count($_SESSION["shopping_cart"]);
                    $item_array = [
                        'item_id' => $_POST['id'],
                        'item_name' => $_POST['name'],
                        'item_price' => $_POST['price'],
                        'item_image' => $_POST['image'],
                        'item_description' => $_POST['description'],
                        'item_qty' => $_POST['qty']
                    ];
                    $_SESSION["shopping_cart"][$count] = $item_array;
                    if(isset($_POST['region'])){
                        header("Location: /".$_POST['region']."product");
                    }else{
                        header("Location: /simple");
                    }
                }else{  
                    if(isset($_POST['region'])){
                        header("Location: /".$_POST['region']."product");
                    }else{
                        header("Location: /simple");
                    }
                }
            }else{
                $item_array = [
                    'item_id' => $_POST['id'],
                    'item_name' => $_POST['name'],
                    'item_price' => $_POST['price'],
                    'item_image' => $_POST['image'],
                    'item_description' => $_POST['description'],
                    'item_qty' => $_POST['qty']
                ];
                $_SESSION["shopping_cart"][0] = $item_array;
                if(isset($_POST['region'])){
                    header("Location: /".$_POST['region']."product");
                }else{
                    header("Location: /simple");
                }
            }
        }
    }
}