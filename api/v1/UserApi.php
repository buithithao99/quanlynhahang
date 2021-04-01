<?php
namespace api\v1;

use libs\Mysqllib;
use db\Database;
use models\ResponseModel;
class UserAPI
{
    public static function checkExistEmail($email){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("SELECT * FROM users WHERE email='%s'", $conn->real_escape_string($email));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        if (count($res->message) === 1) {
            return new ResponseModel(false);
        }
        return new ResponseModel(true);
    }

    public static function save($user){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        // Validate password
        $uppercase = preg_match('@[A-Z]@', $user->password);
        $lowercase = preg_match('@[a-z]@', $user->password);
        $number    = preg_match('@[0-9]@', $user->password);

        if(!$uppercase || !$lowercase || !$number || strlen($user->password) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($user->password, PASSWORD_DEFAULT);
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $type = $conn->real_escape_string($user->type);
        $email = $conn->real_escape_string($user->email);
        $gender = $conn->real_escape_string($user->gender);
        $phone = $conn->real_escape_string($user->phone);
        $city = $conn->real_escape_string($user->city);
        $district = $conn->real_escape_string($user->district);
        $commune = $conn->real_escape_string($user->commune);
        $active = "enabled";
        $status = "unverify";
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)){
                        $user_id = rand(time(), 100000000);
                        // Query
                        $insert_query = sprintf("INSERT INTO `users`(`firstname`, `lastname`, `type`, `email`, `password`, `gender`, `city`, `district`, `commune`, `active`, `phone`, `status`, `img`, `user_id`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                            $firstname,
                            $lastname,
                            $type,
                            $email,
                            $password_hash,
                            $gender,
                            $city,
                            $district,
                            $commune,
                            $active,
                            $phone,
                            $status,
                            $new_img_name,
                            $user_id
                         );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                        if($res->status){
                            $mail = new \mail\PHPMailer();
                            $mail->isSMTP();
                            $mail->Mailer = "smtp";
                            $mail->SMTPDebug  = 1;  
                            $mail->SMTPAuth   = TRUE;
                            $mail->SMTPSecure = "STARTTLS";
                            $mail->Port       = 587;
                            $mail->Host       = "smtp.gmail.com";
                            $mail->Username   = "restaurantsystem99@gmail.com";
                            $mail->Password   = "Buithithao99";
                            $mail ->CharSet = "UTF-8"; 
                            $mail->isHTML(true);
                            $mail->addAddress($email);
                            $mail->setFrom("restaurantsystem99@gmail.com","Hệ thống quản lý nhà hàng");
                            $mail->Subject = "Verify email";
                            $content = '<html>
                                <body>
                                    <center>
                                        <p>
                                        <a href="http://localhost/verify/' . $email . '" 
                                        style="background-color:#ffbe00; color:#000000; display:inline-block; padding:12px 40px 12px 40px; text-align:center; text-decoration:none;" 
                                        target="_blank">Veirfy email</a>
                                        </p>
                                    </center>
                                </body>
                            </html>';
                            $mail->MsgHTML($content);
                            $mail->send();
                            header("Location: /");
                        }else{
                            return "Invalid type";
                        }
                    }
                }else{
                    return "Invalid extension";
                }
            }
        }   
        return $res;
    }

    public static function getUserByEmail($email){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM users WHERE email='%s'", $conn->real_escape_string($email));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateAuthByEmail($email){
        session_start();
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $isVerify = "verify";
        $query = sprintf("UPDATE users SET status='%s' WHERE email='%s'",$isVerify,$email);
        Mysqllib::mysql_get_data_from_query($conn, $query);
        $data = UserAPI::getUserByEmail($email);
        $_SESSION['user_id'] = $data->message[0]['user_id'];
        header("Location: /dashboard");
    }

    public static function login($email,$password,$data){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        if($data->message[0]['status'] === 'verify'){
            $query = sprintf("SELECT `password`, `type`, `user_id` FROM users WHERE `email`='%s'", $conn->real_escape_string($email));
            $res = Mysqllib::mysql_get_data_from_query($conn, $query);
            if(isset($res->message[0]["password"])){
                if (password_verify($conn->real_escape_string($password), $res->message[0]["password"])) {
                    session_start();
                    $_SESSION['type'] = $res->message[0]['type'];
                    $_SESSION['user_id'] = $res->message[0]['user_id'];
                    return new ResponseModel(true);
                }
            }
        }
        return new ResponseModel(false);
    }

    public static function getAllCity(){
       // Connect db
       $conn_resp = Database::connect_db();
       if(!$conn_resp->status) {
           return $conn_resp;
       }
       $conn = $conn_resp->message;

       $query = sprintf("SELECT * FROM devvn_tinhthanhpho");
       $res = Mysqllib::mysql_get_data_from_query($conn, $query);
       return $res;
    }

    public static function getDistrictById($cityId){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM `devvn_quanhuyen` WHERE `matp`='%s'", $conn->real_escape_string($cityId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getCommuneById($districtId){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `devvn_xaphuongthitran` WHERE `maqh`='%s'", $conn->real_escape_string($districtId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updatePasswordById($id,$newPassword){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $uppercase = preg_match('@[A-Z]@', $newPassword);
        $lowercase = preg_match('@[a-z]@', $newPassword);
        $number    = preg_match('@[0-9]@', $newPassword);

        if(!$uppercase || !$lowercase || !$number || strlen($newPassword) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $conn = $conn_resp->message;
        $query = sprintf("UPDATE users SET password='%s' WHERE id='%s'",$password_hash,$id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getUserById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT users.*,devvn_quanhuyen.name name_district,devvn_tinhthanhpho.name name_city ,devvn_xaphuongthitran.name name_commune 
        FROM users,devvn_quanhuyen,devvn_tinhthanhpho,devvn_xaphuongthitran 
        WHERE user_id='%s' 
        AND users.city = devvn_tinhthanhpho.matp 
        AND users.district = devvn_quanhuyen.maqh 
        AND users.commune = devvn_xaphuongthitran.xaid", 
        $conn->real_escape_string($id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function update($user,$id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $gender = $conn->real_escape_string($user->gender);
        $phone = $conn->real_escape_string($user->phone);
        $city = $conn->real_escape_string($user->city);
        $district = $conn->real_escape_string($user->district);
        $commune = $conn->real_escape_string($user->commune);

        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if(isset($user->image)){
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if(in_array($img_ext, $extensions) === true){
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)){
                        // Query
                        $update_query = sprintf("UPDATE `users` SET `firstname`='%s',`lastname`='%s',`phone`='%s',`city`='%s',`district`='%s',`gender`='%s',`commune`='%s',`img`='%s' WHERE `user_id` = '%s'", 
                            $firstname,
                            $lastname,
                            $phone,
                            $city,
                            $district,
                            $gender,
                            $commune,
                            $new_img_name,
                            $id
                        );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $update_query);
                        if($res->status){
                            header("Location: /profile");
                        }
                    }
                }else{
                    return "Invalid type";
                }
            }else{
                return "Invalid extension";
            }
        }
        return $res;
    }

    public static function addCategory($category){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("INSERT INTO category (`name`,`active`) VALUES ('%s','%s')",$conn->real_escape_string($category->name),$conn->real_escape_string($category->active));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /category");
    }

    public static function getAllCategory(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM category");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteCateById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM category WHERE `id` = '%s'",$conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /category");
    }

    public static function getCategoryById($cateId){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `category` WHERE `id`='%s'", $conn->real_escape_string($cateId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateCateById($category){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "UPDATE category 
            SET `name`='%s',`active`='%s'
            WHERE id='%s'",
            $conn->real_escape_string($category->name),
            $conn->real_escape_string($category->active),
            $conn->real_escape_string($category->id)
        );
        Mysqllib::mysql_get_data_from_query($conn, $query);
        header("Location: /category");
    }

    public static function addProduct($product){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $category_id = $conn->real_escape_string($product->category_id);
        $name =  $conn->real_escape_string($product->name);
        $price = $conn->real_escape_string($product->price);
        $description =  $conn->real_escape_string($product->description);
        $active = $conn->real_escape_string($product->active);
        $region = $conn->real_escape_string($product->region);
        $quantity = $conn->real_escape_string($product->quantity);
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if (isset($product->image)) {
            $fileImg = $product->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);
            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/product/".$new_img_name)){
                        // Query
                        $insert_query = sprintf("INSERT INTO products (`category_id`,`name`,`price`,`description`,`image`,`active`,`quantity`,`region`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",
                            $category_id,
                            $name,
                            $price,
                            $description,
                            $new_img_name,
                            $active,
                            $quantity,
                            $region
                         );
                        Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                    }
                }else{
                    return "Invalid extension";
                }
            }
        }
        header("Location: /product");
    }

    public static function getAllProduct(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,c.name cate_name FROM products p,category c WHERE p.category_id = c.id");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteProductById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM products WHERE `id` = '%s'",$conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /product");
    }

    public static function getProductById($productId){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `id`='%s'", $conn->real_escape_string($productId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateProductById($product){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $category_id = $conn->real_escape_string($product->category_id);
        $name =  $conn->real_escape_string($product->name);
        $price = $conn->real_escape_string($product->price);
        $description =  $conn->real_escape_string($product->description);
        $active = $conn->real_escape_string($product->active);
        $region = $conn->real_escape_string($product->region);
        $quantity = $conn->real_escape_string($product->quantity);
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if (isset($product->image)) {
            $fileImg = $product->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);
            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/product/".$new_img_name)){
                        // Query
                        $update_query = sprintf(
                            "UPDATE products 
                            SET `category_id`='%s',`name`='%s',`price`='%s',`description`='%s',`active`='%s',`region`='%s',`image`='%s',`quantity`='%s'
                            WHERE id='%s'",
                            $category_id,
                            $name,
                            $price,
                            $description,
                            $active,
                            $region,
                            $new_img_name,
                            $quantity,
                            $conn->real_escape_string($product->id)
                        );
                        Mysqllib::mysql_post_data_from_query($conn, $update_query);
                    }
                }else{
                    return "Invalid extension";
                }
            }
        }
        header("Location: /product");
    }

    public static function getAllUser($user_id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT users.*,devvn_quanhuyen.name name_district,devvn_tinhthanhpho.name name_city ,devvn_xaphuongthitran.name name_commune 
        FROM users,devvn_quanhuyen,devvn_tinhthanhpho,devvn_xaphuongthitran 
        WHERE NOT user_id='%s' 
        AND users.city = devvn_tinhthanhpho.matp 
        AND users.district = devvn_quanhuyen.maqh 
        AND users.commune = devvn_xaphuongthitran.xaid", 
        $conn->real_escape_string($user_id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function saveUserByAdmin($user){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        // Validate password
        $uppercase = preg_match('@[A-Z]@', $user->password);
        $lowercase = preg_match('@[a-z]@', $user->password);
        $number    = preg_match('@[0-9]@', $user->password);

        if(!$uppercase || !$lowercase || !$number || strlen($user->password) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($user->password, PASSWORD_DEFAULT);
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $type = $conn->real_escape_string($user->type);
        $email = $conn->real_escape_string($user->email);
        $gender = $conn->real_escape_string($user->gender);
        $phone = $conn->real_escape_string($user->phone);
        $city = $conn->real_escape_string($user->city);
        $district = $conn->real_escape_string($user->district);
        $commune = $conn->real_escape_string($user->commune);
        $active = "enabled";
        $status = "unverify";
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)){
                        $user_id = rand(time(), 100000000);
                        // Query
                        $insert_query = sprintf("INSERT INTO `users`(`firstname`, `lastname`, `type`, `email`, `password`, `gender`, `city`, `district`, `commune`, `active`, `phone`, `status`, `img`, `user_id`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                            $firstname,
                            $lastname,
                            $type,
                            $email,
                            $password_hash,
                            $gender,
                            $city,
                            $district,
                            $commune,
                            $active,
                            $phone,
                            $status,
                            $new_img_name,
                            $user_id
                         );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                        if($res->status){
                            $mail = new \mail\PHPMailer();
                            $mail->isSMTP();
                            $mail->Mailer = "smtp";
                            $mail->SMTPDebug  = 1;  
                            $mail->SMTPAuth   = TRUE;
                            $mail->SMTPSecure = "STARTTLS";
                            $mail->Port       = 587;
                            $mail->Host       = "smtp.gmail.com";
                            $mail->Username   = "restaurantsystem99@gmail.com";
                            $mail->Password   = "Buithithao99";
                            $mail ->CharSet = "UTF-8"; 
                            $mail->isHTML(true);
                            $mail->addAddress($email);
                            $mail->setFrom("restaurantsystem99@gmail.com","Hệ thống quản lý nhà hàng");
                            $mail->Subject = "Verify email";
                            $content = '<html>
                                <body>
                                    <center>
                                        <p>
                                        <a href="http://localhost/verify/' . $email . '" 
                                        style="background-color:#ffbe00; color:#000000; display:inline-block; padding:12px 40px 12px 40px; text-align:center; text-decoration:none;" 
                                        target="_blank">Veirfy email</a>
                                        </p>
                                    </center>
                                </body>
                            </html>';
                            $mail->MsgHTML($content);
                            $mail->send();
                            header("Location: /users");
                        }else{
                            return "Invalid type";
                        }
                    }
                }else{
                    return "Invalid extension";
                }
            }
        }   
        return $res;
    }

    public static function getProductNorth(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `region`='north' AND (`category_id`= 1 OR `category_id`= 2)");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductSouth(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `region`='south' AND (`category_id`= 1 OR `category_id`= 2)");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductCentral(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `region`='central' AND (`category_id`= 1 OR `category_id`= 2)");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getVipTable(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `category_id` = 4");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getSimpleTable(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `products` WHERE `category_id` = 3");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function checkout($order){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("INSERT INTO orders (`product`,`product_qty`,`total`,`status`) VALUES ('%s','%s','%s','%s')",$conn->real_escape_string($order->product),$conn->real_escape_string($order->product_qty),$conn->real_escape_string($order->total),"complete");
        Mysqllib::mysql_post_data_from_query($conn, $query);
        unset($_SESSION["shopping_cart"]);
        header("Location: /previousorder");
    }

    public static function getAllOrder(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `orders`");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }
}