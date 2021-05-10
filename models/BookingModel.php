<?php
namespace models;

class BookingModel {
    public $user_id;
    public $table_id;
    public $type;
    public $tem_id;
    public function __construct($booking) {
        $this->user_id = $booking["user_id"];
        $this->table_id = $booking["table_id"];
        $this->type = $booking["type"];
        $this->tem_id = $booking["tem_id"];
    }
}