<?php
namespace vms;
use vms\templates\HomeTemplate;
use api\v1\UserAPI;
class SingleTablePage {
    public $rows;
    public function __construct($params = null) {
        $this->title  = "Danh sách bàn đơn";
        $this->rows = UserAPI::getSingleTable();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new HomeTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Danh sách bàn đơn</h1>
    </div>
</div>
<div class="row">
    <?php  foreach($this->rows->message as $row): ?>
        <?php
            $res = UserAPI::getStatusFromTable($row['id']);
        ?>
        <div class="col-lg-3">
            <form action="/booking" method="POST">
                <input type="hidden" name="table_id" value="<?= $row['id'] ?>" />
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />
                <div class="table-item-box">
                    <div class="product-item">
                        <div class="product-detail">
                            <img src="/assets/img/table/single.jpg" alt="bàn đơn" class="product-image" width="100%" height="70%"/>
                            <div class="table-name">Bàn đơn</div>
                            <div class="table-id">Số: <?= $row['id'] ?></div>
                        </div>
                    </div>
                    <?php if($_SESSION['type'] === 'customer'): ?>
                        <?php if($res->message[0]['active'] === 'enabled'): ?>
                            <div class="col text-center">
                                <button name="submit" type="submit" class="booking-button"><i
                                        class="fas fa-shopping-cart"></i>Đặt bàn</button>
                            </div>
                        <?php else: ?>
                            <div class="col text-center">Đã có khách hàng đặt trước, xin vui lòng quý khách quay lại sau.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php }}