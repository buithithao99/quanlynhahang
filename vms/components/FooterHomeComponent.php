<?php
namespace vms\components;

class FooterHomeComponent {
    public function __construct($params = null) {
    }

    public function render() {
?>
<div class="footer">
    <div class="row">
        <div class="col-lg-6">
            <h3>Thông tin liên hệ</h3>
            <p>Họ tên: Bùi Thị Thảo</p>
            <p>Email: thaobui99dtmu@gmail.com</p>
            <p>Số điện thoại: 0963722443</p>
            <p>Email nhà hàng: lannguyentdmu@gmail.com </p>
        </div>
        <div class="col-lg-6">
            <h3>Mạng xã hội</h3>
            <div class="contact">
                <div class="facebook"><a href="https://www.facebook.com/thaobd2499" target="__blank"><i class="fab fa-facebook-square"></i></a></div>
            </div>
        </div>
    </div>
</div>
<?php }}
