<?php
namespace vms;

use vms\templates\AdminTemplate;
use api\v1\UserAPI;

class PreviousOrderPage
{
    public $rows;
    public $orders;
    public function __construct($params = null)
    {
        session_start();
        $this->title  = "Sửa thông tin";
        $this->rows = UserAPI::getUserById($_SESSION['user_id']);
        $this->orders = UserAPI::getAllOrderById($_SESSION['user_id']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
?>
<div class="row">
    <?php foreach($this->rows->message as $row): ?>
        <div class="profile-nav col-md-3">
            <div class="panel">
                <div class="user-heading round">
                    <a href="#">
                        <img src="/images/user/<?= $row['img'] ?>" alt="">
                    </a>
                    <h1><?= $row['firstname']." ".$row['lastname'] ?></h1>
                    <p><?= $row['email'] ?></p>
                </div>

                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/profile"> <i class="fa fa-user"></i> Thông tin cá nhân</a></li>
                    <li><a href="/previousorder"> <i class="fa fa-calendar"></i> Thông tin đơn hàng</a></li>
                    <li><a href="/editprofileform"> <i class="fa fa-edit"></i> Sửa thông tin cá nhân</a></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="profile-info col-md-9">
        <div class="panel">
            <div class="panel-body bio-graph-info">
                <?php
                    if(isset($_SESSION['checkout-success'])){
                        echo $_SESSION['checkout-success'];
                    }
                ?>
                <h1>Đơn hàng của bạn</h1>
                <?php if(count($this->orders->message) > 0): ?>
                <table class="table table-striped table-bordered table-hover" id="menu-table">
                    <thead>
                        <tr align="center">
                            <th>Bàn</th>
                            <th>Loại bàn</th>
                            <th>Mã hóa đơn</th>
                            <th>Ngày thanh toán</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <td>Chi tiết</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->orders->message as $row): ?>
                            <tr class="even gradeC" align="center">
                                <td><?= !is_null($row['table_id'])?"Số ".$row['table_id']:"không có" ?></td>
                                <td><?= !is_null($row['table_type'])?$row['table_type']:"không có" ?></td>
                                <td><?= $row['order_id'] ?></td>
                                <td><?= $row['order_day'] ?></td>
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
                                <td class="center"><i class="fa fa-eye" aria-hidden="true"></i> <a href="/seeorder/<?= $row['order_id'] ?>">Xem</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div>Hiện tại bạn chưa có đơn hàng nào cả.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
    }
}
