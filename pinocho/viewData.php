<?php
    require ('credentials.php');

    $request_id = null;
    if(array_key_exists('request_id', $_GET)) {
        $request_id = $_GET['request_id'];
    }else {
        echo json_encode(array("error" => "request_id not submitted"));
        return;
    }
    
    // Create connection
    $conn = new mysqli($pinocho_servername, $pinocho_username, $pinocho_password, $pinocho_dbname);

    $sql = "SELECT * FROM request where id = $request_id LIMIT 1";
    $result = $conn->query($sql);
    $request = null;
    if ($result->num_rows == 1) {
        $request = $result->fetch_assoc();
    }
    $conn->close();

    // Create connection
    $conn = pg_connect("host=$dataset_servername dbname=$dataset_dbname user=$dataset_username password=$dataset_password");

    $dataset_id = pg_escape_string($request['dataset_id']);

    $result = pg_query($conn, "SELECT * FROM data_catalog where dataset_id = {$dataset_id} LIMIT 1");
    $dataset = null;
    if ($result != false) {
        $dataset = pg_fetch_array($result);
    }else {
        echo json_encode(array("error" => "failed to retrieve specified dataset",
                                "message" => pg_last_error()));
        return;
    }

    $dataset_query = $dataset['dataset_query'];

    $result = pg_query($conn, "$dataset_query");
    $data = array();
    if($result != false) {
        while($row = pg_fetch_array($result)) {
            array_push($data, $row);
        }
    }else {
        echo json_encode(array("error" => "failed to retrieve specified data using dataset query",
                                "dataset_query" => $dataset_query,
                                "message" => pg_last_error()));
        return;
    }

    pg_close($dataset_conn);

    echo json_encode(array("request" => $request, "dataset" => $dataset, "dataset_query" => $dataset_query, "data" => $data, "number_rows_data" => pg_num_rows($result)));
    return;

    // $escaped_dataset_id = pg_escape_string($dataset_id);
    // $result_dataset = pg_query($dataset_conn, "SELECT * FROM data_catalog where dataset_id = {$escaped_dataset_id} LIMIT 1");
    // $dataset = null;
    // if ($result_dataset != false) {
    //     $dataset = pg_fetch_array($result_dataset);
    // }else {
    //     echo json_encode(array("error" => "failed to retrieve specified dataset",
    //                             "message" => pg_last_error()));
    //     return;
    // }

    // $accept_risk = $dataset['dataset_risk'];
    // $dataset_name = $dataset['dataset_name'];
    // pg_close($dataset_conn);
?>