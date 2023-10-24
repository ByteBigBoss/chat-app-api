<?php

$userJSONText = $_POST["userJSONText"];
$userPHPObject = json_decode($userJSONText);

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");

$table = $connection->query("SELECT * FROM `moments` INNER JOIN `bookmark` ON `moments`.`id` = `bookmark`.`moment_id` WHERE `book_user_id`='".$userPHPObject->id."'");

$phpResponseArray = array();

for ($x = 0; $x < $table->num_rows; $x++) {

    $phpArrayItemObject = new stdClass();

    $moment = $table->fetch_assoc();

    $table2 = $connection->query("SELECT * FROM `moments` INNER JOIN `user` ON `moments`.`user_id` = `user`.`id` WHERE `moments`.`id`='" . $moment["id"] . "'");
    $details = $table2->fetch_assoc();

    $phpArrayItemObject->momentID = $moment["id"];
    $phpArrayItemObject->fname = $details["first_name"];
    $phpArrayItemObject->lname = $details["last_name"];
    $phpArrayItemObject->userID = $details["user_id"];
    $phpArrayItemObject->status = $details["user_status_id"];
    $phpArrayItemObject->title = $details["title"];
    $phpArrayItemObject->description = $details["description"];
    $phpArrayItemObject->profile = $details["profile_url"];

    // OLD FORMAT-------
    $phpDateTimeObject = strtotime($details["date_time"]);
    $timeStr = date('h:i A', $phpDateTimeObject);
    $phpArrayItemObject->time = $timeStr;

    $table3 = $connection->query("SELECT COUNT(`lid`)AS `likes` FROM `like`
    WHERE `moment_id`IN(SELECT `id` FROM `moments` WHERE `id`='" . $moment["id"] . "')");
    $likesCount = $table3->fetch_assoc();
    $phpArrayItemObject->like = $likesCount["likes"];

    $table4 = $connection->query("SELECT COUNT(`id`)AS `dislikes` FROM `dislike` 
    WHERE `moment_id`IN(SELECT `id` FROM `moments` WHERE `id`='" . $moment["id"] . "')");
    $dislikesCount = $table4->fetch_assoc();
    $phpArrayItemObject->dislike = $dislikesCount["dislikes"];

    $table5 = $connection->query("SELECT COUNT(`id`)AS `shares` FROM `share` 
    WHERE `moment_id`IN(SELECT `id` FROM `moments` WHERE `id`='" . $moment["id"] . "')");
    $shareCount = $table5->fetch_assoc();
    $phpArrayItemObject->share = $shareCount["shares"];

    if ($moment["media_type_id"] == 3) {
        $table6 = $connection->query("SELECT * FROM `moments` 
    INNER JOIN `user` ON `moments`.`user_id` = `user`.`id`
    INNER JOIN `moment_mdia` ON `moments`.`id` = `moment_mdia`.`moments_id` 
    WHERE `moments_id`='" . $moment["id"] . "'");

    $phpImageArray = array();

    for($i=0;$i<$table6->num_rows;$i++){

        $imageObject = new stdClass();
        $image = $table6->fetch_assoc();

        $imageObject->image = $image["media_url"];

        array_push($phpImageArray,$imageObject);

    }

        if($table6->num_rows==1){
        $phpArrayItemObject->mode = "single";
        $phpArrayItemObject->singleImage = $image["media_url"];
        }elseif($table6->num_rows>1){
        $phpArrayItemObject->mode = "multiple";

        }

    $phpArrayItemObject->images = $phpImageArray;
    

    } else {
        $phpArrayItemObject->images = "noImage";

    }

    array_push($phpResponseArray,$phpArrayItemObject);

}

$json = json_encode($phpResponseArray);
echo($json);

?>