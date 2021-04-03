<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;

class ProductListPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Danh sách sản phẩm";
        $this->rows = UserAPI::getAllProduct();
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
        <h1 class="page-header">Sản phẩm
            <small>Danh sách</small>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
    <table class="table table-striped table-bordered table-hover" id="menu-table">
        <thead>
            <tr align="center">
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Region</th>
                <th>Active</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->rows->message as $row): ?>
                <tr class="even gradeC" align="center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['cate_name'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['image'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['region_name'] ?></td>
                    <td><?= $row['active'] ?></td>
                    <td class="center"><i class="fas fa-trash"></i> <a href="/deletepro/<?= $row['id'] ?>"> Delete</a></td>
                    <td class="center"><i class="fas fa-edit"></i> <a href="/editpro/<?= $row['id'] ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }}