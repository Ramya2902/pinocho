<?php

    require ('credentials.php');

    // Create connection
    $conn = new mysqli($pinocho_servername, $pinocho_username, $pinocho_password, $pinocho_dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to database"));
        return;
    }

    $user_id = null;
    $admin_id = null;
    if(array_key_exists('user_id', $_GET)) {
        $user_id = $_GET['user_id'];
    }
    if(array_key_exists('admin_id', $_GET)) {
        $admin_id = $_GET['admin_id'];
    }
    

    if($user_id == null && $admin_id != null) {
        $sql = "SELECT * FROM request WHERE status = 'pending'";
        $result = $conn->query($sql);
        $pending = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($pending, $row);
            }
        }

        // $admin_id = $conn->real_escape_string($admin_id);

        $sql = "SELECT * FROM request WHERE admin_id = $admin_id";
        $result = $conn->query($sql);

        $approved = array();
        $denied = array();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row['status'] == "approved") {
                    array_push($approved, $row);
                }else {
                    array_push($denied, $row);
                }
            }
        }

        $requests = array("pending" => $pending,
                        "approved" => $approved,
                        "denied" => $denied,
                        "user_id" => $user_id,
                        "admin_id" => $admin_id);

        header('Content-Type: application/json');
        echo json_encode($requests);
        return;
    }else {
        $sql = "SELECT * FROM request where user_id = $user_id";
        $result = $conn->query($sql);
        $pending = array();
        $approved = array();
        $denied = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row['status'] == "pending") {
                    array_push($pending, $row);
                }else if($row['status'] == "approved") {
                    array_push($approved, $row);
                }else {
                    array_push($denied, $row);
                }
            }
        }

        $requests = array("pending" => $pending,
                        "approved" => $approved,
                        "denied" => $denied,
                        "user_id" => $user_id,
                        "admin_id" => $admin_id);

        header('Content-Type: application/json');
        echo json_encode($requests);
        return;
    }
?>