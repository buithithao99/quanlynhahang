<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;

class UserListPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Danh sách người dùng";
        $this->rows = UserAPI::getAllUser($_SESSION['user_id']);
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
        <h1 class="page-header">Người dùng
            <small>Danh sách</small>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
    <table class="table table-striped table-bordered table-hover" id="menu-table">
        <thead>
            <tr align="center">
                <th>ID</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
                <th>City</th>
                <th>District</th>
                <th>Commune</th>
                <th>Status</th>
                <th>Active</th>
                <th>Delete</th>
                <th>Edit</th>
                <th>Block</th>
                <th>Enable</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->rows->message as $row): ?>
                <tr class="even gradeC" align="center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['type'] === 'customer'? "Khách hàng online":"Phục vụ" ?></td>
                    <td><?= $row['name_city'] ?></td>
                    <td><?= $row['name_district'] ?></td>
                    <td><?= $row['name_commune'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['active'] ?></td>
                    <td class="center"><i class="fas fa-trash"></i> <a data-href="/deleteuser/<?= $row['id'] ?>" data-target="#confirm-delete" data-toggle="modal"> Delete</a></td>
                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    Bạn có chắc chắn sẽ muốn xóa người dùng này ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-danger btn-ok">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <td class="center"><i class="fas fa-edit"></i> <a href="/edituser/<?= $row['id'] ?>">Edit</a></td>
                    <td class="center"><i class="fas fa-lock"></i> <a href="/blockuser/<?= $row['id'] ?>">Block</a></td>
                    <td class="center"><i class="fas fa-check-circle"></i> <a href="/enableuser/<?= $row['id'] ?>">Enable</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }}