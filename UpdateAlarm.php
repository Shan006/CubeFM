<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
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

$submitButton = $_POST['submitButton'];
$AlertId = $_POST['alert_id'];
$alert = $_POST['alert'];
$interval = $_POST['interval'];
$status = $_POST['status'];
$createdOn = date('Y-m-d H:i:s');
$qFilter = $_POST['q_filter'];
$userId = '1';
$eventCount = $_POST['eventcount'];
$dataVolume = $_POST['datavolume'];
$callDuration = $_POST['callduration'];
$amount = $_POST['amount'];
$severity = $_POST['severity'];
$q_criteria = "count*( >= $eventCount) AND sum( >= $dataVolume) AND sum( >= $callDuration) AND sum( >= $amount)";


if ($submitButton === "Add") {
    $sql = "INSERT INTO PDCMCBFMUFCB_CMS_ALERTS (alert_id, alert, interval, status, create_on, q_filter, q_criteria, user_id, eventcount, datavolume, callduration, amount, severity) 
            VALUES (:AlertId, :alert, :interval, :status, TO_DATE(:createdOn, 'YYYY-MM-DD HH24:MI:SS'), :qFilter, :q_criteria, :userId, :eventCount, :dataVolume, :callDuration, :amount, :severity)";
} elseif ($submitButton === "Save") {
    $sql = "UPDATE PDCMCBFMUFCB_CMS_ALERTS SET
            alert = :alert,
            interval = :interval,
            status = :status,
            create_on = TO_DATE(:createdOn, 'YYYY-MM-DD HH24:MI:SS'),
            q_filter = :qFilter,
            q_criteria = :q_criteria,
            user_id = :userId,
            eventcount = :eventCount,
            datavolume = :dataVolume,
            callduration = :callDuration,
            amount = :amount,
            severity = :severity
            WHERE alert_id = :AlertId";
}


$stmt = oci_parse($conn, $sql);

oci_bind_by_name($stmt, ":AlertId", $AlertId);
oci_bind_by_name($stmt, ":alert", $alert);
oci_bind_by_name($stmt, ":interval", $interval);
oci_bind_by_name($stmt, ":status", $status);
oci_bind_by_name($stmt, ":createdOn", $createdOn);
oci_bind_by_name($stmt, ":qFilter", $qFilter);
oci_bind_by_name($stmt, ":userId", $userId);
oci_bind_by_name($stmt, ":eventCount", $eventCount);
oci_bind_by_name($stmt, ":dataVolume", $dataVolume);
oci_bind_by_name($stmt, ":callDuration", $callDuration);
oci_bind_by_name($stmt, ":amount", $amount);
oci_bind_by_name($stmt, ":severity", $severity);
oci_bind_by_name($stmt, ":q_criteria", $q_criteria);

if (oci_execute($stmt)) {
    echo "Data " . ($submitButton === "Add" ? "Inserted" : "Updated") . " successfully.";
    header("Location: index.php");
    exit;
} else {
    $error = oci_error($stmt);
    echo "Data " . ($submitButton === "Add" ? "insertion" : "update") . " failed. Error: " . $error['message'];
    header("Location: error.php?error=" . urlencode($error));
    exit;
}

oci_free_statement($stmt);
oci_close($conn);
?>
