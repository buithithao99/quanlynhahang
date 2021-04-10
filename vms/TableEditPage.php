<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\TableModel;
class TableEditPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Locaton: /");
        }
        $this->rows = UserAPI::getTableById($params[0]);
        $this->title  = "Sửa thông tin chỗ ngồi";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $table = new TableModel($_POST);
            UserAPI::updateTableById($table);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Thông tin chỗ ngồi
                <small>Sửa</small>
            </h1>
        </div>
        <div class="col-lg-7">
            <form action="/edittable" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
                <div class="form-group">
                <label>Loại</label>
                <select class="form-control" name="type" id="type" required>
                    <?php if($row['type'] === 'single'): ?>
                        <option value="single" selected>Đơn</option>
                        <option value="double">Đôi</option>
                        <option value="other">Từ 2 người trở lên</option>
                    <?php elseif($row['type'] === 'double'): ?>
                        <option value="single" selected>Đơn</option>
                        <option value="double">Đôi</option>
                        <option value="other">Từ 2 người trở lên</option>
                    <?php elseif($row['type'] === 'other'): ?>
                        <option value="single">Đơn</option>
                        <option value="double">Đôi</option>
                        <option value="other" selected>Từ 2 người trở lên</option>
                    <?php endif; ?>
                </select>
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
                <a type="button" href="/table" class="btn btn-danger">Quay lại</a>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php }}