<?php

$image = $_FILES["image"]["tmp_name"];
$requestJSON = $_POST["requestJSON"];
$requestObject = json_decode($requestJSON);

$from_id = $requestObject->from_user_id;
$to_id = $requestObject->to_user_id;
$message = $requestObject->message;
date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");
if($message!=null){
    // INSERT CHAT TEXT DETAILS
    $connection->query("INSERT INTO `chat`(`user_from_id`,`user_to_id`,`text_or_url`,`date_time`,`status_id`,`chat_type_id`)
    VALUES('" . $from_id . "','" . $to_id . "','" . $message . "','" . $date_time . "','1','1')");
}

if($image!=null){

    $table= $connection->query("SELECT * FROM `user` WHERE `id`='".$from_id."'");
    $row = $table->fetch_assoc();

    $uniqID = uniqid();

        // INSERT CHAT TEXT DETAILS
        $connection->query("INSERT INTO `chat`(`user_from_id`,`user_to_id`,`text_or_url`,`date_time`,`status_id`,`chat_type_id`)
        VALUES('" . $from_id . "','" . $to_id . "','uploads/chat".$row["mobile"]."".$uniqID.".jpeg','" . $date_time . "','1','2')");

    move_uploaded_file($image,"./uploads/chat".$row["mobile"]."".$uniqID.".jpeg");
    

}

echo ("Success");

?>