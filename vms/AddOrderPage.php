<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\OrderModel;
class AddOrderPage {

    public $catetgory;
    public function __construct($params = null) {
        session_start();
        $this->title  = "Tạo đơn đặt hàng";
        $this->catetgory = UserAPI::getAllCategory();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row" style="margin-bottom:2rem;">
    <div class="col-lg-12">
        <h1 class="page-header">Đơn đặt hàng
            <small>Tạo</small>
        </h1>
    </div>
    <div class="col-lg-7">
        <form action="/addorder" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Danh mục</label>
                <select class="form-control" name="category_id" id="category_id" required>
                    <?php foreach($this->catetgory->message as $row): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select class="form-control" name="status" id="status" required>
                    <option value="complete">Hoàn thành</option>
                    <option value="cancle">Hủy</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>
<?php }}