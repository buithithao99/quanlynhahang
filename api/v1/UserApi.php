<?php
namespace api\v1;

use libs\Mysqllib;
use db\Database;
use models\ResponseModel;
class UserAPI
{
    public static function checkExistEmail($email)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
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

    public static function save($user)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        // Validate password
        $uppercase = preg_match('@[A-Z]@', $user->password);
        $lowercase = preg_match('@[a-z]@', $user->password);
        $number    = preg_match('@[0-9]@', $user->password);

        if (!$uppercase || !$lowercase || !$number || strlen($user->password) < 8) {
            return "Invalid password";
        }
        $query = sprintf(
            "SELECT * FROM users"
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        $password_hash = password_hash($user->password, PASSWORD_DEFAULT);
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $type = "customer";
        $email = $conn->real_escape_string($user->email);
        $gender = $conn->real_escape_string($user->gender);
        $phone = $conn->real_escape_string($user->phone);
        $city = $conn->real_escape_string($user->city);
        $district = $conn->real_escape_string($user->district);
        $commune = $conn->real_escape_string($user->commune);
        $active = "enabled";
        $status = "unverify";
        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        foreach($res->message as $row){
            if($row['phone'] == $phone){
                return "Same phone";
            }
        }
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)) {
                        // Query
                        $insert_query = sprintf(
                            "INSERT INTO `users`(`firstname`, `lastname`, `type`, `email`, `password`, `gender`, `city`, `district`, `commune`, `active`, `phone`, `status`, `img`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
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
                            $new_img_name
                        );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                        if ($res->status) {
                            $mail = new \mail\PHPMailer();
                            $mail->isSMTP();
                            $mail->Mailer = "smtp";
                            $mail->SMTPDebug  = 1;
                            $mail->SMTPAuth   = true;
                            $mail->SMTPSecure = "STARTTLS";
                            $mail->Port       = 587;
                            $mail->Host       = "smtp.gmail.com";
                            $mail->Username   = "lannguyentdmu@gmail.com";
                            $mail->Password   = "Lan@12345";
                            $mail ->CharSet = "UTF-8";
                            $mail->isHTML(true);
                            $mail->addAddress($email);
                            $mail->setFrom("lannguyentdmu@gmail.com", "Hệ thống quản lý nhà hàng");
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
                        } else {
                            return "Invalid type";
                        }
                    }
                } else {
                    return "Invalid extension";
                }
            }
        }
        return $res;
    }

    public static function getUserByEmail($email)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM users WHERE email='%s'", $conn->real_escape_string($email));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateAuthByEmail($email)
    {
        session_start();
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $isVerify = "verify";
        $query = sprintf("UPDATE users SET status='%s' WHERE email='%s'", $isVerify, $email);
        Mysqllib::mysql_get_data_from_query($conn, $query);
        $data = UserAPI::getUserByEmail($email);
        $_SESSION['user_id'] = $data->message[0]['id'];
        $_SESSION['type'] = $data->message[0]['type'];
        if ($_SESSION['type'] === 'admin') {
            header("Location: /dashboard");
        } elseif ($_SESSION['type'] === 'customer' || $_SESSION['type'] === 'serve') {
            header("Location: /northproduct");
        } elseif ($_SESSION['type'] === 'cashier') {
            header("Location: /order");
        }
    }

    public static function login($email, $password, $data)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        if ($data->message[0]['status'] === 'verify') {
            $query = sprintf("SELECT `password`, `type`, `id` FROM users WHERE `email`='%s' AND `active`='enabled'", $conn->real_escape_string($email));
            $res = Mysqllib::mysql_get_data_from_query($conn, $query);
            if (isset($res->message[0]["password"])) {
                if (password_verify($conn->real_escape_string($password), $res->message[0]["password"])) {
                    session_start();
                    $_SESSION['type'] = $res->message[0]['type'];
                    $_SESSION['user_id'] = $res->message[0]['id'];
                    $_SESSION['temporary_type'] = $res->message[0]['type'];
                    return new ResponseModel(true);
                }
            }
        }
        return new ResponseModel(false);
    }

    public static function getAllCity()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM devvn_tinhthanhpho");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getDistrictById($cityId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM `devvn_quanhuyen` WHERE `matp`='%s'", $conn->real_escape_string($cityId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getCommuneById($districtId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `devvn_xaphuongthitran` WHERE `maqh`='%s'", $conn->real_escape_string($districtId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updatePasswordById($id, $newPassword)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $uppercase = preg_match('@[A-Z]@', $newPassword);
        $lowercase = preg_match('@[a-z]@', $newPassword);
        $number    = preg_match('@[0-9]@', $newPassword);

        if (!$uppercase || !$lowercase || !$number || strlen($newPassword) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $conn = $conn_resp->message;
        $query = sprintf("UPDATE users SET password='%s' WHERE id='%s'", $password_hash, $id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getUserById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf(
            "SELECT users.*,devvn_quanhuyen.name name_district,devvn_tinhthanhpho.name name_city ,devvn_xaphuongthitran.name name_commune 
        FROM users,devvn_quanhuyen,devvn_tinhthanhpho,devvn_xaphuongthitran 
        WHERE id='%s' 
        AND users.city = devvn_tinhthanhpho.matp 
        AND users.district = devvn_quanhuyen.maqh 
        AND users.commune = devvn_xaphuongthitran.xaid",
        $conn->real_escape_string($id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function update($user)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
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
        $id = $conn->real_escape_string($user->id);

        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)) {
                        // Query
                        $update_query = sprintf(
                            "UPDATE `users` SET `firstname`='%s',`lastname`='%s',`phone`='%s',`city`='%s',`district`='%s',`gender`='%s',`commune`='%s',`img`='%s' WHERE `id` = '%s'",
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
                        if ($res->status) {
                            header("Location: /users");
                        }
                    }
                } else {
                    return "Invalid type";
                }
            } else {
                return "Invalid extension";
            }
        }
        return $res;
    }

    public static function addCategory($category)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("INSERT INTO category (`name`,`active`) VALUES ('%s','%s')", $conn->real_escape_string($category->name), $conn->real_escape_string($category->active));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /category");
    }

    public static function getAllCategory()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM category");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteCateById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM category WHERE `id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /category");
    }

    public static function getCategoryById($cateId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `category` WHERE `id`='%s'", $conn->real_escape_string($cateId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateCateById($category)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
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

    public static function addProduct($product)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $category_id = $conn->real_escape_string($product->category_id);
        $name =  $conn->real_escape_string($product->name);
        $price = $conn->real_escape_string($product->price);
        $description =  $conn->real_escape_string($product->description);
        $active = $conn->real_escape_string($product->active);
        $region_id = $conn->real_escape_string($product->region_id);
        $quantity = $conn->real_escape_string($product->quantity);
        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        if (isset($product->image)) {
            $fileImg = $product->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);
            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/product/".$new_img_name)) {
                        // Query
                        $insert_query = sprintf(
                            "INSERT INTO products (`category_id`,`name`,`price`,`description`,`image`,`active`,`quantity`,`region_id`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",
                            $category_id,
                            $name,
                            $price,
                            $description,
                            $new_img_name,
                            $active,
                            $quantity,
                            $region_id
                        );
                        Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                    }
                } else {
                    return "Invalid extension";
                }
            }
        }
        header("Location: /product");
    }

    public static function getAllProduct()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,c.name cate_name,r.name region_name  FROM products p,category c,region r WHERE p.category_id = c.id AND r.id = p.region_id");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteProductById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM products WHERE `id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /product");
    }

    public static function getProductById($productId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.* FROM products p WHERE p.id ='%s'", $conn->real_escape_string($productId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateProductById($product)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $category_id = $conn->real_escape_string($product->category_id);
        $name =  $conn->real_escape_string($product->name);
        $price = $conn->real_escape_string($product->price);
        $description =  $conn->real_escape_string($product->description);
        $active = $conn->real_escape_string($product->active);
        $region = $conn->real_escape_string($product->region_id);
        $quantity = $conn->real_escape_string($product->quantity);
        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        if (isset($product->image)) {
            $fileImg = $product->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);
            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/product/".$new_img_name)) {
                        // Query
                        $update_query = sprintf(
                            "UPDATE products 
                            SET `category_id`='%s',`name`='%s',`price`='%s',`description`='%s',`active`='%s',`region_id`='%s',`image`='%s',`quantity`='%s'
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
                } else {
                    return "Invalid extension";
                }
            }
        }
        header("Location: /product");
    }

    public static function getAllUser($user_id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf(
            "SELECT users.*,devvn_quanhuyen.name name_district,devvn_tinhthanhpho.name name_city ,devvn_xaphuongthitran.name name_commune 
        FROM users,devvn_quanhuyen,devvn_tinhthanhpho,devvn_xaphuongthitran 
        WHERE NOT id='%s' 
        AND users.city = devvn_tinhthanhpho.matp 
        AND users.district = devvn_quanhuyen.maqh 
        AND users.commune = devvn_xaphuongthitran.xaid",
        $conn->real_escape_string($user_id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function saveUserByAdmin($user)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        // Validate password
        $uppercase = preg_match('@[A-Z]@', $user->password);
        $lowercase = preg_match('@[a-z]@', $user->password);
        $number    = preg_match('@[0-9]@', $user->password);

        if (!$uppercase || !$lowercase || !$number || strlen($user->password) < 8) {
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
        $status = "verify";
        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)) {
                        // Query
                        $insert_query = sprintf(
                            "INSERT INTO `users`(`firstname`, `lastname`, `type`, `email`, `password`, `gender`, `city`, `district`, `commune`, `active`, `phone`, `status`, `img`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
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
                            $new_img_name
                        );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                        if ($res->status) {
                            header("Location: /users");
                        } else {
                            return "Invalid type";
                        }
                    }
                } else {
                    return "Invalid extension";
                }
            }
        }
        return $res;
    }

    public static function getProductNorth()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='north'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductSouth()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='south'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductCentral()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='central'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getDoubleTable()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `tables` WHERE `type` = 'double'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getSingleTable()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `tables` WHERE `type` = 'single'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getOtherTable()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `tables` WHERE `type` = 'other'");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function checkout($orders)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $ran_id = rand(time(), 100000000);
        foreach($orders as $row){
            $select_query_1 = sprintf("SELECT `quantity` qty FROM `products` WHERE `id`='%s'",$row['item_id']);
            $res1 = Mysqllib::mysql_get_data_from_query($conn, $select_query_1);
            if($res1->message[0]['qty'] > 0){
                $select_query_2 = sprintf("SELECT `quantity`-'%s' result FROM `products` WHERE `id`='%s'",$row['item_qty'],$row['item_id']);
                $res2 = Mysqllib::mysql_get_data_from_query($conn, $select_query_2);
                if($res2->message[0]['result'] < 0){
                    continue;
                }else{
                    $query = sprintf("INSERT INTO orders (`user_id`,`product_id`,`quantity`,`total`,`status`,`order_id`,`type`) VALUES ('%s','%s','%s','%s','%s','%s','%s')",$row['user_id'],$row['item_id'],$row['item_qty'],$row['item_price']*$row['item_qty'],'handle',$ran_id,0);
                    Mysqllib::mysql_post_data_from_query($conn, $query);
                    $update_query = sprintf("UPDATE `products` SET `quantity`='%s' WHERE id='%s'",
                    $res2->message[0]['result'],
                    $row['item_id']
                    );
                    Mysqllib::mysql_post_data_from_query($conn, $update_query);
                    unset($_SESSION["shopping_cart"]);
                    $_SESSION['checkout-success'] = "<div class='alert alert-success'> Đã đặt hàng thành công  <span class='close'>&times;</span></div>";
                    header("Location: /previousorder");
                }
            }
        }
    }

    public static function checkoutOnline($orders,$data)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $ran_id = rand(time(), 100000000);
        foreach($orders as $row){
            $query = sprintf("INSERT INTO orders (`user_id`,`product_id`,`quantity`,`total`,`status`,`order_id`,`recipient_name`,`address`,`phone`,`type`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$row['user_id'],$row['item_id'],$row['item_qty'],$row['item_price']*$row['item_qty'],'handle',$ran_id,$data['recipient'],$data['address'],$data['phone-recipient'],1);
            Mysqllib::mysql_post_data_from_query($conn, $query);
            $select_query = sprintf("SELECT `quantity`-'%s' result FROM `products` WHERE `id`='%s'",$row['item_qty'],$row['item_id']);
            $res = Mysqllib::mysql_get_data_from_query($conn, $select_query);
            $update_query = sprintf("UPDATE `products` SET `quantity`='%s' WHERE id='%s'",
            $res->message[0]['result'],
            $row['item_id']
            );
            Mysqllib::mysql_post_data_from_query($conn, $update_query);
        }
        unset($_SESSION["shopping_cart"]);
        $select_query = sprintf("SELECT email FROM `users` WHERE `id`='%s'",$orders[0]["user_id"]);
        $res = Mysqllib::mysql_get_data_from_query($conn, $select_query);
        $mail = new \mail\PHPMailer();
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        // $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "STARTTLS";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "lannguyentdmu@gmail.com";
        $mail->Password   = "Lan@12345";
        $mail ->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->addAddress($res->message[0]['email']);
        $mail->setFrom("lannguyentdmu@gmail.com", "Hệ thống quản lý nhà hàng");
        $mail->Subject = "Thư cảm ơn";
        $content = '<html>
            <body>
                <center>
                    <p>
                    Cảm ơn quý khách đã đặt hàng của chúng tôi. Hẹn gặp lại quý khách trong các lần mua hàng tiếp theo
                    </p>
                </center>
            </body>
        </html>';
        $mail->MsgHTML($content);
        $mail->send();
        $_SESSION['checkout-success'] = "<div class='alert alert-success'> Đã đặt hàng thành công  <span class='close'>&times;</span></div>";
        header("Location: /previousorder");
    }

    public static function getAllOrderById($user_id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("SELECT SUM(o.total) total,DATE(o.created_at) order_day,o.order_id,o.status,o.type FROM orders o WHERE `user_id` = '%s' GROUP BY o.order_id ORDER BY order_day DESC",$conn->real_escape_string($user_id));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function addRegion($region)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("INSERT INTO region (`name`) VALUES ('%s')", $conn->real_escape_string($region->name));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /region");
    }

    public static function getAllRegion()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `region`");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteReById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM region WHERE `id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /region");
    }

    public static function getRegionById($reId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `region` WHERE `id`='%s'", $conn->real_escape_string($reId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateReById($region)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "UPDATE region
            SET `name`='%s'
            WHERE id='%s'",
            $conn->real_escape_string($region->name),
            $conn->real_escape_string($region->id)
        );
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /region");
    }

    public static function deleteUserById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM users WHERE `id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /users");
    }

    public static function addTable($table)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("INSERT INTO tables (`type`,`active`) VALUES ('%s','%s')",$conn->real_escape_string($table->type),$conn->real_escape_string($table->active));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /table");
    }

    public static function deleteTableById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM tables WHERE `id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /table");
    }

    public static function getAllTable()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM tables");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getTableById($tableId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM `tables` WHERE `id`='%s'", $conn->real_escape_string($tableId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateTableById($table)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "UPDATE `tables`
            SET `type`='%s',`active`='%s'
            WHERE id='%s'",
            $conn->real_escape_string($table->type),
            $conn->real_escape_string($table->active),
            $conn->real_escape_string($table->id)
        );
        Mysqllib::mysql_get_data_from_query($conn, $query);
        header("Location: /table");
    }

    public static function getProductByCateId($cateId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM `products` WHERE `category_id`='%s' AND active = 'enabled'", $conn->real_escape_string($cateId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getPriceByProductId($proId){
         // Connect db
         $conn_resp = Database::connect_db();
         if (!$conn_resp->status) {
             return $conn_resp;
         }
         $conn = $conn_resp->message;
 
         $query = sprintf("SELECT price FROM `products` WHERE `id`='%s'", $conn->real_escape_string($proId));
         $res = Mysqllib::mysql_get_data_from_query($conn, $query);
         return $res;
    }

    public static function deleteOrderById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("DELETE FROM orders WHERE `order_id` = '%s'", $conn->real_escape_string($id));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /order");
    }

    public static function getTotalByDay(){
         // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT sum(total),DATE(created_at) FROM `orders` WHERE status = 'complete' GROUP BY DATE(created_at)");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function blockUserById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("UPDATE `users`
        SET `active`='disabled'
        WHERE id='%s'",
        $conn->real_escape_string($id)
        );
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /users");
    }

    public static function enableUserById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("UPDATE `users`
        SET `active`='enabled'
        WHERE id='%s'",
        $conn->real_escape_string($id)
        );
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /users");
    }

    public static function bookingTable($booking){
         // Connect db
         $conn_resp = Database::connect_db();
         if (!$conn_resp->status) {
             return $conn_resp;
         }
         $conn = $conn_resp->message;
         $query = sprintf("INSERT INTO booking (`user_id`,`table_id`) VALUE ('%s','%s')",$conn->real_escape_string($booking->user_id),$conn->real_escape_string($booking->table_id));
         Mysqllib::mysql_post_data_from_query($conn, $query);
         $update_query = sprintf(
            "UPDATE `tables`
            SET `active`='disabled'
            WHERE id='%s'",
            $conn->real_escape_string($booking->table_id)
        );
         Mysqllib::mysql_post_data_from_query($conn, $update_query);
         session_start();
         $_SESSION['table_id'] = $booking->table_id;
         $_SESSION['table_type'] = $booking->type;
         header("Location: /northproduct");
    }

    public static function enableTableById($id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("UPDATE `tables`
        SET `active`='enabled'
        WHERE id='%s'",
        $conn->real_escape_string($id)
        );
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /table");
    }

    public static function getStatusFromTable($table_id){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT active FROM `tables` WHERE `id` = $table_id");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getAllOrder()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("SELECT SUM(o.total) total,o.order_id,o.status,u.firstname,u.lastname,o.type,MAX(o.created_at) last_rendered FROM orders o,users u WHERE o.user_id = u.id GROUP BY order_id  ORDER BY last_rendered DESC");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateProfile($user,$id)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
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

        $baseUrl = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'api'));
        if (isset($user->image)) {
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) === true) {
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if (in_array($img_type, $types) === true) {
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if (move_uploaded_file($tmp_name, str_replace('\\', '/', $baseUrl)."/images/user/".$new_img_name)) {
                        // Query
                        $update_query = sprintf(
                            "UPDATE `users` SET `firstname`='%s',`lastname`='%s',`phone`='%s',`city`='%s',`district`='%s',`gender`='%s',`commune`='%s',`img`='%s' WHERE `id` = '%s'",
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
                        if ($res->status) {
                            header("Location: /profile");
                        }
                    }
                } else {
                    return "Invalid type";
                }
            } else {
                return "Invalid extension";
            }
        }
        return $res;
    }

    public static function getOrderById($orderId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT o.*,p.category_id cate_id FROM orders o,products p WHERE o.id= '%s' AND o.product_id=p.id", $conn->real_escape_string($orderId));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getTotalByMonth(){
        // Connect db
       $conn_resp = Database::connect_db();
       if (!$conn_resp->status) {
           return $conn_resp;
       }
       $conn = $conn_resp->message;

       $query = sprintf("SELECT sum(total),MONTH(created_at) FROM `orders` WHERE status = 'complete' GROUP BY MONTH(created_at)");
       $res = Mysqllib::mysql_get_data_from_query($conn, $query);
       return $res;
   }

   public static function getTotalByYear(){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT sum(total),YEAR(created_at) FROM `orders` WHERE status = 'complete' GROUP BY YEAR(created_at)");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function undoStatusTable($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $select_query = sprintf("SELECT table_id FROM `booking` WHERE `user_id`='%s'",$id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $select_query);
        foreach($res->message as $row){
            $update_query = sprintf("UPDATE tables SET active='enabled' WHERE id='%s'",$row['table_id']);
            Mysqllib::mysql_post_data_from_query($conn, $update_query);
        }
        header("Location: /homepage");
    }

    public static function payment($detail){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        
        $insert_query = sprintf("INSERT INTO `payment` (`email`, amount, `currency_code`, `txn_id`, `payment_status`, `payment_response`) values ('%s', '%s', '%s', '%s', '%s', '%s')", $detail[0], $detail[1], $detail[2], $detail[3], $detail[4], $detail[5]);
        $res = Mysqllib::mysql_get_data_from_query($conn, $insert_query);
        if ($res->status) {
            return $res;
        }
    }

    public static function getProductSouthLimit()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='south' LIMIT 4");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductNorthLimit()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='north' LIMIT 4");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductCentralLimit()
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='central' LIMIT 4");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductNorthById($cateId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='north' AND p.active = 'enabled' AND category_id = '%s'",$cateId);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductSouthById($cateId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='south' AND p.active = 'enabled' AND category_id = '%s'",$cateId);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductCentralById($cateId)
    {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,r.name region_name FROM products p,region r WHERE p.region_id = r.id AND r.name='central' AND p.active = 'enabled' AND category_id = '%s'",$cateId);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getFirstCate(){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT * FROM category ORDER BY id ASC LIMIT 1");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getProductByOrder($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("SELECT p.*,o.quantity order_qty,o.status status,o.recipient_name,o.address,o.phone,o.type,o.order_id,u.firstname,u.lastname,u.email,u.phone phone_number FROM orders o,products p,users u WHERE order_id='%s' AND o.product_id = p.id AND u.id = o.user_id", $id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function saveContact($contact){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
    
        $query = sprintf("INSERT INTO `contacts` (`name`, `address`, `email`, `phone`, `title`, `content`) values ('%s', '%s', '%s', '%s', '%s', '%s')",$conn->real_escape_string($contact->name),$conn->real_escape_string($contact->address),$conn->real_escape_string($contact->email),$conn->real_escape_string($contact->phone),$conn->real_escape_string($contact->title),$conn->real_escape_string($contact->content));
        Mysqllib::mysql_post_data_from_query($conn, $query);
        header("Location: /homepage");
    }    

    public static function updateStatus($data){
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("UPDATE orders SET `status`='%s' WHERE `order_id`='%s'",$conn->real_escape_string($data['status']),$conn->real_escape_string($data['order_id'])
        );
        Mysqllib::mysql_get_data_from_query($conn, $query);
        if($data['status'] === 'complete'){
            $query1 = sprintf("SELECT o.user_id u_id FROM orders o WHERE o.order_id = '%s'",$conn->real_escape_string($data['order_id']));
            $res = Mysqllib::mysql_get_data_from_query($conn, $query1);
            $query2 = sprintf("SELECT b.table_id tb_id FROM booking b WHERE b.user_id = '%s'",$res->message[0]['u_id']);
            $res2 = Mysqllib::mysql_get_data_from_query($conn, $query2);
            foreach($res2->message as $row){
                $query = sprintf("UPDATE tables SET `active`='enabled' WHERE `id`='%s'",$row['tb_id']);
                Mysqllib::mysql_get_data_from_query($conn, $query);
            }
        }
        header("Location: /order");
    }

    public static function getTotal($start_date,$end_date){
        // Connect db
       $conn_resp = Database::connect_db();
       if (!$conn_resp->status) {
           return $conn_resp;
       }
       $conn = $conn_resp->message;

       $query = sprintf("SELECT sum(total),DATE(created_at) FROM `orders` WHERE status = 'complete' AND DATE(created_at) BETWEEN '%s' AND '%s' GROUP BY DATE(created_at)",$start_date,$end_date);
       $res = Mysqllib::mysql_get_data_from_query($conn, $query);
       return $res;
   }

   public static function checkQuantity($id)
   {
        // Connect db
        $conn_resp = Database::connect_db();
        if (!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        
        $select = sprintf("SELECT `quantity` qty FROM `products` WHERE `id`='%s'",$id);
        $res1 = Mysqllib::mysql_get_data_from_query($conn, $select);
        if($res1->message[0]['qty'] <= 0){
            $update = sprintf("UPDATE `products` SET `active`='disabled' WHERE id='%s'",
            $id
            );
            Mysqllib::mysql_post_data_from_query($conn, $update);
        }
   }
}