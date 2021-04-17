<?php
namespace vms;
use vms\templates\HomeTemplate;
use api\v1\UserAPI;

class HomePage {

    public $souths;
    public $norths;
    public $centrals;
    public function __construct($params = null) {
        $this->title  = "Trang chủ";
        if(isset($_SESSION['booking'])){
            unset($_SESSION['booking']);
        }
        $this->souths = UserAPI::getProductSouthLimit();
        $this->norths = UserAPI::getProductNorthLimit();
        $this->centrals = UserAPI::getProductCentralLimit();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new HomeTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="col-lg-12">
    <div id="sale-banner" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#sale-banner" data-slide-to="0" class="active"></li>
            <li data-target="#sale-banner" data-slide-to="1"></li>
            <li data-target="#sale-banner" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="/assets/img/banner/sale-banner-1.jpg" width=100%>
            </div>

            <div class="item">
                <img src="/assets/img/banner/sale-banner-2.jpg" width=100%>
            </div>

            <div class="item">
                <img src="/assets/img/banner/sale-banner-3.jpg" width=100%>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#sale-banner" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#sale-banner" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
<div class="row" style="background-color:white;">
    <div class="col-lg-12">
        <h1 class="page-header">Ẩm thực
            <small>3 miền</small>
        </h1>
    </div>
    <div class="row">
        <?php if(isset($this->souths->message)): ?>
            <?php foreach($this->souths->message as $row): ?>
                <div class="col-lg-3">
                    <div class="product-item-box">
                        <div class="product-item">
                            <div class="product-detail">
                                <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                                <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                                <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div>Hiện tại chưa có sản phẩm nào. Quý khách vui lòng quay lại sau !</div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if(isset($this->norths->message)): ?>
            <?php foreach($this->norths->message as $row): ?>
                <div class="col-lg-3">
                    <div class="product-item-box">
                        <div class="product-item">
                            <div class="product-detail">
                                <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                                <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                                <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div>Hiện tại chưa có sản phẩm nào. Quý khách vui lòng quay lại sau !</div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if(isset($this->centrals->message)): ?>
            <?php foreach($this->centrals->message as $row): ?>
                <div class="col-lg-3">
                    <div class="product-item-box">
                        <div class="product-item">
                            <div class="product-detail">
                                <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                                <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                                <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div>Hiện tại chưa có sản phẩm nào. Quý khách vui lòng quay lại sau !</div>
        <?php endif; ?>
    </div>
</div>
<?php }}