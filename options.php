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

// Step 2: Define your array of records (assuming an associative array)
$records = [
    ['colname' => 'field', 'description' => 'ANumber' , 'type' => 'CDR'],
    ['colname' => 'field', 'description' => 'Eventtype' , 'type' => 'CDR'],
    ['colname' => 'field', 'description' => 'Calltype' , 'type' => 'CDR'],
    ['colname' => 'field', 'description' => 'Amount' , 'type' => 'CDR'],
    ['colname' => 'value', 'description' => 'ANumber' , 'type' => 'CDR'],
    ['colname' => 'value', 'description' => 'Amount' , 'type' => 'CDR'],
    ['colname' => 'value', 'description' => 'BHandsetID' , 'type' => 'CDR'],
    ['colname' => 'field', 'description' => 'ANumberis' , 'type' => 'Subscriber'],
    ['colname' => 'field', 'description' => 'Amount' , 'type' => 'Subscriber'],
    ['colname' => 'value', 'description' => 'AccountID' , 'type' => 'Subscriber'],
    ['colname' => 'value', 'description' => 'ActivationID' , 'type' => 'Subscriber'],
    ['colname' => 'value', 'description' => 'Balance' , 'type' => 'Subscriber'],
    ['colname' => 'subset', 'description' => 'CallType=All' , 'type' => 'Subset'],
    ['colname' => 'subset', 'description' => 'CallType=International' , 'type' => 'Subset'],
    // Add more records as needed
];

// Step 3: Loop through the array and insert records into the database
foreach ($records as $record) {
    // Prepare an SQL INSERT statement
    $sql = "INSERT INTO options (colname, description, type) VALUES (:colname, :description, :type)";
    $stmt = oci_parse($conn, $sql);

    if (!$stmt) {
        die("SQL parsing failed: " . oci_error($conn));
    }

    // Bind parameters
    oci_bind_by_name($stmt, ':colname', $record['colname']);
    oci_bind_by_name($stmt, ':description', $record['description']);
    oci_bind_by_name($stmt, ':type', $record['type']);

    // Execute the SQL statement
    if (oci_execute($stmt) === false) {
        die("SQL execution failed: " . oci_error($stmt));
    }
}

// Step 4: Close the Connection
oci_close($conn);

// Optionally, provide a response to indicate success or failure
echo "Records inserted successfully!";
?>
