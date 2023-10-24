<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);
$logUser = $userPHPObject->id;

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$table=$connection->query("SELECT * FROM `friend` WHERE (`log_user_id`='".$logUser."' OR `friend_id`='".$logUser."') AND `friend_status_id`='2'");


// $friend = 

$phpResponseArray = array();

for ($x=0;$x<$table->num_rows;$x++){

    $phpArrayItemObject = new stdClass();
    $row = $table->fetch_assoc();

    if($row["log_user_id"] == $userPHPObject->id){
        $table2 = $connection->query("SELECT * FROM `user` INNER JOIN `country` ON `user`.`country_id`=`country`.`id` WHERE `user`.`id`='".$row["friend_id"]."'");
        $row2 = $table2->fetch_assoc();
    
        $phpArrayItemObject->fname= $row2["first_name"];
        $phpArrayItemObject->lname= $row2["last_name"];
        $phpArrayItemObject->mobile= $row2["mobile"];
        $phpArrayItemObject->profile= $row2["profile_url"];
        $phpArrayItemObject->cover= $row2["cover_url"];
        $phpArrayItemObject->slogan= $row2["slogan"];
        $phpArrayItemObject->status= $row2["user_status_id"];
        $phpArrayItemObject->country= $row2["name"];
        $phpArrayItemObject->userID= $row["friend_id"];
    }elseif($row["friend_id"] == $userPHPObject->id){
        $table2 = $connection->query("SELECT * FROM `user` INNER JOIN `country` ON `user`.`country_id`=`country`.`id` WHERE `user`.`id`='".$row["log_user_id"]."'");
        $row2 = $table2->fetch_assoc();
    
        $phpArrayItemObject->fname= $row2["first_name"];
        $phpArrayItemObject->lname= $row2["last_name"];
        $phpArrayItemObject->mobile= $row2["mobile"];
        $phpArrayItemObject->profile= $row2["profile_url"];
        $phpArrayItemObject->cover= $row2["cover_url"];
        $phpArrayItemObject->slogan= $row2["slogan"];
        $phpArrayItemObject->status= $row2["user_status_id"];
        $phpArrayItemObject->country= $row2["name"];
        $phpArrayItemObject->userID= $row["log_user_id"];
    }

    array_push($phpResponseArray,$phpArrayItemObject);

}

$json = json_encode($phpResponseArray);
echo($json);

?>