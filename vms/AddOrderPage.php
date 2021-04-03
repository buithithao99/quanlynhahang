<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\OrderModel;
class AddOrderPage {

    public $category;
    
    public function __construct($params = null) {
        session_start();
        $this->title  = "Tạo đơn đặt hàng";
        $this->category = UserAPI::getAllCategory();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        if(isset($_POST['submit'])){
            $order = new OrderModel($_POST);
            UserAPI::addOrder($order);
        }
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
                <label>Email</label>
                <input type="text" class="form-control" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
                <label>Danh mục</label>
                <select class="form-control" name="category_id" id="category_id" required>
                    <option>Xin mời chọn danh mục</option>
                    <?php foreach($this->category->message as $row): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Đồ ăn/Thức uống</label>
                <select class="form-control" name="product_id" id="product_id" required></select>
            </div>
            <div class="form-group">
                <label>Giá cả</label>
                <input type="text" class="form-control" name="price" id="price" value=0 disabled>
            </div>
            <div class="form-group">
                <label>Số lượng</label>
                <input type="num" class="form-control" name="quantity" id="quantity" min=1 required>
            </div>
            <div class="form-group">
                <label>Tổng tiền</label>
                <input type="text" class="form-control" name="total" id="total" value=0 disabled>
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