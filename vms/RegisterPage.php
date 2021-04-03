<?php
namespace vms;

use vms\templates\FormTemplate;
use api\v1\UserAPI;
use models\UserModel;

class RegisterPage
{
    public $city;
    public function __construct($params = null)
    {
        $this->title  = "Đăng ký";
        $this->city = UserAPI::getAllCity();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        session_start();
        $template = new FormTemplate();
        if(isset($_POST['submit'])){
          $email = $_POST["email"];
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkExist = UserAPI::checkExistEmail($email);
            if ($checkExist->status) {
                $user = new UserModel($_POST,$_FILES);
                $res = UserAPI::save($user);
                if($res === "Invalid password"){
                  $_SESSION['error'] = "<div class='error-text'>Password must have uppercase letter, lower letter and number. <span class='close'>&times;</span></div>";
                }
            }else{
                $_SESSION['error'] = "<div class='error-text'>$email - This email already exist! <span class='close'>&times;</span></div>";
            }  
          }else{
            $_SESSION['error'] = "<div class='error-text'>$email is not a valid email! <span class='close'>&times;</span></div>";
          }
       }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
        ?>
<div class="wrapper">
  <div class="form-area">
    <section class="form signup">
        <header>Đăng ký <i class="fas fa-utensils"></i></header>
          <form action="/register" method="POST" enctype="multipart/form-data" autocomplete="off">
            <?= isset($_SESSION['error']) ? $_SESSION['error']: "" ?>
            <?php unset($_SESSION['error']);?>
            <div class="name-details">
              <div class="field input">
                <label>Tên</label>
                <input type="text" name="firstname" placeholder="Nhập tên" required>
              </div>
              <div class="field input">
                <label>Họ</label>
                <input type="text" name="lastname" placeholder="Nhập họ" required>
              </div>
            </div>
            <div class="field">
              <label>Giới tính</label>
              <select name="gender" id="gender" required>
                  <option value="male">Nam</option>
                  <option value="female">Nữ</option>
              </select>
            </div>
            <div class="field input">
              <label>Email</label>
              <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
            </div>
            <div class="field input">
              <label>Số điện thoại</label>
              <input type="tel" name="phone" placeholder="Nhập số điện thoại" required>
            </div>
            <div class="field">
              <label>Tỉnh/thành phố</label>
              <select name="city" id="city" required>
                <option value="">Chọn tỉnh/thành phố</option>
                <?php foreach($this->city->message as $row): ?>      
                    <option value="<?= $row['matp'] ?>"><?= $row['name'] ?></option>           
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label>Quận/huyện</label>
              <select name="district" id="district" required>
                  <option value="">Chọn quận/huyện</option>
              </select>
            </div>
            <div class="field">
              <label>Xã/phường</label>
              <select name="commune" id="commune" required>
                  <option value="">Chọn xã/phường</option>
              </select>
            </div>
            <div class="field input">
              <label>Mật khẩu</label>
              <input type="password" name="password" id="pass_log_id" placeholder="Đặt mật khẩu" required>
              <i class="fas fa-eye toggle-password" toggle="#password-field"></i>
            </div>
            <div class="field image">
              <label>Chọn hình ảnh</label>
              <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
            </div>
            <div class="field button">
              <input type="submit" name="submit" value="Đăng ký">
            </div>
          </form>
          <div class="link">Bạn đã có tài khoản rồi? <a href="/">Nhấp vào đây để đăng nhập</a></div>
    </section>
  </div>
</div>
<?php
    }
}
