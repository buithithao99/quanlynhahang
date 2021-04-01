<?php
namespace vms;
use vms\templates\FormTemplate;
class NotFoundPage {
    public function __construct($params = null) {
        $this->title = "404 - Trang không hợp lệ";
    }

    public function render() {
        $template = new FormTemplate();
        $template->renderChild($this);
    }
    public function __render(){

?>
<div class="wrapper">
    <div class="not-found">404 - Not found</div>
</div>


<?php }}