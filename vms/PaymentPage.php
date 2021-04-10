<?php

namespace vms;

use \libs\StripePayment;
use api\v1\UserAPI;
use vms\templates\AdminTemplate;
class PaymentPage
{

    private $dataCart;
    public $successMessage;
    public $rows;
    public function __construct($params = null)
    {
        session_start();
        $this->title = "Thanh toán điện tử";
        $this->rows = UserAPI::getUserById($_SESSION['user_id']);
    }

    public function render()
    {
        $template = new AdminTemplate();
        if (isset($_POST["data"])) {
            $this->dataCart = $_POST["data"];
            $_SESSION['dataCart'] = $this->dataCart;
        }

        if (!empty($_POST["token"])) {
            $stripePayment = new StripePayment();

            $amount = 0;
            foreach (unserialize($_SESSION['dataCart']) as $key => $value) {
                $amount += $value["item_price"] * $value["item_qty"];
            }
            
            $_POST["amount"] = $amount;

            $stripeResponse = $stripePayment->chargeAmountFromCard($_POST);

            $amount = $stripeResponse["amount"];

            $param_value_array = array(
                $_POST['email'],
                $amount,
                $stripeResponse["currency"],
                $stripeResponse["balance_transaction"],
                $stripeResponse["status"],
                json_encode($stripeResponse)
            );

            $result = UserAPI::payment($param_value_array);
            if ($result->status && $stripeResponse['amount_refunded'] == 0 && empty($stripeResponse['failure_code']) && $stripeResponse['paid'] == 1 && $stripeResponse['captured'] == 1 && $stripeResponse['status'] == 'succeeded') {
                $this->successMessage = "completed successfully";
                UserAPI::checkout(unserialize($_POST['data']));
            }
        }
        $template->renderChild($this);
    }
    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
?>
<h2>Thanh toán điện tử</h2>
<?php if(!empty($this->successMessage)): ?>
    <div id="success-message"><?= $this->successMessage ?></div>
<?php endif; ?>
<div id="error-message"></div>
<?php foreach($this->rows->message as $row): ?>
    <form id="frmStripePayment" action="/payment" method="post">
        <div class="form-group">
            <label for="name">Tên sở hữu</label> <span id="card-holder-name-info" class="info"></span>
            <input type="text" id="name" name="name" class="form-control" value="<?= $row['firstname'].' '.$row['lastname'] ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">Email</label> <span id="email-info" class="info"></span>
            <input type="email" id="email" name="email" class="form-control" value="<?= $row['email'] ?>" disabled>
        </div>
        <div class="form-group">
            <label for="card-number">Số tài khoản</label> <span id="card-number-info" class="info"></span>
            <input type="text" id="card-number" name="card-number" class="form-control">
        </div>
        <div class="form-group">
            <label>Hạn mức (Tháng / Năm)</label> <span id="userEmail-info" class="info"></span>
            <div class="row">
                <div class="col-xs-6">
                    <select name="month" id="month" class="form-control">
                        <?php for($i = 1;$i<=12;$i++): ?>
                            <option value="<?= $i ?>">Tháng <?= $i ?></option>
                        <?php endfor; ?>
                    </select> 
                </div>
                <div class="col-xs-6">
                    <select name="year" id="year" class="form-control">
                        <?php for($i = 2021;$i<=2030;$i++): ?>
                            <option value="<?= $i ?>">Năm <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="cvc">CVC</label> <span id="cvv-info" class="info"></span>
            <input type="text" name="cvc" id="cvc" class="form-control">
        </div>
        <div>
            <input type="submit" name="pay_now" value="Thanh toán" id="submit-btn" class="btn btn-primary" onClick="stripePay(event);">
        </div>
        <input type='hidden' name='currency_code' value='VND'> 
    </form>
<?php endforeach; ?>
<?php
    }
}
