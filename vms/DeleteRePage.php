<?php
namespace vms;
use api\v1\UserAPI;

class DeleteRePage
{
    public $reId;
    public function __construct($params = null)
    {
        $this->reId = $params[0];
    }
    public function render() {
        UserAPI::deleteReById($this->reId);
    } 
}