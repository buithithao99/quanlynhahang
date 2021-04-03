<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\RegionModel;
class AddRegionPage {
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->title  = "Thêm vùng miền";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $region = new RegionModel($_POST);
            UserAPI::addRegion($region);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Vùng miền
            <small>Thêm</small>
        </h1>
    </div>
    <div class="col-lg-7">
        <form action="/addregion" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên vùng miền</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên vùng miền" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>
<?php }}