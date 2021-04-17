<?php
namespace models;

class ContactModel {

    public $name;
    public $address;
    public $email;
    public $phone;
    public $title;
    public $content;

    public function __construct($contact) {
        $this->name = $contact['name'];
        $this->address = $contact['address'];
        $this->email = $contact['email'];
        $this->phone = $contact['phone'];
        $this->title = $contact['title'];
        $this->content = $contact['content'];
    }
}