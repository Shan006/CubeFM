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
// Step 2: Prepare and Execute SQL Query
$sql = "SELECT pdcmcbfmuf.cb_cms_seq.nextval FROM dual";
$stmt = oci_parse($conn, $sql);

if (!$stmt) {
    die("SQL parsing failed: " . oci_error($conn));
}

if (oci_execute($stmt) === false) {
    die("SQL execution failed: " . oci_error($stmt));
}

// Step 3: Fetch the Result
if (oci_fetch($stmt)) {
    $id = oci_result($stmt, 1); // Assuming the ID is in the first column
    
    // Create an associative array to hold the ID
    $response = array(
        'alert_id' => $id
    );

    // Encode the array as JSON
    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'No result found'));
}

// Step 4: Close the Connection
oci_close($conn);
?>
