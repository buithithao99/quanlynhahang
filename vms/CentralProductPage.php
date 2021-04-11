<?php
namespace vms;
use vms\templates\AdminTemplate;
use api\v1\UserAPI;

class CentralProductPage {

    public $categories;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
        }
        $this->title  = "Ẩm thực miền Trung";
        $this->categories = UserAPI::getAllCategory();
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
        <h1 class="page-header">Ẩm thực
            <small>Miền Trung</small>
        </h1>
    </div>
    <div class="col-lg-12">
        <div id="central-banner" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#central-banner" data-slide-to="0" class="active"></li>
                <li data-target="#central-banner" data-slide-to="1"></li>
                <li data-target="#central-banner" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="/assets/img/banner/central-banner-1.jpg" width=100%>
                </div>

                <div class="item">
                    <img src="/assets/img/banner/central-banner-2.jpg" width=100%>
                </div>

                <div class="item">
                    <img src="/assets/img/banner/central-banner-3.jpg" width=100%>
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#central-banner" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#central-banner" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<ul class="nav nav-tabs" style="margin-top:2rem;">
    <?php foreach($this->categories->message as $row): ?>
        <li><a data-toggle="tab" href="#<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
    <?php endforeach; ?>
</ul>
<div class="row tab-content">
    <?php foreach($this->categories->message as $category): ?>
       <?php $rows = UserAPI::getProductCentralById($category['id']); ?>
       <?php  foreach($rows->message as $row): ?>
        <div class="col-lg-3" id="<?= $category['id'] ?>">
            <form action="/addtocart" method="POST">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />
                <input type="hidden" name="image" value="<?= $row['image'] ?>" />
                <input type="hidden" name="id" value="<?= $row['id'] ?>" />
                <input type="hidden" name="name" value="<?= $row['name'] ?>" />
                <input type="hidden" name="price" value="<?= $row['price'] ?>" />
                <input type="hidden" name="region_id" value="<?= $row['region_id'] ?>" />
                <input type="hidden" name="region_name" value="<?= $row['region_name'] ?>" />
                <input type="hidden" name="description" value="<?= $row['description'] ?>" />
                <div class="product-item-box">
                    <div class="product-item">
                        <div class="product-detail">
                            <img src="/images/product/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image" width="100%" height="70%"/>
                            <div class="product-name" name="product-name"><?= $row['name'] ?></div>
                            <div class="price-new" name="price-new"><?= number_format($row['price'], 0, '', ',') ?>₫</div>
                            <?php if($_SESSION['type'] === 'customer'): ?>
                                <input type="number" name="qty" min=1 class="qty" class="qty" required/>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($_SESSION['type'] === 'customer'): ?>
                        <div class="col text-center">
                            <button name="submit" type="submit"><i
                                    class="fas fa-shopping-cart mr-2"></i>Mua</button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php }}