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
                        'item_qty' => $_POST['qty'],
                        'user_id' => $_POST['user_id'],
                        'region_id' => $_POST['region_id'],
                        'table_id' => $_POST['table_id'],
                        'table_type' => $_POST['table_type']
                    ];
                    $_SESSION["shopping_cart"][$count] = $item_array;
                    if(isset($_POST['region_name'])){
                        header("Location: /".$_POST['region_name']."product");
                    }
                }else{  
                    if(isset($_POST['region_name'])){
                        header("Location: /".$_POST['region_name']."product");
                    }
                }
            }else{
                $item_array = [
                    'item_id' => $_POST['id'],
                    'item_name' => $_POST['name'],
                    'item_price' => $_POST['price'],
                    'item_image' => $_POST['image'],
                    'item_description' => $_POST['description'],
                    'item_qty' => $_POST['qty'],
                    'user_id' => $_POST['user_id'],
                    'region_id' => $_POST['region_id'],
                    'table_id' => $_POST['table_id'],
                    'table_type' => $_POST['table_type']
                ];
                $_SESSION["shopping_cart"][0] = $item_array;
                if(isset($_POST['region_name'])){
                    header("Location: /".$_POST['region_name']."product");
                }
            }
        }
    }
}