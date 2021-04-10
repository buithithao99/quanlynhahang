<?php
namespace vms\templates;

use vms\components\HeaderHomeComponent;
use api\v1\UserAPI;

class HomeTemplate {
    public function renderChild($child) {
        session_start();
        $res = UserAPI::getUserById($_SESSION['user_id']);
        $_SESSION['temporary_type'] = $res->message[0]['type'];
        $this->child = $child;
        $this->render();
    }

    public function __construct($params = null) {}

    public function render() {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $this->child->title ?></title>
        <link rel='shortcut icon' type='image/x-icon' href='/assets/img/favicon.ico'/>
        <!-- Bootstrap Core CSS -->
        <link href="/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- MetisMenu CSS -->
        <link href="/assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet" type="text/css">

        <!-- Custom CSS -->
        <link href="/assets/css/admin.css" rel="stylesheet" type="text/css">

        <!-- Custom Fonts -->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

        <!-- DataTables CSS -->
        <link href="/assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="/assets/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
        
        <!-- Library -->
    </head>
    <body>
        <div id="wrapper">
            <?php (new HeaderHomeComponent())->render(); ?>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <nav class="navbar navbar-inverse" style="margin-top:1rem;">
                        <div class="container-fluid">
                            <ul class="nav navbar-nav">
                                <li><a href="/refernorth">Bắc</a></li>
                                <li><a href="/refercentral">Trung</a></li>
                                <li><a href="/refersouth">Nam</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Đặt món
                                    <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                    <li><a href="/singletable">Đơn</a></li>
                                    <li><a href="/doubletable">Đôi</a></li>
                                    <li><a href="/othertable">2 người trở lên</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <?php $this->child->__render(); ?>
                </div>
            </div>
        </div>
    </body>
    <!-- jQuery -->
    <script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/assets/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/assets/js/handle.js"></script>

    <!-- DataTables JavaScript -->
    <script src="/assets/bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="/assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    </html>
<?php }}