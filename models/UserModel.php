<?php
namespace models;

class UserModel {
    public $email;
    public $firstname;
    public $lastname;
    public $phone;
    public $gender;
    public $password;
    public $city;
    public $district;
    public $commune;
    public $image;
    public $type;
    public $id;
    
    public function __construct($user,$file) {
        $this->firstname = $user["firstname"];
        $this->lastname = $user["lastname"];
        $this->email = $user["email"];
        $this->password = $user["password"];
        $this->gender = $user['gender'];
        $this->phone = $user['phone'];
        $this->city = $user['city'];
        $this->district = $user['district'];
        $this->commune = $user['commune'];
        $this->type = $user['type'];
        $this->image = $file['image'];
        if(isset($user['id'])){
            $this->id = $user['id'];
        }
    }
}