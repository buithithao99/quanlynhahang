<?php
namespace vms;
use api\v1\UserAPI;

class CustomFilterPage
{
    public $rows;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getTotal($_POST['from_date'],$_POST['to_date']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
       echo json_encode($this->rows->message);
    }
}
