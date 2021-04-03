<?php
namespace vms;
use api\v1\UserAPI;
use models\BookingModel;

class BookingPage
{
    public function __construct($params = null){session_start();}
    public function render() {
        if(isset($_POST['submit'])){
            $booking = new BookingModel($_POST);
            UserAPI::bookingTable($booking);
        }
    } 
}