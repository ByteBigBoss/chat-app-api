<?php
$user = $_POST["profilUserID"];

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$table = $connection->query("SELECT * FROM `user` WHERE `id`='".$user."'");
$row = $table->fetch_assoc();

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
$ResponseObject->status = $row["user_status_id"];
$ResponseObject->country = $row2["name"];
$ResponseObject->code = $row2["code"];


$json = json_encode($ResponseObject);
echo($json);

?>