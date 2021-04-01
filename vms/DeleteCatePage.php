<?php
namespace vms;
use api\v1\UserAPI;

class DeleteCatePage
{
    public $cateId;
    public function __construct($params = null)
    {
        $this->cateId = $params[0];
    }
    public function render() {
        UserAPI::deleteCateById($this->cateId);
    } 
}