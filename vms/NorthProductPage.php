<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;
class NorthProductPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Món ăn miền Bắc";
        $this->rows = UserAPI::getProductNorth();
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
        <h1 class="page-header">Món ăn
            <small>Miền Bắc</small>
        </h1>
    </div>
    <div class="col-lg-12">
        <div id="north-banner" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#north-banner" data-slide-to="0" class="active"></li>
                <li data-target="#north-banner" data-slide-to="1"></li>
                <li data-target="#north-banner" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="/assets/img/banner/north-banner-1.jpg" width=100%>
                </div>

                <div class="item">
                    <img src="/assets/img/banner/north-banner-2.jpg" width=100%>
                </div>

                <div class="item">
                    <img src="/assets/img/banner/north-banner-3.jpg" width=100%>
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#north-banner" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#north-banner" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <?php  foreach($this->rows->message as $row): ?>
        <div class="col-lg-3">
            <form action="/addtocart" method="POST">
                <input type="hidden" name="image" value="<?= $row['image'] ?>" />
                <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                <input type="hidden" name="name" value="<?= $row['name'] ?>" />
                <input type="hidden" name="price" value="<?= $row['price'] ?>" />
                <input type="hidden" name="region" value="<?= $row['region'] ?>" />
                <input type="hidden" name="description" value="<?= $row['description'] ?>" />
                <div class="product-item-box">
                    <div class="product-item">
                        <div class="product-detail">
                            <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                            <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                            <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            <input type="number" name="qty" min=1 class="qty" class="qty" required/>
                        </div>
                    </div>
                    <div class="col text-center">
                        <button name="submit" type="submit"><i
                                class="fas fa-shopping-cart mr-2"></i>Mua</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<?php }}