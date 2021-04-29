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
    <?php  if(count($this->rows->message) > 0): ?>
        <table class="table table-striped table-bordered table-hover" id="menu-table">
            <thead>
                <tr align="center">
                    <th>Id</th>
                    <th>Name</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Delete</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach($this->rows->message as $row): ?>
                        <tr class="even gradeC" align="center">
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['firstname']." ".$row['lastname'] ?></td>
                            <td><?= $row['total'] ?></td>
                            <td>
                                <?php
                                    if($row['type'] === "0"){
                                        if($row['status'] === 'handle'){
                                            echo "Chờ xác nhận";
                                        }elseif($row['status'] === 'complete'){
                                            echo "Đã thanh toán";
                                        }elseif($row['status'] === 'cancle'){
                                            echo "Đã hủy";
                                        }
                                    }else{
                                        if($row['status'] === 'handle'){
                                            echo "Chờ xác nhận";
                                        }elseif($row['status'] === 'ship'){
                                            echo "Xác nhận";
                                        }elseif($row['status'] === 'complete'){
                                            echo "Hoàn thành";
                                        }elseif($row['status'] === 'cancle'){
                                            echo "Đã hủy";
                                        }
                                    }
                                ?>
                            </td>
                            <td><?= $row['type'] === "0" ? "Thanh toán trực tiếp":"Thanh toán online" ?></td>
                            <td class="center"><i class="fas fa-trash"></i> <a data-href="/deleteorder/<?= $row['order_id'] ?>" data-target="#confirm-delete" data-toggle="modal"> Delete</a></td>
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
                            <td class="center"><i class="fa fa-eye" aria-hidden="true"></i> <a href="/seeorder/<?= $row['order_id'] ?>">See detail</a></td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="col-lg-12">Hiện tại chưa có đơn hàng nào.</div>
    <?php endif; ?>
</div>
<?php }}