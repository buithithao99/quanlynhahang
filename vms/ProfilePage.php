<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;

class ProfilePage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->rows = UserAPI::getUserById($_SESSION['user_id']);
        $this->title  = "Thông tin cá nhân";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }
    
    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
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

            <ul class="nav nav-pills nav-stacked menu-page">
                <li><a href="/profile"> <i class="fa fa-user"></i> Thông tin cá nhân</a></li>
                <?php if($row['type'] !== "admin"): ?>
                    <li><a href="/previousorder"> <i class="fa fa-calendar"></i>Thông tin đơn hàng</a></li>
                <?php endif; ?>
                <li><a href="/editprofileform"> <i class="fa fa-edit"></i> Sửa thông tin cá nhân</a></li>
            </ul>
        </div>
    </div>
    <div class="profile-info col-md-9">
        <div class="panel">
            <div class="panel-body bio-graph-info">
                <h1>Vị trí: <?php
                    if($row['type'] === "customer"){
                        echo "Khách hàng online";
                    }elseif($row['type'] === "cashier"){
                        echo "Thu ngân";
                    }elseif($row['type'] === "serve"){
                        echo "Phục vụ";
                    }else{
                        echo "Admin";
                    }
                ?></h1>
                <div class="row">
                    <div class="bio-row">
                        <p><span>Tên </span>: <?= $row['firstname'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Họ </span>: <?= $row['lastname'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Thành phố/tỉnh </span>: <?= $row['name_city'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Quận/huyện </span>: <?= $row['name_district'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Xã/phường </span>:  <?= $row['name_commune'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Email </span>:  <?= $row['email'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Số điện thoại </span>: <?= $row['phone'] ?></p>
                    </div>
                    <div class="bio-row">
                        <p><span>Trạng thái </span>:  <?= $row['status'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php }}