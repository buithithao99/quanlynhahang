<?php
namespace vms;
use api\v1\UserAPI;

class ShowProductPage
{
    public $rows;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getProductByCateId($_POST['cateId']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($this->rows->message)){
            foreach ($this->rows->message as $row) {
                echo "<option value='".$row['id']."'>".$row['name']."</option>";
            }
        }
    }
}
