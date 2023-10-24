<?php

$connection = new mysqli("127.0.0.1", "root", "Ms2005j@Neru", "my_chat_app");
$table = $connection->query("SELECT * FROM `country`");

$country_array = array();

for($x=0;$x<$table->num_rows;$x++){
    $row = $table->fetch_assoc();

    $obj = new stdClass();

    // $obj->name = $row["name"];
    // $obj->code = $row["code"];

    array_push($country_array,$row["code"]);
}

$json = json_encode($country_array);
echo($json);

?>