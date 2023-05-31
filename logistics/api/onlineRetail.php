<?php

include '../../cradle_config.php';
include '../sql/sqlonlineretail.php';

global $conn,$query;

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} else {

    echo "No results found.";
}
?>
