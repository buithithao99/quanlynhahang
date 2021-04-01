<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
class VipTablePage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Bàn vip";
        $this->rows = UserAPI::getVipTable();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Bàn
            <small>Vip</small>
        </h1>
    </div>
</div>
<div class="row">
    <?php  foreach($this->rows->message as $row): ?>
        <div class="col-lg-3">
            <form action="/addtocart" method="POST">
                <input type="hidden" name="image" value="<?= $row['image'] ?>" />
                <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                <input type="hidden" name="name" value="<?= $row['name'] ?>" />
                <input type="hidden" name="price" value="<?= $row['price'] ?>" />
                <input type="hidden" name="description" value="<?= $row['description'] ?>" />
                <div class="product-item-box">
                    <div class="product-item">
                        <div class="product-detail">
                            <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                            <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                            <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            <input type="number" name="qty" min=1 class="qty" class="qty"/>
                        </div>
                    </div>
                    <div class="col text-center">
                        <button name="submit" type="submit"><i
                                class="fas fa-shopping-cart mr-2"></i>Đặt bàn</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php }}