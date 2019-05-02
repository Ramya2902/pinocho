<?php

    require ('credentials.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to database"));
        return;
    }

    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $usage = $conn->real_escape_string($_POST['usage']);
    $store = $conn->real_escape_string($_POST['store']);

    // int in number of weeks
    $access_length = $conn->real_escape_string($_POST['access_length']);

    // 1 = 1 week, 2 = 1-2 weeks, 3 = 2 or more weeks
    $access_soon = $conn->real_escape_string($_POST['access_soon']);

    // 'identified', 'deidentified', 'aggregated', 'limited'
    $data_type = $conn->real_escape_string($_POST['data_type']);

    $user_id = $conn->real_escape_string($_POST['user_id']);

    $sql = "INSERT INTO request (user_id, title, description, howDataUsed, howDataStored, weeksAccessible, weeksUntilAccessible, dataType) VALUES ('$user_id', '$title', '$description', '$usage', '$store', '$access_length', '$access_soon', '$data_type')";
    $result = $conn->query($sql);
    $json = null;

    if($result) {
        $json = array("status" => "OK");
    }else {
        $json = array("status" => "ERROR",
                    "error" => $conn->error);
    }


    // $obj = array("requestID" => $_GET['request_id'],
    // "requestDate" => $_GET['request_date'],
    // "requestStatus" => $_GET['request_status'],
    // "requestDesc" => "This is a test description returned by /pinocho/viewRequests.php"
    // );

    header('Content-Type: application/json');
    echo json_encode($json);

?>