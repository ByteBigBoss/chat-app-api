<?php

$user1 = $_POST["id1"];
$user2 = $_POST["id2"];

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$connection->query("UPDATE `chat` SET `status_id`='2' WHERE (`user_from_id`='".$user2."' AND `user_to_id`='".$user1."') ");

$table = $connection->query("SELECT * FROM `chat` INNER JOIN `status` ON `chat`.`status_id`=`status`.`id` WHERE
(`user_from_id`='".$user1."' AND `user_to_id`='".$user2."') OR
(`user_from_id`='".$user2."' AND `user_to_id`='".$user1."')
ORDER BY `date_time` ASC");

// $table2 = $connection->query("SELECT * FROM `chat_image` INNER JOIN `status` ON `chat`.`status_id`=`status`.`id` WHERE
// (`user_from_id`='".$user1."' AND `user_to_id`='".$user2."') OR
// (`user_from_id`='".$user2."' AND `user_to_id`='".$user1."')
// ORDER BY `date_time` ASC");

$chatArray = array();

for ($x=0;$x<$table->num_rows;$x++){

    $row = $table->fetch_assoc();

    $chatObject = new stdClass();
    if($row["chat_type_id"]==1){
        $chatObject->msg = $row["text_or_url"];
        $chatObject->image = null;
        $chatObject->type = "text";
    }
    if($row["chat_type_id"]==2){
        $chatObject->image = $row["text_or_url"];
        $chatObject->msg = null;
        $chatObject->type = "image";
    }

    $phpDateTimeObject = strtotime($row["date_time"]);
    $timeStr = date('h:i A', $phpDateTimeObject);
    $chatObject->time = $timeStr;

    if($row["user_from_id"]==$user1){
        $chatObject->side = "right";
    }else{
        $chatObject->side = "left";
    }

    $chatObject->status = strtolower($row["name"]);
    array_push($chatArray,$chatObject);

}

$responseJSON = json_encode($chatArray);
echo($responseJSON);

?>