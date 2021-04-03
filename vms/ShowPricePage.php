<?php
namespace vms;
use api\v1\UserAPI;

class ShowPricePage
{
    public $rows;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getPriceByProductId($_POST['proId']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        foreach ($this->rows->message as $row) {
            echo $row['price'];
        }
    }
}
