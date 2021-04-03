<?php
namespace models;

class TableModel {
    public $type;
    public $active;
    public $id;
    public function __construct($table) {
        $this->type = $table["type"];
        $this->active = $table["active"];
        $this->id = $table["id"];
    }
}