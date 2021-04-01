<?php
namespace vms;
use api\v1\UserAPI;

class CommunePage
{
    public $rows;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getCommuneById($_POST['districtId']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if ($_POST['districtId'] == "") {
            echo "<option value=''>--Chọn xã/phường--</option>";
        }
        foreach ($this->rows->message as $row) {
            echo "<option value='".$row['xaid']."'>".$row['name']."</option>";
        }
    }
}
