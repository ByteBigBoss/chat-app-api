<?php

$jsonRequestText = $_POST["jsonRequestText"];
$phpRequestObject = json_decode($jsonRequestText);

$mobile = $phpRequestObject->mobile;
$password = $phpRequestObject->password;

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");
$table = $connection->query("SELECT * FROM `user` WHERE `mobile`='".$mobile."' AND `password`='".$password."'");

$phpResponseObject = new stdClass();

    // $table2 = $connection->query("SELECT * FROM `user` WHERE `mobile`='".$mobile."'");

    // if($table2->num_rows==1){
    //     $row2 = $table2->fetch_assoc();
    //     $phpResponseObject->mobile = "Avalible";
    // }
    
    // if($mobile==null){
    //     $phpResponseObject->mobile = "Unavalible";
    // }

    // if($row2["password"]!=$password){
    //     $phpResponseObject->password = "Wrong";
    // }


if($table->num_rows==0){
    $phpResponseObject->msg = "Error";
}else{

    $phpResponseObject->msg = "Success";

    $row = $table->fetch_assoc();
    $phpResponseObject->user = $row;

}

$jsonResponseText = json_encode($phpResponseObject);
echo($jsonResponseText);

?>