<?php
    $startTime = microtime(true);
    require ('credentials.php');

    // ===V=== CONNECTING TO PINOCHO DATABASE ===V=== \\

    // ** THIS IS ONLY FOR ESCAPING VALUES **

    // Create connection
    $conn = new mysqli($pinocho_servername, $pinocho_username, $pinocho_password, $pinocho_dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to request database"));
        return;
    }
    // ===/\=== END CONNECTING TO PINOCHO DATABASE ===/\=== \\

    // ===V=== GATHERING FORM INFORMATION ===V=== \\
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

    // 'external' or 'internal'
    $user_type = $conn->real_escape_string($_POST['user_type']);

    // id of the requested dataset. will query dataset database to obtain risk value
    $dataset_id = $conn->real_escape_string($_POST['dataset_id']);

    $user_id = $conn->real_escape_string($_POST['user_id']);

    $conn->close();
    // ===/\=== END GATHERING FORM INFORMATION ===/\=== \\

    // ===V=== RISK ASSESSMENT ===V=== \\

    // input_risk[0]: user_type => 1 = local/internal, 90 = external
    // input_risk[1]: data_type => 95 = identified, 45 = deidentified, 30 = limited, 3 = aggregated
    // input_risk[2]: data_source => 3 = local/internal?, 95 = external?
    // input_risk defaults: 1, 3, 3 (internal, aggregated, ?)
    $input_risk = array(1,3,3);

    // determining input_risk for user_type
    if($user_type == 'internal') {
        $input_risk[0] = 1;
    }else if($user_type == 'external') {
        $input_risk[0] = 90;
    }

    // determining input_risk for data_type
    if($data_type == 'identified') {
        $input_risk[1] = 95;
    }else if($data_type == 'deidentified') {
        $input_risk[1] = 30;
    }else if($data_type == 'limited') {
        $input_risk[1] = 45;
    }else if($data_type == 'aggregated') {
        $input_risk[1] = 3;
    }

    // determining input_risk for data_source
    // ===V=== CONNECTING TO DATASET DATABASE ===V=== \\

    // Create connection
    $dataset_conn = pg_connect("host=$dataset_servername dbname=$dataset_dbname user=$dataset_username password=$dataset_password");

    // Check connection
    if (pg_connection_status($dataset_conn) == PGSQL_CONNECTION_BAD) {
        echo json_encode(array("error" => "failed to connect to dataset database"));
        return;
    }
    // ===/\=== END CONNECTING TO DATASET DATABASE ===/\=== \\

    // ===V=== RETRIEVING DATASET RISK VALUE DATABASE ===V=== \\
    $escaped_dataset_id = pg_escape_string($dataset_id);
    $result_dataset = pg_query($dataset_conn, "SELECT * FROM data_catalog where dataset_id = {$escaped_dataset_id} LIMIT 1");
    $dataset = null;
    if ($result_dataset != false) {
        $dataset = pg_fetch_array($result_dataset);
    }else {
        echo json_encode(array("error" => "failed to retrieve specified dataset",
                                "message" => pg_last_error()));
        return;
    }

    $accept_risk = $dataset['dataset_risk'];
    $dataset_name = $dataset['dataset_name'];
    pg_close($dataset_conn);
    // ===/\=== END RETRIEVING DATASET RISK VALUE DATABASE ===/\=== \\
        
    // calculate risk
    $total_risk = 0;
    $data_risk = log($accept_risk);

    foreach($input_risk as $i) {
        $total_risk += log($i);
    }

    $risk_factor = exp(log($total_risk));
    $risk_level = "low";

    // determine risk_level (high, medium, or low)
    if($risk_factor >= (1.25 * $data_risk)){
        $risk_level = "high";
    }else if(($risk_factor >= (0.75 * $data_risk)) && ($risk_factor <= (1.25 * $data_risk))) {
        $risk_level = "medium";
    }else {
        $risk_level = "low";
    }

    // ===/\=== END RISK ASSESSMENT ===/\=== \\

    // ===V=== CONNECTING TO PINOCHO DATABASE ===V=== \\

    // Create connection
    $conn = new mysqli($pinocho_servername, $pinocho_username, $pinocho_password, $pinocho_dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(array("error" => "failed to connect to request database"));
        return;
    }
    // ===/\=== END CONNECTING TO PINOCHO DATABASE ===/\=== \\

    // ===V=== INSERTING REQUEST INTO DATABASE ===V=== \\
    $sql = "INSERT INTO request (user_id, title, description, howDataUsed, howDataStored, weeksAccessible, weeksUntilAccessible, dataType, risk, user_type, dataset_id, dataset_name) VALUES ('$user_id', '$title', '$description', '$usage', '$store', '$access_length', '$access_soon', '$data_type', '$risk_level', '$user_type', '$dataset_id', '$dataset_name')";
    $result = $conn->query($sql);
    $json = null;

    if($result) {
	$json = array("status" => "OK",
                "dataset" => $dataset,
                "dataset_risk" => $dataset['dataset_risk'],
                "risk_assess" => array("risk_factor" => $risk_factor, "data_risk" => $data_risk, "risk_level" => $risk_level));
    }else {
        $json = array("status" => "ERROR",
		    "error" => $conn->error);
    }

    // $result->close();
    $conn->close();
    // ===/\=== END INSERTING REQUEST INTO DATABASE ===/\=== \\

    $json['calculateTime'] = round(microtime(true) - $startTime,3)*1000;

    // ===V=== RETURN RESPONSE TO FRONTEND ===V=== \\
    header('Content-Type: application/json');
    echo json_encode($json);

?>
