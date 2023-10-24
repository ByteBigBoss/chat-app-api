<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);
$usrMobile = $_POST["mobile"];

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

if($usrMobile==null){
    $table = $connection->query("SELECT * FROM `user` WHERE `id`='".$userPHPObject->id."'");
    $row = $table->fetch_assoc();
}else{
    $table = $connection->query("SELECT * FROM `user` WHERE `mobile`='".$usrMobile."'");
    $row = $table->fetch_assoc();
}

$table2 = $connection->query("SELECT * FROM `country` WHERE `id`='".$row["country_id"]."'");
$row2 = $table2->fetch_assoc();

$ResponseObject = new stdClass();

$ResponseObject->fname = $row["first_name"];
$ResponseObject->lname = $row["last_name"];
$ResponseObject->pic = $row["profile_url"];
$ResponseObject->cover = $row["cover_url"];
$ResponseObject->mobile = $row["mobile"];
$ResponseObject->slogan = $row["slogan"];
$ResponseObject->userID = $row["id"];
$ResponseObject->country = $row2["name"];
$ResponseObject->code = $row2["code"];

$json = json_encode($ResponseObject);
echo($json);

?>