<?php

$mobile = $_POST["mobile"];
$profile_picture_location = $_FILES["profile_picture"]["tmp_name"];
$cover_picture_location = $_FILES["cover"]["tmp_name"];
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$slogan = $_POST["slogan"];
$countryCode = $_POST["countryCode"];
$password = $_POST["password"];
$verifyPassword = $_POST["verifyPassword"];

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");
$userObject = new stdClass();

if($profile_picture_location==null){
    $userObject->pic = "none";
}
if($cover_picture_location==null){
    $userObject->cover="none";
}
if($firstName==null){
    $userObject->fname = 'none';
} if($lastName==null){
    $userObject->lname = "none";
}if($slogan==null){
    $userObject->slogan = "none";
}if($countryCode==null){
    $userObject->code = "none";
}if($mobile==null){
    $userObject->mobile = "none";
}if($password==null){
    $userObject->password = "none";
}if($verifyPassword==null){
    $userObject->vPassword = "none";
}if($password==$verifyPassword&&$password!=null){
    $userObject->passMsg = "Verified";
}else{
    $userObject->passMsg = "unmatched";
}

$table3 = $connection->query("SELECT * FROM `user` WHERE `mobile`='".$mobile."'");

$table = $connection->query("SELECT `id` FROM `country` WHERE `code`='" . $countryCode . "'");
$row = $table->fetch_assoc();
$country_id = $row["id"];

if($table3->num_rows==0){
    if ($country_id != null) {
        $connection->query("INSERT INTO `user`(`first_name`,`last_name`,`mobile`,`password`,`profile_url`,`cover_url`,`slogan`,`country_id`,`user_status_id`)
    VALUES('" . $firstName . "','" . $lastName . "','" . $mobile . "','" . $password . "','uploads/profile" . $mobile . ".jpeg','uploads/cover" . $mobile . ".jpeg','" . $slogan . "','" . $country_id . "','1');");
    
    }
}

move_uploaded_file($profile_picture_location,"./uploads/profile".$mobile.".jpeg");
move_uploaded_file($cover_picture_location,"./uploads/cover".$mobile.".jpeg");

if($mobile!=null){
    $table2 = $connection->query("SELECT * FROM `user` WHERE `mobile`='".$mobile."' AND `password`='".$password."'");
    $row2 =  $table2->fetch_assoc();
    

    $userObject->userID=$row2;


    
}
if($table2->num_rows==0){
    $userObject->msg="Error";
}else{
    $userObject->msg = "Success";
}
    $json = json_encode($userObject);
    echo($json);

?>