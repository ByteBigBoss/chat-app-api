<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);

$title = $_POST["title"];
$description = $_POST["description"];
$image = $_FILES["image"]["tmp_name"];
$imageText = $_POST["imageText"];
date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");

if($image!=null){
    $type = 3;
}else{
    $type = 1;
}

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$connection->query("INSERT INTO `moments`(`user_id`,`title`,`description`,`date_time`,`media_type_id`)
VALUES('".$userPHPObject->id."','".$title."','".$description."','".$date_time."','".$type."')");

if($imageText!=null){

    $table= $connection->query("SELECT * FROM `moments` WHERE `user_id`='".$userPHPObject->id."' ORDER BY `date_time` DESC");
    $row = $table->fetch_assoc();

    $connection->query("INSERT INTO `media_text`(`user_id`,`moment_id`,`text`)
    VALUES('".$userPHPObject->id."','".$row["id"]."','".$imageText."')");
}

if($image!=null){

    $table2= $connection->query("SELECT * FROM `moments` WHERE `user_id`='".$userPHPObject->id."' ORDER BY `date_time` DESC");
    $row2 = $table2->fetch_assoc();

    $table3= $connection->query("SELECT * FROM `user` WHERE `id`='".$userPHPObject->id."'");
    $row3 = $table3->fetch_assoc();

    $uniqID = uniqid();

    $table4 = $connection->query("INSERT INTO `moment_mdia`(`user_id`,`moments_id`,`media_url`,`media_type_id`)
    VALUES('".$userPHPObject->id."','".$row2["id"]."','uploads/moments".$row3["mobile"]."".$uniqID.".jpeg','3')");

move_uploaded_file($image,"./uploads/moments".$row3["mobile"]."".$uniqID.".jpeg");
}

echo("success");

?>