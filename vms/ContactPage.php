<?php
namespace vms;
use vms\templates\HomeTemplate;
use api\v1\UserAPI;
use models\ContactModel;

class ContactPage {

    public function __construct($params = null) {
        $this->title  = "Liên hệ";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new HomeTemplate();
        if(isset($_POST['submit'])){
            $contact = new ContactModel($_POST);
            UserAPI::saveContact($contact);
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row mt-4" style="margin-bottom:3rem;">
    <div class="col-lg-9">
        <small><a href="/homepage" class="text-dark">Trang chủ</a> <i class="fas fa-angle-double-right"></i> <span
                class="introduce">Liên hệ</span></small>
        <div class="heading-lg mt-3">
            <h1>THÔNG TIN LIÊN HỆ</h1>
        </div>
        <div class="row mt-4">
            <div class="col-lg-9">
                <h5>Chủ nhà hàng: Bùi Thị Thảo</h5>
                <p><i class="fas fa-map-marker-alt"></i> Địa chỉ: Đại học Thủ Dầu Một </p>
                <p><i class="fas fa-phone-alt"></i> Điện thoại: 0963722443</p>
                <p><i class="fas fa-envelope-open"></i> Email: thaobui99dtmu@gmail.com</p>
                <p><i class="fas fa-envelope-open"></i> Email nhà hàng: lannguyentdmu@gmail.com</p>
            </div>
        </div>
        <hr />
        <div class="col-lg-12">
            <h6 class="mt-4"><span>GỬI THÔNG TIN LIÊN HỆ</span></h6>
            <small class="d-block mb-3">
                <p>Xin vui lòng điền các yêu cầu vào mẫu dưới đây và gửi cho chúng tôi. Chúng tôi sẽ trả
                    lời bạn ngay sau khi nhận được. Xin chân thành cảm ơn!</p>
            </small>
        </div>
        <div class="col-md-12" style="margin-top:2rem;">
            <form method="POST" action="/contact">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="name" type="text" class="form-control" name="name" placeholder="Họ tên" required>
                </div>
                <div class="input-group" style="margin-top:1rem;">
                    <span class="input-group-addon"><i class="fa fa-address-book" aria-hidden="true"></i></span>
                    <input id="address" type="text" class="form-control" name="address" placeholder="Địa chỉ" required>
                </div>
                <div class="input-group" style="margin-top:1rem;">
                    <span class="input-group-addon"><i class="fa fa-envelope-open" aria-hidden="true"></i></span>
                    <input id="email" type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="input-group" style="margin-top:1rem;">
                    <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                    <input id="phone" type="tel" class="form-control" name="phone" placeholder="Điện thoại" required>
                </div>
                <div class="input-group" style="margin-top:1rem;">
                    <span class="input-group-addon"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                    <input id="title" type="text" class="form-control" name="title" placeholder="Tiêu đề" required>
                </div>
                <div class="form-group" style="margin-top:1rem;">
                    <textarea class="form-control" rows="5" id="content" name="content" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="submit" style="margin-bottom:1rem;">Gửi</button>
            </form>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.771910464706!2d106.67239731407062!3d10.9805814921832!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d12739baa5ed%3A0xf500a5c3425a73a3!2zxJDhuqFpIGjhu41jIFRo4bunIEThuqd1IE3hu5l0!5e0!3m2!1sen!2s!4v1618555444272!5m2!1sen!2s" width="700" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <div class="col-lg-3">
        <h5 class="mt-4"><span>HỖ TRỢ TRỰC TUYẾN</span></h5>
        <p>Hotline: 0963722443</p>
    </div>
</div>
<?php }}