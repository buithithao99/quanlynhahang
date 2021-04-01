<?php
namespace vms;
use vms\templates\AdminTemplate;

class CartPage {

    public $total;
    public $qty;
    public function __construct($params = null) {
        session_start();
        if($_SESSION["type"] !== "customer"){
            if($_SESSION["type"] === "admin"){
                header("Location: /dashboard");
            }elseif($_SESSION["type"] === "serve"){
                header("Location: /northproduct");
            }else{
                header("Location: /order");
            }
        }
        $this->title  = "Giỏ hàng";
        $this->total = 0;
        $this->qty = 0;
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
        <h1 class="page-header">Giỏ hàng</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
    <form action="/checkout" method="POST" enctype="multipart/form-data">
    <div class="col-lg-12"> 
        <table id="cart" class="table table-hover table-condensed"> 
            <thead> 
                <tr> 
                    <th style="width:40%">Tên sản phẩm</th> 
                    <th style="width:10%">Giá</th> 
                    <th style="width:8%">Số lượng</th> 
                    <th style="width:22%" class="text-center">Thành tiền</th> 
                    <th style="width:10%"></th> 
                </tr> 
            </thead> 
            <tbody>
                <?php if(!empty($_SESSION["shopping_cart"])): ?>
                    <?php foreach($_SESSION["shopping_cart"] as $keys => $values): ?>
                        <tr> 
                            <td data-th="Product"> 
                                <div class="row"> 
                                    <div class="col-sm-2 hidden-xs"><img src="/images/product/<?= $values['item_image'] ?>" alt="<?= $values['item_name'] ?>" class="img-responsive" width="100"></div> 
                                    <div class="col-sm-10"> 
                                        <h4 class="nomargin"><?= $values['item_name'] ?></h4> 
                                        <p><?= $values['item_description'] ?></p> 
                                    </div> 
                                </div> 
                            </td> 
                            <td data-th="Price"><?= number_format($values['item_qty'] * $values['item_price'], 0, '', ',') ?>₫</td> 
                            <td data-th="Quantity"><input class="form-control text-center" value="<?= $values['item_qty'] ?>" type="number" disabled></td> 
                            <td data-th="Subtotal" class="text-center"><?= number_format($values['item_qty'] * $values['item_price'], 0, '', ',') ?>₫</td> 
                            <td class="actions" data-th="">
                                <a href="/deletecart/<?= $values['item_id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                </a>
                            </td>
                            <?php $this->qty+= $values['item_qty'] ?>
                            <?php $this->total += $values['item_price']*$values['item_qty']; ?>
                            <input type="hidden" name="product" value='<?= serialize($values) ?>'    />
                    <?php endforeach; ?>
                            <input type="hidden" name="product_qty" value="<?= $this->qty ?>" />
                            <input type="hidden" name="total" value="<?= $this->total ?>" />
                        </tr>  
                    <?php endif; ?>
                        <tr>
                            <td><a href="/northproduct" class="btn btn-warning"><i class="fa fa-angle-left"></i> Tiếp tục mua hàng</a></td> 
                            <td colspan="2" class="hidden-xs"></td> 
                            <td class="hidden-xs text-center"><strong>Tổng tiền <?= number_format( $this->total, 0, '', ',') ?></strong></td> 
                            <td><button type="submit" name="submit" class="btn btn-success btn-block">Thanh toán <i class="fa fa-angle-right"></i></button></td> 
                        </tr>
            </tbody> 
        </table>
    </div>
    </form>
<?php }}