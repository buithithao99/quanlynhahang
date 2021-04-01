<?php
namespace vms\components;

class HeaderComponent {
    public function __construct($params = null) {}

    public function render() {
?>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php if($_SESSION['type']==='admin' || $_SESSION['temporary_type']==='admin'): ?>
            <a class="navbar-brand" href="/dashboard"><i class="fas fa-home"></i></a>
        <?php elseif($_SESSION['type']==='customer' || $_SESSION['type']==='serve' ||  $_SESSION['temporary_type']==='customer' ||  $_SESSION['temporary_type']==='serve'): ?>
            <a class="navbar-brand" href="/northproduct"><i class="fas fa-home"></i></a>
        <?php else: ?>
            <a class="navbar-brand" href="/order"><i class="fas fa-home"></i></a>
        <?php endif; ?>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->

        <li class="nav-item">
            <a class="nav-link" href="/cart">
                <?php if($_SESSION["type"] === "customer" ||  $_SESSION['temporary_type']==='customer'): ?>
                    <i class="fas fa-shopping-cart"></i>
                <?php endif; ?>
                <?php if(!empty($_SESSION["shopping_cart"])): ?>
                    <span class="quantity"><?= count($_SESSION["shopping_cart"]) ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="/profile"><i class="fa fa-user fa-fw"></i> Thông tin cá nhân</a>
                </li>
                <li class="divider"></li>
                <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Đăng xuất</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation" style="position:fixed;">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <?php if($_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                    <li>
                        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-list"></i> Danh mục<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/category">Quản lý danh mục</a>
                            </li>
                            <li>
                                <a href="/addcategory">Thêm danh mục</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-box-open"></i> Sản phẩm<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/product">Quản lý sản phẩm</a>
                            </li>
                            <li>
                                <a href="/addproduct">Thêm sản phẩm</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-users"></i> Người dùng<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/users">Quản lý người dùng</a>
                            </li>
                            <li>
                                <a href="/adduser">Thêm nguời dùng</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                <?php endif; ?>
                <?php if($_SESSION['type']==='admin' || $_SESSION['type']==='cashier' ||  $_SESSION['temporary_type']==='admin' ||  $_SESSION['temporary_type']==='cashier'): ?>
                <li>
                    <a href="#"><i class="fas fa-receipt"></i> Đặt hàng<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/order">Quản lý đơn đặt hàng</a>
                        </li>
                        <?php if($_SESSION['type'] === 'admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                        <li>
                            <a href="/addorder">Thêm đơn hàng</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php endif; ?>
                <?php if($_SESSION['type']==='customer' || $_SESSION['type']==='cashier' || $_SESSION['type']==='serve' || $_SESSION['temporary_type']==='customer' || $_SESSION['temporary_type']==='cashier' || $_SESSION['temporary_type']==='serve'): ?>
                    <li>
                        <a href="#"><i class="fas fa-utensils"></i> Món ăn/Thức uống<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/northproduct">Bắc</a>
                            </li>
                            <li>
                                <a href="/centralproduct">Trung</a>
                            </li>
                            <li>
                                <a href="/southproduct">Nam</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-chair"></i> Đặt bàn<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/simple">Hạng thường</a>
                            </li>
                            <li>
                                <a href="/vip">Hạng vip</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>

<?php }}
