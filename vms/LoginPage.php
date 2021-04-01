<?php
namespace vms;
use vms\templates\FormTemplate;
use api\v1\UserAPI;

class LoginPage {
    public function __construct($params = null) {
        session_start();
        $this->title  = "Đăng nhập";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new FormTemplate();
        $template->renderChild($this);
        if(isset($_POST['submit'])){
          $email = $_POST["email"];
          $password = $_POST["password"];
          $data = UserAPI::getUserByEmail($email);
          if(UserAPI::login($email,$password,$data)->status){
              if($_SESSION['type'] === "admin"){
                  header("Location: /dashboard");
              }elseif($_SESSION['type'] === "cashier"){
                  header("Location: /order");
              }else{
                  header("Location: /northproduct");
              }
          }else{
              header("Location: /");
          }
      }
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="wrapper">
  <div class="form-area">
    <section class="form login">
          <img src="/assets/img/logo.jpg" width=200px height=200px/>
          <header>Quản lý nhà hàng <i class="fas fa-utensils"></i></header>
          <form action="/" method="POST" enctype="multipart/form-data" autocomplete="off">
            <?= isset($_SESSION['logout']) ? $_SESSION['logout']:"" ?>
            <?php 
                unset($_SESSION['logout']);
            ?>
            <div class="field input">
              <label>Email</label>
              <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
            </div>
            <div class="field input">
              <label>Mật khẩu</label>
              <input type="password" name="password" placeholder="Nhập mật khẩu" id="pass_log_id" required>
              <i class="fas fa-eye toggle-password" toggle="#password-field"></i>
            </div>
            <div class="reset-password">
               <a href="/formreset">Quên mật khẩu ?</a>
            </div>
            <div class="field button">
              <input type="submit" name="submit" value="Đăng nhập">
            </div>
          </form>
          <div class="link">Bạn chưa có tài khoản? <a href="/register">Nhấp vào đây để đăng ký</a></div>
    </section>
  </div>
</div>
<?php }}