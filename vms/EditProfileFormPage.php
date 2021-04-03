<?php
namespace vms;

use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\UserModel;

class EditProfileFormPage
{
    public $rows;
    public $city;
    public $orders;
    public function __construct($params = null)
    {
        session_start();
        $this->title  = "Sửa thông tin";
        $this->rows = UserAPI::getUserById($_SESSION['user_id']);
        $this->city = UserAPI::getAllCity();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $user = new UserModel($_POST,$_FILES);
            $res = UserAPI::update($user,$_SESSION['user_id']);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="profile-nav col-md-3">
            <div class="panel">
                <div class="user-heading round">
                    <a href="#">
                        <img src="/images/user/<?= $row['img'] ?>" alt="">
                    </a>
                    <h1><?= $row['firstname']." ".$row['lastname'] ?></h1>
                    <p><?= $row['email'] ?></p>
                </div>

                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/profile"> <i class="fa fa-user"></i> Thông tin cá nhân</a></li>
                    <li><a href="/previousorder"> <i class="fa fa-calendar"></i> Thông tin đơn hàng</a></li>
                    <li><a href="/editprofileform"> <i class="fa fa-edit"></i> Sửa thông tin cá nhân</a></li>
                </ul>
            </div>
        </div>
        <div class="profile-info col-md-9">
            <form action="/editprofileform" method="POST" enctype="multipart/form-data" style="margin-bottom:2rem;">
                <input type="hidden" value="<?= $row['email'] ?>" name="email" />
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Tên</label>
                        <input type="text" class="form-control" placeholder="Nhập tên" name="firstname" value="<?= $row['firstname'] ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Họ</label>
                        <input type="text" class="form-control" placeholder="Nhập họ" name="lastname" value="<?= $row['lastname'] ?>" required>
                    </div> 
                </div>
                <div class="form-group">
                    <label>Giới tính</label>
                    <select class="form-control" name="gender" id="gender" required>
                        <?php if($row['gender']==='male'): ?>
                            <option value="male" selected>Nam</option>
                            <option value="female">Nữ</option>
                        <?php elseif($row['gender']==='female'): ?>
                            <option value="male">Nam</option>
                            <option value="female" selected>Nữ</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại" value="<?= $row['phone'] ?>" required>
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
                    <label>Chọn hình ảnh</label>
                    <input type="file" name="image" class="form-control-file" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php
    }
}
