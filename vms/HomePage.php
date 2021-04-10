<?php
namespace vms;
use vms\templates\HomeTemplate;
class HomePage {
    public function __construct($params = null) {
        $this->title  = "Trang chủ";
        if(isset($_SESSION['booking'])){
            unset($_SESSION['booking']);
        }
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new HomeTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php }}