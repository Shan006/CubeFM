<?php 
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/file.log');

$alertId = $_POST["alert_id"];
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
    die("Connection failed: " . $error["message"]);
}

$sql = "DELETE FROM pdcmcbfmuf.cb_cms_alerts WHERE alert_id = :alertId";
$stmt = oci_parse($conn, $sql);

oci_bind_by_name($stmt, ":alertId", $alertId);  

if (oci_execute($stmt)) {
    $response = [
        'status' => 'success',
        'message' => 'Delete query executed successfully.',
    ];
} else {
    $error = oci_error($stmt);
    $response = [
        'status' => 'error',
        'message' => 'Error executing delete query: ' . $error["message"],
    ];
}

oci_free_statement($stmt);
oci_close($conn);

header('Content-Type: application/json');
echo json_encode($response);

?>
