<?php
namespace vms;

use api\v1\UserAPI;
use vms\templates\AdminTemplate;
class OrderListPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Danh sách đơn đặt hàng";
        $this->rows = UserAPI::getAllOrder();
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
        <h1 class="page-header">Đơn đặt hàng
            <small>Danh sách</small>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
    <table class="table table-striped table-bordered table-hover" id="menu-table">
        <thead>
            <tr align="center">
                <th>Id</th>
                <th>Email</th>
                <th>Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Delete</th>
                <th>Edit</th>
                <th>Cancel</th>
                <th>Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->rows->message as $row): ?>
                <tr class="even gradeC" align="center">
                    <td><?= $row['order_id'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['firstname']." ".$row['lastname'] ?></td>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['total'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td class="center"><i class="fas fa-trash"></i> <a data-href="/deleteorder/<?= $row['id'] ?>" data-target="#confirm-delete" data-toggle="modal"> Delete</a></td>
                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    Bạn có chắc chắn sẽ muốn xóa đơn đặt hàng này ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-danger btn-ok">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <td class="center"><i class="fas fa-edit"></i> <a href="/editorder/<?= $row['id'] ?>">Edit</a></td>
                    <td class="center"><i class="fas fa-ban"></i> <a data-href="/cancelorder/<?= $row['order_id'] ?>" data-target="#confirm-cancel" data-toggle="modal"> Cancel</a></td>
                    <div class="modal fade" id="confirm-cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    Bạn có chắc chắn sẽ muốn hủy đơn đặt hàng này ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-danger btn-ok">OK</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <td class="center"><i class="fas fa-shopping-bag"></i> <a data-href="/paid/<?= $row['order_id'] ?> " data-target="#confirm-paid" data-toggle="modal">Paid</a></td>
                    <div class="modal fade" id="confirm-paid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xác nhận đơn hàng này đã thanh toán
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-danger btn-ok">OK</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }}