<?php
namespace vms;
use api\v1\UserAPI;

class ReturnPage
{
    public function __construct($params = null)
    {
        UserAPI::undoStatusTable($params[0]);
    }
}