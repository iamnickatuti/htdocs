<?php
include '../../cradle_config.php';
// SQL query
include '../sql/sqlOrders.php';
global $sql,$conn;
// Execute the query
$result = $conn->query($sql);

// Check if any rows are returned
if ($result->num_rows > 0) {
    // Create an empty array to store the results
    $data = array();

    // Fetch each row and add it to the array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Convert the array to JSON
    $jsonArray = json_encode($data);

    // Output the JSON array
    header('Content-Type: application/json');
    echo $jsonArray;
} else {
    echo "No results found.";
}

// Close the connection
$conn->close();
?>
