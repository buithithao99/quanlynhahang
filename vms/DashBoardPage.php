<?php
namespace vms;
use vms\templates\AdminTemplate;

class DashBoardPage {
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            if($_SESSION['type']!=="admin"){
                header("Location: /northproduct");
            }else{
                header("Location: /");
            }
        }
        $this->title  = "Bảng điều khiển";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div id="chart_div"></div>
<?php }}