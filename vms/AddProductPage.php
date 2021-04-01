<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\ProductModel;
class AddProductPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Thêm sản phẩm";
        $this->rows = UserAPI::getAllCategory();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $product = new ProductModel($_POST,$_FILES);
            UserAPI::addProduct($product);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row" style="margin-bottom:2rem;">
    <div class="col-lg-12">
        <h1 class="page-header">Sản phẩm
            <small>Thêm</small>
        </h1>
    </div>
    <div class="col-lg-7">
        <form action="/addproduct" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tên</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="form-group">
                <label>Giá tiền</label>
                <input type="number" class="form-control" name="price" placeholder="Nhập giá tiền sản phẩm" required>
            </div>  
            <div class="form-group">
                <label>Chi tiết sản phẩm</label>
                <input type="text" class="form-control" name="description" placeholder="Nhập chi tiết sản phẩm">
            </div> 
            <div class="form-group">
                <label>Số lượng</label>
                <input type="number" class="form-control" name="quantity" min="1" placeholder="Nhập số lượng sản phẩm" required>
            </div>
            <div class="form-group">
                <label>Danh mục</label>
                <select class="form-control" name="category_id" id="category_id" required>
                    <?php foreach($this->rows->message as $row): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group regions">
                <label>Vùng miền</label>
                <select class="form-control" name="region" id="region">
                    <option value="" selected>Chọn vùng miền</option>
                    <option value="north">Miền Bắc</option>
                    <option value="central">Miền Trung</option>
                    <option value="south">Miền Nam</option>
                </select>
            </div>
            <div class="form-group">
                <label>Chọn hình ảnh</label>
                <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
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