<?php
namespace vms;
use api\v1\UserAPI;

class TableEnablePage
{
    public $tableId;
    public function __construct($params = null)
    {
        $this->tableId = $params[0];
    }
    public function render() {
        UserAPI::enableTableById($this->tableId);
    } 
}