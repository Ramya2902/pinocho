<?php

    require ('credentials.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to database"));
        return;
    }

    $request_id = $_POST['request_id'];
    $admin_id = $_POST['admin_id'];
    $denied_description = $conn->real_escape_string($_POST['deniedDescription']);

    $sql = "UPDATE request SET status = 'denied', admin_id = '$admin_id', deniedDescription = '$denied_description' WHERE id = $request_id";
    $result = $conn->query($sql);
    if($result) {
        $json = array("status" => "OK");
    }else {
        $json = array("status" => "ERROR",
                    "error" => $conn->error);
    }

    header('Content-Type: application/json');
    echo json_encode($json);

?>