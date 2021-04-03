<?php
namespace vms;
use api\v1\UserAPI;

class EnableUserPage
{
    public $userId;
    public function __construct($params = null)
    {
        $this->userId = $params[0];
    }
    public function render() {
        UserAPI::enableUserById($this->userId);
    } 
}