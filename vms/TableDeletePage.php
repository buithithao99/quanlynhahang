<?php
namespace vms;
use api\v1\UserAPI;

class TableDeletePage
{
    public $tableId;
    public function __construct($params = null)
    {
        $this->tableId = $params[0];
    }
    public function render() {
        UserAPI::deleteTableById($this->tableId);
    } 
}