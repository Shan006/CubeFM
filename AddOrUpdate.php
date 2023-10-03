<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/file.log');

header('Content-Type: application/json'); // Set response header to JSON

$response = array(); // Initialize an array to hold the response data

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $username = "PDADCBFMUF";
    $password = "CTSAdm";
    
    $conn = oci_connect($username, $password, "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = 161.97.85.250)(PORT = 1521))
    (CONNECT_DATA =
      (SID = ORCL)
      (PRESENTATION = RO)
    )
    )");
    
    if (!$conn) {
        $error = oci_error();
        $response['success'] = false;
        $response['message'] = 'Connection failed: ' . $error['message'];
        echo json_encode($response);
        exit;
    }
    
    $Indentifier = $_POST['identifier'];
    $AlertId = $_POST['alert_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $severity = $_POST['severity'];
    $createdOn = date('Y-m-d H:i:s');
    $qFilter = $_POST['q_filter'];
    $q_criteria = $_POST['q_criteria'];
    
    if ($Indentifier === "Add") {
        $sql = "INSERT INTO pdcmcbfmuf.cb_cms_alerts (alert_id, create_on, q_filter, q_criteria, alert, severity) 
                VALUES (:AlertId, TO_DATE(:createdOn, 'YYYY-MM-DD HH24:MI:SS'), :qFilter, :q_criteria, :alert, :severity)";
    } elseif ($Indentifier === "Update") {
        $sql = "UPDATE pdcmcbfmuf.cb_cms_alerts SET
                create_on = TO_DATE(:createdOn, 'YYYY-MM-DD HH24:MI:SS'),
                q_filter = :qFilter,
                q_criteria = :q_criteria,
                alert = :alert,
                severity = :severity
                WHERE alert_id = :AlertId";
    }
    
    $stmt = oci_parse($conn, $sql);
    
    oci_bind_by_name($stmt, ":AlertId", $AlertId);
    oci_bind_by_name($stmt, ":alert", $name);
    // oci_bind_by_name($stmt, ":type", $type);
    oci_bind_by_name($stmt, ":severity", $severity);
    oci_bind_by_name($stmt, ":createdOn", $createdOn);
    oci_bind_by_name($stmt, ":qFilter", $qFilter);
    oci_bind_by_name($stmt, ":q_criteria", $q_criteria);
    
    if (oci_execute($stmt)) {
        $response['success'] = true;
        $response['message'] = "Data " . ($Indentifier === "Add" ? "Inserted" : "Updated") . " successfully.";
    } else {
        $error = oci_error($stmt);
        $response['success'] = false;
        $response['message'] = "Data " . ($Indentifier === "Add" ? "Insertion" : "Update") . " failed. Error: " . $error['message'];
    }
    
    oci_free_statement($stmt);
    oci_close($conn);
    
    // Return the JSON-encoded response
    echo json_encode($response);
}
?>
