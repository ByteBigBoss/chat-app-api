<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$table = $connection->query("SELECT * FROM  `user` WHERE `id`!='".$userPHPObject->id."' AND (`first_name` LIKE '".$_POST["text"]."%') OR (`last_name` LIKE '".$_POST["text"]."')");

$phpResponseArray = array();

for($x=0;$x<$table->num_rows;$x++){

    $phpArrayItemObject = new stdClass();

    $user = $table->fetch_assoc();
    $phpArrayItemObject->pic =$user["profile_url"];
    $phpArrayItemObject->fname =$user["first_name"];
    $phpArrayItemObject->lname =$user["last_name"];
    $phpArrayItemObject->id =$user["id"];
    $phpArrayItemObject->status =$user["user_status_id"];

    $table2 = $connection->query("SELECT * FROM `chat` WHERE 
    (`user_from_id`='".$userPHPObject->id."' AND `user_to_id`='".$user["id"]."') OR
    (`user_from_id`='".$user["id"]."' AND `user_to_id`='".$userPHPObject->id."') 
    ORDER BY `date_time` DESC");

    if($table2->num_rows==0){
        $phpArrayItemObject->msg = "Tap to start message";
        $phpArrayItemObject->time = "";
        $phpArrayItemObject->count = "0";

    }else{
       // unseen chat count
       $unseenChatCount = 0;

       // first row
       $lastChatRow = $table2->fetch_assoc();
       if($lastChatRow["status_id"]==1&&$lastChatRow["user_from_id"]==$user["id"]){
           $unseenChatCount++;
       }

       if($lastChatRow["chat_type_id"]==1){
        $phpArrayItemObject->msg = $lastChatRow["text_or_url"];
       }
       if($lastChatRow["chat_type_id"]==2){
        $phpArrayItemObject->msg = "image";
       }

       $phpDateTimeObject = strtotime($lastChatRow["date_time"]);
       $timeStr = date('h:i A', $phpDateTimeObject);

       $phpArrayItemObject->time = $timeStr;

       for ($i = 1; $i <$table2->num_rows;$i++){
            // other rows
            $newChatRow = $table2->fetch_assoc();
            if($newChatRow["status_id"]==1&&$newChatRow["user_from_id"]==$user["id"]){
                $unseenChatCount++;
            }
       }

       $phpArrayItemObject->count = $unseenChatCount;
    }

    array_push($phpResponseArray,$phpArrayItemObject);

}

$jsonResponseText = json_encode($phpResponseArray);
echo($jsonResponseText);

?>