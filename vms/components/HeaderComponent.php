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
        <?php if($_SESSION['type']==='admin' || $_SESSION['temporary_type']==='admin' || $_SESSION['type']==='cashier' || $_SESSION['temporary_type']==='cashier'): ?>
            <a class="navbar-brand" href="/dashboard"><i class="fas fa-home"></i></a>
        <?php elseif($_SESSION['type']==='customer' || $_SESSION['temporary_type']==='customer' || $_SESSION['type']==='serve' || $_SESSION['temporary_type']==='serve'): ?>
            <?php if(!empty($_SESSION['checkout-success'])): ?>
                <a class="navbar-brand" href="/homepage"><i class="fas fa-home"></i></a>
            <?php else: ?>
                <a class="navbar-brand" href="/return/<?= $_SESSION['user_id'] ?>"><i class="fas fa-home"></i></a>
            <?php endif; ?>
        <?php else: ?>
            <a class="navbar-brand" href="/order"><i class="fas fa-home"></i></a>
        <?php endif; ?>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <?php if($_SESSION["type"] === "customer" ||  $_SESSION['temporary_type']==='customer' || $_SESSION["type"] === "serve" ||  $_SESSION['temporary_type']==='serve'): ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-shopping-cart"></i>  
                    <?php if(!empty($_SESSION["shopping_cart"])): ?>
                        <span class="quantity"><?= count($_SESSION["shopping_cart"]) ?></span>
                    <?php endif; ?>
                    <i class="fa fa-caret-down"></i>
                </a>
                <?php if($_SESSION["type"] === "customer" ||  $_SESSION['temporary_type']==='customer'): ?>
                <ul class="dropdown-menu dropdown-cart">
                    <li><a href="/cart">Thanh to??n ti???n m???t</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="/cartonline">Thanh to??n online</a>
                    </li>
                </ul>
                <?php elseif($_SESSION["type"] === "serve" ||  $_SESSION['temporary_type']==='serve'): ?>
                    <ul class="dropdown-menu dropdown-cart">
                    <li><a href="/cart">Thanh to??n ti???n m???t</a>
                    </li>
                </ul>
                <?php endif; ?>
            </li>
        <?php endif; ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="/profile"><i class="fa fa-user fa-fw"></i> Th??ng tin c?? nh??n</a>
                </li>
                <li class="divider"></li>
                <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> ????ng xu???t</a>
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
                    <?php if($_SESSION['type']==='cashier' || $_SESSION['temporary_type']==='cashier' || $_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                    <li>
                        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i> B???ng ??i???u khi???n</a>
                    </li>
                    <?php endif; ?>
                    <?php if($_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                    <li>
                        <a href="#"><i class="fas fa-list"></i> Danh m???c<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/category">Qu???n l?? danh m???c</a>
                            </li>
                            <li>
                                <a href="/addcategory">Th??m danh m???c</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-mountain"></i> V??ng mi???n<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/region">Qu???n l?? v??ng mi???n</a>
                            </li>
                            <li>
                                <a href="/addregion">Th??m v??ng mi???n</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <?php endif; ?>
                    <?php if( $_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                        <li>
                            <a href="#"><i class="fas fa-chair"></i> Ch??? ng???i<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="/table">Qu???n l?? ch??? ng???i</a>
                                </li>
                            <?php if($_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                                <li>
                                    <a href="/addtable">Th??m ch??? ng???i</a>
                                </li>
                            <?php endif; ?>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php endif; ?>
                    <?php if($_SESSION['type']==='admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                    <li>
                        <a href="#"><i class="fas fa-box-open"></i> S???n ph???m<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/product">Qu???n l?? s???n ph???m</a>
                            </li>
                            <li>
                                <a href="/addproduct">Th??m s???n ph???m</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-users"></i> Ng?????i d??ng<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/users">Qu???n l?? ng?????i d??ng</a>
                            </li>
                            <li>
                                <a href="/adduser">Th??m ngu???i d??ng</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <?php endif; ?>
                <?php if($_SESSION['type']==='admin' || $_SESSION['type']==='cashier' ||  $_SESSION['temporary_type']==='admin' ||  $_SESSION['temporary_type']==='cashier'): ?>
                <li>
                    <a href="#"><i class="fas fa-receipt"></i> ?????t h??ng<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/order">Qu???n l?? ????n ?????t h??ng</a>
                        </li>
                        <?php if($_SESSION['type'] === 'admin' ||  $_SESSION['temporary_type']==='admin'): ?>
                        <?php endif; ?>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php endif; ?>
                <?php if($_SESSION['type']==='customer'|| $_SESSION['type']==='serve' || $_SESSION['temporary_type']==='customer' || $_SESSION['temporary_type']==='serve'): ?>
                    <li>
                        <a href="#"><i class="fas fa-utensils"></i> M??n ??n/Th???c u???ng<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/northproduct">B???c</a>
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
                <?php endif; ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>

<?php }}
