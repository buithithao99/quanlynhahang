<?php
namespace vms\components;

class HeaderHomeComponent {
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
        <a class="navbar-brand" href="/homepage"><i class="fas fa-home"></i></a>
    </div>
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
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
                <?php if($_SESSION['type']==='customer' || $_SESSION['temporary_type']==='customer' || $_SESSION['type']==='serve' || $_SESSION['temporary_type']==='serve'): ?>
                <li>
                    <a href="/homepage"><i class="fas fa-tachometer-alt"></i> Trang chủ</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>          
<?php }}
