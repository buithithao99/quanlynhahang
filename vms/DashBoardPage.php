<?php
namespace vms;
use vms\templates\AdminTemplate;

class DashBoardPage {
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['user_id'])){
            if($_SESSION['type']!=="admin"){
                header("Location: /northproduct");
            }else{
                header("Location: /");
            }
        }
        $this->title  = "Bảng điều khiển";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new AdminTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row" style="margin-top:2rem;">
    <div class="col-lg-4">
        <div class="form-group">
            <select id="revenue" class="form-control">
                <option value="day" selected>Doanh thu theo ngày</option>
                <option value="month">Doanh thu theo tháng</option>
                <option value="year">Doanh thu theo năm</option>
                <option value="custom">Tùy chọn</option>
            </select>
        </div>
    </div>
</div>
<div id="chart_day"></div>
<div id="chart_month"></div>
<div id="chart_year"></div>
<div id="form-filter">
    <div class="form-group row">
        <div class="col-xs-3">
            <label for="from_date">Ngày bắt đầu</label>
            <input type="date" name="from_date" id="from_date" class="form-control" required/>
        </div>
        <div class="col-xs-3">
            <label for="to_date">Ngày kết thúc</label>
            <input type="date" name="to_date" id="to_date" class="form-control" required/>
        </div>
    </div>
    <input type="button" class="btn btn-primary" name="filter" id="filter" value="Lọc" />
</div>
<div id="chart_custom"></div>
<?php }}    