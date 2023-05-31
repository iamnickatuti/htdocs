<?php
include '../../cradle_config.php';
// SQL query to fetch the data
include '../sql/sqlOrderLogs.php';
global $conn,$sql;
// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}

// Prepare the data array
$data = array();

// Fetch rows from the result set
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Set the response headers
header('Content-Type: application/json');

// Convert data to JSON and output
echo json_encode($data);

// Close the database connection
mysqli_close($conn);
?>
