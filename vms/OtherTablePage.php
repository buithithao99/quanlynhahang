<?php
namespace vms;
use vms\templates\HomeTemplate;
use api\v1\UserAPI;
class OtherTablePage {
    public $rows;
    public function __construct($params = null) {
        $this->title  = "Danh sách bàn nhiều người";
        $this->rows = UserAPI::getOtherTable();
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
        <h1 class="page-header">Danh sách bàn nhiều người</h1>
    </div>
</div>
<div class="row">
    <?php $id = 1; foreach($this->rows->message as $row): ?>
        <?php $res = UserAPI::getStatusFromTable($row['id']); ?>
        <div class="col-lg-3">
            <form action="/booking" method="POST">
                <input type="hidden" name="tem_id" value="<?= $id ?>" />
                <input type="hidden" name="table_id" value="<?= $row['id'] ?>" />
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />
                <input type="hidden" name="type" value="Nhiều người" />
                <div class="table-item-box">
                    <div class="product-item">
                        <div class="product-detail">
                            <img src="/assets/img/table/other.jpg" alt="bàn nhiều người" class="product-image" width="80%" height="70%"/>
                            <div class="table-name" name="table-name">Bàn nhiều người</div>
                            <div class="table-id">Số: <?= $id ?></div>
                            <?php $id++; ?>
                        </div>
                    </div>
                    <?php if($_SESSION['type'] === 'serve'): ?>
                        <?php if($res->message[0]['active'] === 'enabled'): ?>
                            <div class="col text-center">
                                <button name="submit" type="submit" class="booking-button"><i
                                        class="fas fa-shopping-cart"></i>Đặt bàn</button>
                            </div>
                        <?php else: ?>
                            <div class="col text-center">Đã đặt trước.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php }}