<?php
namespace models;

class RegionModel {
    public $name;
    public function __construct($region) {
        $this->name = $region["name"];
    }
}