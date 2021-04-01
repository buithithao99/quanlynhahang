<?php
namespace models;

class CategoryModel {
    public $name;
    public $active;
    public $id;
    public function __construct($category) {
        $this->name = $category["name"];
        $this->active = $category["active"];
        $this->id = $category["id"];
    }
}