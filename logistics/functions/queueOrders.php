<?php
$url = 'https://reports.moko.co.ke/logistics/api/orders.php';  // Replace with the actual URL of the JSON data
$jsonArray = file_get_contents($url);

// Convert the JSON data to an array of records
$orders = json_decode($jsonArray, true);

// Get the current month
$currentMonth = date('Y-m');

// Initialize an array to store the counts and amounts for each status and elapsed time interval
$statusData = array();

// Define the statuses to check
$statuses = array(0, 1, 2, 9, 5, 14, 6);
$statusLabel = ''; // Replace this with your own array or logic to map the status values to labels
// Define the elapsed time intervals
$timeIntervals = array(
    '<1day' => array('count' => 0, 'amount' => 0),
    '2days' => array('count' => 0, 'amount' => 0),
    '3days' => array('count' => 0, 'amount' => 0),
    '4days' => array('count' => 0, 'amount' => 0),
    '5days' => array('count' => 0, 'amount' => 0),
    'Above 5days' => array('count' => 0, 'amount' => 0)
);

// Loop through each status and initialize the counts and amounts for each interval
foreach ($statuses as $status) {
    $statusData[$status] = $timeIntervals;
}

// Loop through each order and calculate the elapsed time, count, and amount for each status and interval
foreach ($orders as $order) {
    $createdAt = strtotime($order['created_at']);
    $elapsedTime = time() - $createdAt;
    $elapsedDays = ceil($elapsedTime / (60 * 60 * 24));  // Calculate the elapsed days

    // Check the status and increment the count and amount for the appropriate interval
    if (in_array($order['status'], $statuses)) {
        if ($elapsedDays <= 1) {
            $statusData[$order['status']]['<1day']['count']++;
            $statusData[$order['status']]['<1day']['amount'] += floatval($order['total']);
        } elseif ($elapsedDays == 2) {
            $statusData[$order['status']]['2days']['count']++;
            $statusData[$order['status']]['2days']['amount'] += floatval($order['total']);
        } elseif ($elapsedDays == 3) {
            $statusData[$order['status']]['3days']['count']++;
            $statusData[$order['status']]['3days']['amount'] += floatval($order['total']);
        } elseif ($elapsedDays == 4) {
            $statusData[$order['status']]['4days']['count']++;
            $statusData[$order['status']]['4days']['amount'] += floatval($order['total']);
        } elseif ($elapsedDays == 5) {
            $statusData[$order['status']]['5days']['count']++;
            $statusData[$order['status']]['5days']['amount'] += floatval($order['total']);
        } else {
            $statusData[$order['status']]['Above 5days']['count']++;
            $statusData[$order['status']]['Above 5days']['amount'] += floatval($order['total']);
        }
    }
}


?>
