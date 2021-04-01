<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\CategoryModel;
class AddCategoryPage {
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->title  = "Thêm danh mục";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $category = new CategoryModel($_POST);
            UserAPI::addCategory($category);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Danh mục
            <small>Thêm</small>
        </h1>
    </div>
    <div class="col-lg-7">
        <form action="/addcategory" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên danh mục" required>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select class="form-control" name="active" id="active" required>
                    <option value="enabled">Hoạt động</option>
                    <option value="disabled">Không hoạt động</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>
<?php }}