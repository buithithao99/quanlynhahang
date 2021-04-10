<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\RegionModel;
class EditRePage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->rows = UserAPI::getRegionById($params[0]);
        $this->title  = "Sửa vùng miền";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $region = new RegionModel($_POST);
            UserAPI::updateReById($region);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Vùng miền
                <small>Sửa</small>
            </h1>
        </div>
        <div class="col-lg-7">
            <form action="/editre" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
                <div class="form-group">
                    <label>Tên vùng miền</label>
                    <input type="text" class="form-control" name="name" placeholder="Nhập tên vùng miền" value="<?= $row['name'] ?>" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Sửa</button>
                <a type="button" href="/region" class="btn btn-danger">Quay lại</a>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php }}