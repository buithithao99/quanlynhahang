<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\UserModel;
class AddUserPage {

    public $rows;
    public $city;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Thêm người dùng";
        $this->city = UserAPI::getAllCity();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
          $email = $_POST["email"];
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkExist = UserAPI::checkExistEmail($email);
            if ($checkExist->status) {
                $user = new UserModel($_POST,$_FILES);
                $res = UserAPI::saveUserByAdmin($user);
                if($res === "Invalid password"){
                  $_SESSION['error_add'] = "<div class='alert alert-danger'>Password must have uppercase letter, lower letter and number. <span class='close'>&times;</span></div>";
                }
            }else{
                $_SESSION['error_add'] = "<div class='alert alert-danger'>$email - This email already exist! <span class='close'>&times;</span></div>";
            }  
          }else{
            $_SESSION['error_add'] = "<div class='alert alert-danger'>$email is not a valid email! <span class='close'>&times;</span></div>";
          }
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row" style="margin-bottom:2rem;">
    <div class="col-lg-12">
        <h1 class="page-header">Người dùng
            <small>Thêm</small>
        </h1>
    </div>
    <div class="col-lg-7">
        <form action="/adduser" method="POST" enctype="multipart/form-data">
            <?= isset($_SESSION['error_add']) ? $_SESSION['error_add']:"" ?>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Tên</label>
                    <input type="text" class="form-control" placeholder="Nhập tên" name="firstname" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Họ</label>
                    <input type="text" class="form-control" placeholder="Nhập họ" name="lastname" required>
                </div> 
            </div>
            <div class="form-group">
                <label>Giới tính</label>
                <select class="form-control" name="gender" id="gender" required>
                    <option value="male" selected>Nam</option>
                    <option value="female">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Nhập địa chỉ email" required>
            </div>
            <div class="form-group">
                <label>Chức vụ</label>
                <select class="form-control" name="type" id="type" required>
                    <option value="cashier">Thu ngân</option>
                    <option value="serve">Khách tại quầy</option>
                </select>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại"    required>
            </div>
            <div class="form-group">
                <label>Tỉnh/thành phố</label>
                <select class="form-control" name="city" id="city" required>
                    <option value="">Chọn tỉnh/thành phố</option>
                    <?php foreach($this->city->message as $row): ?>      
                        <option value="<?= $row['matp'] ?>"><?= $row['name'] ?></option>           
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quận/huyện</label>
                <select class="form-control" name="district" id="district" required>
                    <option value="">Chọn quận/huyện</option>
                </select>
            </div>
            <div class="form-group">
                <label>Xã/phường</label>
                <select class="form-control" name="commune" id="commune" required>
                    <option value="">Chọn xã/phường</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" id="pass_log_id" class="form-control" placeholder="Đặt mật khẩu" required>
            </div>
            <div class="form-group">
                <label>Chọn hình ảnh</label>
                <input type="file" name="image" class="form-control-file" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>
<?php }}