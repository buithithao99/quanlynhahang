<?php
namespace vms;

use api\v1\UserAPI;
use vms\templates\AdminTemplate;
class OrderSeePage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Chi tiết đơn hàng";
        $this->rows = UserAPI::getProductByOrder($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        if(isset($_POST['submit'])){
            UserAPI::updateStatus($_POST);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<?php foreach($this->rows->message as $row): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Đơn đặt hàng
                <small>Chi tiết</small>
            </h1>
        </div>
        <!-- /.col-lg-12 -->
        <?php if($_SESSION['type'] === 'admin'): ?>
            <form method="POST" action="/seeorder" style="margin-bottom: 3rem;">
                <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>" />
                <select name="status" class="status" style="padding:0.4rem 0;outline:none;">
                    <?php if($row['type'] === "0"): ?>
                        <?php if($row['status'] === 'handle'): ?>
                            <option value="handle" selected>Chờ xác nhận</option>
                            <option value="complete">Xác nhận thanh toán</option>
                            <option value="cancle">Hủy đơn hàng</option>
                        <?php elseif($row['status'] === 'handle'): ?>
                            <option value="handle">Chờ xác nhận</option>
                            <option value="complete" selected>Xác nhận thanh toán</option>
                            <option value="cancle">Hủy đơn hàng</option>
                        <?php else: ?>
                            <option value="handle">Chờ xác nhận</option>
                            <option value="complete">Xác nhận thanh toán</option>
                            <option value="cancle" selected>Hủy đơn hàng</option>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($row['status'] === 'handle'): ?>
                            <option value="handle" selected>Đang nhận hàng</option>
                            <option value="ship">Đang giao hàng</option>
                            <option value="complete">Đã giao hàng</option>
                            <option value="cancle">Hủy đơn hàng</option>
                        <?php elseif($row['status'] === 'ship'): ?>
                            <option value="handle">Đang nhận hàng</option>
                            <option value="ship" selected>Đang giao hàng</option>
                            <option value="complete">Đã giao hàng</option>
                            <option value="cancle">Hủy đơn hàng</option>
                        <?php elseif($row['status'] === 'complete'): ?>
                            <option value="handle">Đang nhận hàng</option>
                            <option value="ship">Đang giao hàng</option>
                            <option value="complete" selected>Đã giao hàng</option>
                            <option value="cancle">Hủy đơn hàng</option>
                        <?php else: ?>
                            <option value="handle">Đang nhận hàng</option>
                            <option value="ship">Đang giao hàng</option>
                            <option value="complete">Đã giao hàng</option>
                            <option value="cancle" selected>Hủy đơn hàng</option>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" name="submit">Cập nhật</button>
            </form>
        <?php endif; ?>
        <?php if($row['type'] === "1"): ?>
            <div style="float:left;">
                <h3>Bên thanh toán</h3>
                <p>Tên: <?= $row['firstname']." ".$row['lastname'] ?></p>
                <p>Email: <?= $row['email'] ?></p>
                <p>Số điện thoại: <?= $row['phone_number'] ?></p>
            </div>
            <div style="float:right;">
                <h3>Bên nhận hàng</h3>
                <p>Tên: <?= $row['recipient_name'] ?></p>
                <p>Địa chỉ: <?= $row['address'] ?></p>
                <p>Số điện thoại: <?= $row['phone'] ?></p>
            </div>
        <?php endif; ?>
        <table class="table table-striped table-bordered table-hover" id="menu-table">
            <thead>
                <tr align="center">
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even gradeC" align="center">
                    <th><?= $row['name'] ?></th>
                    <th><?= $row['price'] ?></th>
                    <th><?= $row['order_qty'] ?></th>
                </tr>
            </tbody>
        </table>
    </div>
<?php endforeach; ?>
<?php }}