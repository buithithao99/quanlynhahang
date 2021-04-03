<?php
namespace models;

class BookingModel {
    public $user_id;
    public $table_id;
    public function __construct($booking) {
        $this->user_id = $booking["user_id"];
        $this->table_id = $booking["table_id"];
    }
}