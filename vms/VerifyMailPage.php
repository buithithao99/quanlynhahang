<?php
namespace vms;
use api\v1\UserAPI;

class VerifyMailPage
{
    public $email;
    public function __construct($params = null)
    {
        $this->email = $params[0];
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        UserAPI::updateAuthByEmail($this->email);
    }
}