<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$table = $connection->query("SELECT * FROM  `friend` WHERE `log_user_id`='" . $userPHPObject->id . "' OR `friend_id`='" . $userPHPObject->id . "'");
$countRow = $connection->query("SELECT COUNT(`id`)AS`count` FROM  `friend` WHERE `log_user_id`='" . $userPHPObject->id . "' OR `friend_id`='" . $userPHPObject->id . "'");
$count = $countRow->fetch_assoc();

$obj = new stdClass();
$phpResponseArray = array();

for ($x = 0; $x < $table->num_rows; $x++) {

    $phpArrayItemObject = new stdClass();
    $row1 = $table->fetch_assoc();

    if ($row1["log_user_id"] == $userPHPObject->id) {

        $table2 = $connection->query("SELECT * FROM `user` WHERE `id`='" . $row1["friend_id"] . "'");
        $user = $table2->fetch_assoc();

        $phpArrayItemObject->pic = $user["profile_url"];
        $phpArrayItemObject->cover = $user["cover_url"];
        $phpArrayItemObject->fname = $user["first_name"];
        $phpArrayItemObject->lname = $user["last_name"];
        $phpArrayItemObject->userID = $user["id"];
        $phpArrayItemObject->status = $user["user_status_id"];
        $phpArrayItemObject->mode = $user["friend_status_id"];

        $table2 = $connection->query("SELECT * FROM `country` WHERE `id`='" . $user["country_id"] . "'");
        $countryRow = $table2->fetch_assoc();
        $phpArrayItemObject->country = $countryRow["name"];

    } elseif ($row1["friend_id"] == $userPHPObject->id) {

        $table2 = $connection->query("SELECT * FROM `user` WHERE `id`='" . $row1["log_user_id"] . "'");
        $user = $table2->fetch_assoc();

        $phpArrayItemObject->pic = $user["profile_url"];
        $phpArrayItemObject->cover = $user["cover_url"];
        $phpArrayItemObject->fname = $user["first_name"];
        $phpArrayItemObject->lname = $user["last_name"];
        $phpArrayItemObject->userID = $user["id"];
        $phpArrayItemObject->status = $user["user_status_id"];
        $phpArrayItemObject->mode = $user["friend_status_id"];

        $table2 = $connection->query("SELECT * FROM `country` WHERE `id`='" . $user["country_id"] . "'");
        $countryRow = $table2->fetch_assoc();
        $phpArrayItemObject->country = $countryRow["name"];

    }



    array_push($phpResponseArray,$phpArrayItemObject);

}

$obj->array = $phpResponseArray;
$obj->count = $count["count"];

$jsonResponseText = json_encode($obj);
echo ($jsonResponseText);

?>