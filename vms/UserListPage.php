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
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->rows->message as $row): ?>
                <tr class="even gradeC" align="center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['type'] ?></td>
                    <td><?= $row['name_city'] ?></td>
                    <td><?= $row['name_district'] ?></td>
                    <td><?= $row['name_commune'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['active'] ?></td>
                    <td class="center"><i class="fas fa-trash"></i> <a href="/deleteuser/<?= $row['user_id'] ?>"> Delete</a></td>
                    <td class="center"><i class="fas fa-edit"></i> <a href="/edituser/<?= $row['user_id'] ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }}