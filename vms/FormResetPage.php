<?php
namespace vms;
use vms\templates\FormTemplate;

class FormResetPage {
    public function __construct($params = null) {
        $this->title  = "Lấy lại mật khẩu";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new FormTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="wrapper">
  <div class="form-area">
    <section class="form">
          <header>Lấy lại mật khẩu</header>
          <form action="/resetpassword" method="POST" enctype="multipart/form-data" autocomplete="off">
              <div class="field input">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
              </div>
              <div class="field button">
                <input type="submit" name="submit" value="Xác thực mail">
              </div>
              <a href="/" class="return"><i class="fas fa-arrow-left"></i></a>
          </form>
    </section>
  </div>
</div>
<?php }}