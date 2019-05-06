<?php

    require ('credentials.php');

    // Create connection
    $conn = new mysqli($pinocho_servername, $pinocho_username, $pinocho_password, $pinocho_dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to database"));
        return;
    }

    $request_id = $_POST['request_id'];
    $admin_id = $_POST['admin_id'];
    $expiration_date = \DateTime::createFromFormat('D M d Y H:i:s e+', $_POST['expiration_date']);

    $sql = "UPDATE request SET status = 'approved', admin_id = $admin_id, dateExpired = '{$expiration_date->format('Y-m-d H:i:s')}' WHERE id = $request_id";
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