<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
use models\ProductModel;

class ProductEditPage {
    public $rows;
    public $cate;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Sửa sản phẩm";
        $this->rows = UserAPI::getProductById($params[0]);
        $this->cate = UserAPI::getAllCategory();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            $product = new ProductModel($_POST,$_FILES);
            UserAPI::updateProductById($product);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sản phẩm
                <small>Sửa</small>
            </h1>
        </div>
        <div class="col-lg-7">
            <form action="/editpro" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
                <div class="form-group">
                    <label>Tên</label>
                    <input type="text" class="form-control" name="name" placeholder="Nhập tên sản phẩm" value="<?= $row['name'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Giá tiền</label>
                    <input type="number" class="form-control" name="price" placeholder="Nhập giá tiền sản phẩm" value="<?= $row['price'] ?>" required>
                </div>  
                <div class="form-group">
                    <label>Chi tiết sản phẩm</label>
                    <input type="text" class="form-control" name="description" placeholder="Nhập chi tiết sản phẩm" value="<?= $row['description'] ?>">
                </div> 
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" class="form-control" name="quantity" min="1" placeholder="Nhập số lượng sản phẩm" value="<?= $row['quantity'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Vùng miền</label>
                    <select class="form-control" name="region" id="region" required>
                        <?php if($row['region'] === "north"): ?>
                            <option value="north" selected>Miền Bắc</option>
                            <option value="central">Miền Trung</option>
                            <option value="south">Miền Nam</option>
                        <?php elseif($row['region'] === "central"): ?>
                            <option value="north">Miền Bắc</option>
                            <option value="central" selected>Miền Trung</option>
                            <option value="south">Miền Nam</option>
                        <?php elseif($row['region'] === "south"): ?>
                            <option value="north">Miền Bắc</option>
                            <option value="central">Miền Trung</option>
                            <option value="south" selected>Miền Nam</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Danh mục</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <?php foreach($this->cate->message as $cate): ?>
                            <?php if($cate['id'] === $row['category_id']): ?>
                                <option value="<?= $cate['id'] ?>" selected><?= $cate['name'] ?></option>
                            <?php else: ?>
                                <option value="<?= $cate['id'] ?>"><?= $cate['name'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Chọn hình ảnh</label>
                    <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
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