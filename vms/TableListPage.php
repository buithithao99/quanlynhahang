<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
class TableListPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Danh sách chỗ ngồi";
        $this->rows = UserAPI::getAllTable();
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
        <h1 class="page-header">Chỗ ngồi
            <small>Danh sách</small>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
    <table class="table table-striped table-bordered table-hover" id="menu-table">
        <thead>
            <tr align="center">
                <th>ID</th>
                <th>Type</th>
                <th>Active</th>
                <th>Delete</th>
                <th>Edit</th>
                <th>Enable</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->rows->message as $row): ?>
                <tr class="even gradeC" align="center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['type'] ?></td>
                    <td><?= $row['active'] ?></td>
                    <td class="center"><i class="fas fa-trash"></i> <a href="/deletetable/<?= $row['id'] ?>"> Delete</a></td>
                    <td class="center"><i class="fas fa-edit"></i> <a href="/edittable/<?= $row['id'] ?>">Edit</a></td>
                    <td class="center"><i class="fas fa-check-circle"></i> <a href="/enabletable/<?= $row['id'] ?>">Enable</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }}