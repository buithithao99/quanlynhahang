<?php
namespace vms;
use vms\templates\FormTemplate;
use api\v1\UserAPI;

class ResetPasswordMailPage {
    public $rows;
    public function __construct($params = null) {
        $this->title  = "Nhập mật khẩu mới";
        $this->rows = UserAPI::getUserByEmail($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new FormTemplate();
        $template->renderChild($this);
        if(isset($_POST['submit'])){
            UserAPI::updatePasswordById($_POST['user_id'],$_POST['password']);
            if($this->rows->status){
                header("Location: /");
            }
        }
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="wrapper">
  <div class="form-area">
    <section class="form">
        <header>Lấy lại mật khẩu</header>
        <?php foreach($this->rows->message as $row): ?>
            <form action="/reset" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" value="<?= $row['id'] ?>" name="user_id" />
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu mới" required>
                </div>
                <div class="field button">
                <input type="submit" name="submit" value="Gửi">
                </div>
            </form>
        <?php endforeach; ?>
    </section>
  </div>
</div>
<?php }}