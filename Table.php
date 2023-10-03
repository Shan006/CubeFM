<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/file.log');

$username = "PDAPCBFMUF";
$password = "CTSApp";

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

$sql = "SELECT * FROM CB_ST_ALARMS";

$stmt = oci_parse($conn, $sql);

oci_execute($stmt);


$data = array();
while ($row = oci_fetch_assoc($stmt)) {
    $data[] = $row;
}

oci_free_statement($stmt);
oci_close($conn);

$response = array(
    "status" => 'success',
    "data" => $data
);

echo json_encode($response);
?>
