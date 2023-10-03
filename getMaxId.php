<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/file.log');

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
    die('Connection failed: ' . $error['message']);
}

$sql = 'SELECT MAX(alert_id) AS max_alert_id FROM pdcmcbfmuf.cb_cms_alerts';
$stmt = oci_parse($conn, $sql);

if (oci_execute($stmt)) {
    $row = oci_fetch_assoc($stmt);
    $maxAlertId = $row['MAX_ALERT_ID'];
    $nextAlertId = $maxAlertId + 1;

    $response = array(
        'status' => 'success',
        'alert_id' => $nextAlertId,
    );
    $jsonResponse = json_encode($response);
    echo $jsonResponse;
    exit;
} else {
    $error = oci_error($stmt);
    $response = array(
        'status' => 'error',
        'message' => 'Data retrieval failed: ' . $error['message'],
    );
    $jsonResponse = json_encode($response);
    echo $jsonResponse;
    exit;
}

oci_free_statement($stmt);
oci_close($conn);
?>
