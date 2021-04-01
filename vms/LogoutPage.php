<?php
namespace vms;

class LogoutPage
{
    public function __construct($params = null)
    {
        session_start();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($_SESSION['user_id'])){
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['logout'] = "<div class='success-text'>Đăng xuất thành công <span class='close'>&times;</span></div>";
            header("Location: /");
        }else{  
            header("Location: /");
        }
    }
}