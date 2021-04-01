<?php
namespace vms;
use api\v1\UserAPI;

class DistrictPage
{
    public $rows;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getDistrictById($_POST['cityId']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if ($_POST['cityId'] == "") {
            echo "<option value=''>--Chọn quận/huyện--</option>";
        }
        foreach ($this->rows->message as $row) {
            echo "<option value='".$row['maqh']."'>".$row['name']."</option>";
        }
    }
}
