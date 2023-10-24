<?php

$userJSON = $_POST["userJSON"];
$userPHPObject = json_decode($userJSON);

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$responseArray = array();

// LOAD LIKE NOTIFICATIONS
$table = $connection->query("SELECT * FROM `moments` INNER JOIN `like` ON `like`.`moment_id`=`moments`.`id` INNER JOIN `user` ON `user`.`id` = `like`.`like_user_id` WHERE `user_id`='".$userPHPObject->id."'");

for($x=0;$x<$table->num_rows;$x++){

    $ItemObject = new stdClass();

    $row = $table->fetch_assoc();

    $ItemObject->likeID = $row["lid"];
    $ItemObject->pic = $row["profile_url"];
    $ItemObject->fname = $row["first_name"];
    $ItemObject->lname = $row["last_name"];

    $phpDateTimeObject = strtotime($row["liked_date_time"]);
    $timeStr = date('h:i A', $phpDateTimeObject);
    $ItemObject->time = $timeStr;

    $ItemObject->mode = "like";

    $table2->$connection->query("SELECT * FROM `moment_mdia` WHERE `moments_id`='".$row["moment_id"]."'");    
    $row2 = $table2->fetch_assoc();
    $ItemObject->moment = $row2["media_url"];

    array_push($responseArray,$ItemObject);
}

// LOAD FRIEND REQUEST NOTIFICATIONS
$table3 = $connection->query("SELECT * FROM `friend_request`INNER JOIN `user` ON `friend_request`.`from_user_id`=`user`.`id` WHERE `to_user_id`='".$userPHPObject->id."'");

for($i=0;$i<$table3->num_rows;$i++){

    $friendItemObject = new stdClass();

    $row3 = $table3->fetch_assoc();

    $friendItemObject->req_id = $row3["req_id"];
    $friendItemObject->pic = $row3["profile_url"];
    $friendItemObject->fname = $row3["first_name"];
    $friendItemObject->lname = $row3["last_name"];

    $phpDateTimeObject2 = strtotime($row3["request_date_time"]);
    $timeStr = date('h:i A', $phpDateTimeObject2);
    $friendItemObject->time = $timeStr;

    $friendItemObject->mode = "request";

    $friendItemObject->moment = "none";

    array_push($responseArray,$friendItemObject);
}

$json = json_encode($responseArray);
echo($json);

?>