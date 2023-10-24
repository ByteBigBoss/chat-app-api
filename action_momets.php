<?php

$logUserJSON = $_POST["userJSON"];
$logUserObject = json_decode($logUserJSON);
$momentID = $_POST["momentID"];
$action = $_POST["action"];
$actionD = $_POST["actionD"];
$actionB = $_POST["actionB"];
date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

if($action=="like"){

    $table1 = $connection->query("SELECT * FROM `like` WHERE `like_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
    $table2 = $connection->query("SELECT * FROM `dislike` WHERE `dislike_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");

    if($table1->num_rows==0){
        $connection->query("INSERT INTO `like`(`like_user_id`,`moment_id`,`liked_date_time`)
        VALUES('".$logUserObject->id."','".$momentID."','".$date_time."')");
        if($table2->num_rows!=0){
            $connection->query("DELETE FROM `dislike` WHERE `dislike_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
        }
    }else{
        $connection->query("DELETE FROM `like` WHERE `like_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
    }

}

if($actionD=="dislike"){
    $table3 = $connection->query("SELECT * FROM `dislike` WHERE `dislike_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
    $table4 = $connection->query("SELECT * FROM `like` WHERE `like_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");

    if($table3->num_rows==0){
        $connection->query("INSERT INTO `dislike`(`dislike_user_id`,`moment_id`,`date_time`)
        VALUES('".$logUserObject->id."','".$momentID."','".$date_time."')");
        if($table4->num_rows!=0){
            $connection->query("DELETE FROM `like` WHERE `like_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
        }
    }else{
        $connection->query("DELETE FROM `dislike` WHERE `dislike_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
    }

}

if($actionB=="book"){
    $table5 = $connection->query("SELECT * FROM `bookmark` WHERE `book_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");

    if($table5->num_rows==0){
        $connection->query("INSERT INTO `bookmark`(`book_user_id`,`moment_id`,`bookmark_status_id`)
        VALUES('".$logUserObject->id."','".$momentID."','1')");
    }else{
        $connection->query("DELETE FROM `bookmark` WHERE `book_user_id`='".$logUserObject->id."' AND `moment_id`='".$momentID."'");
    }
}

?>