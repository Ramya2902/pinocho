<?php

    require ('credentials.php');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to database"));
        return;
    }

    $request_id = $_GET['request_id'];

    $sql = "SELECT * FROM request where id = $request_id LIMIT 1";
    $result = $conn->query($sql);
    $request = null;
    if ($result->num_rows == 1) {
        $request = $result->fetch_assoc();
    }


    // $obj = array("requestID" => $_GET['request_id'],
    // "requestDate" => $_GET['request_date'],
    // "requestStatus" => $_GET['request_status'],
    // "requestDesc" => "This is a test description returned by /pinocho/viewRequests.php"
    // );

    header('Content-Type: application/json');
    echo json_encode($request);

?>