<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\CategoryModel;
class EditCatePage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->rows = UserAPI::getCategoryById($params[0]);
        $this->title  = "Sửa danh mục";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $category = new CategoryModel($_POST);
            UserAPI::updateCateById($category);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Danh mục
                <small>Sửa</small>
            </h1>
        </div>
        <div class="col-lg-7">
            <form action="/editcate" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
                <div class="form-group">
                    <label>Tên</label>
                    <input type="text" class="form-control" name="name" placeholder="Nhập tên danh mục" value="<?= $row['name'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select class="form-control" name="active" id="active" required>
                        <?php if($row['active'] === 'enabled'): ?>
                            <option value="enabled" selected>Hoạt động</option>
                            <option value="disabled">Không hoạt động</option>
                        <?php elseif($row['active'] === 'disabled'): ?>
                            <option value="enabled">Hoạt động</option>
                            <option value="disabled" selected>Không hoạt động</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Sửa</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php }}