<?php

$url = 'https://reports.moko.co.ke/production/api/api_cutting_two.php';

// Fetch JSON data from the URL
$json_data = file_get_contents($url);

// Decode JSON data into a PHP array
$data = json_decode($json_data, true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
} else {
    // Default date range is last 7 days
    $start_date = date('Y-m-d', strtotime('-7 days'));
    $end_date = date('Y-m-d');
}

// Group data by Block SKU, count instances, and sum Dry Block Weight
$grouped_data = [];
foreach ($data as $row) {
$block_sku = $row['Block SKU'];
$cutting = $row['Cutting Date'];
$dry_block_weight = $row['Dry Block Weight'];
if ($cutting >= $start_date && $cutting <= $end_date) {
if (!isset($grouped_data[$block_sku])) {
$grouped_data[$block_sku] = [
'count' => 0,
'sum_dry_weight' => 0,
];
}
$grouped_data[$block_sku]['count']++;
$grouped_data[$block_sku]['sum_dry_weight'] += $dry_block_weight;
}
}

    foreach ($grouped_data as $block_sku => $values) {
    $count = $values['count'];
    $sum_dry_weight = $values['sum_dry_weight'];
    echo "<tr>";
        echo "<td>".$block_sku."</td>";
        echo "<td>".$count."</td>";
        echo "<td>".$sum_dry_weight."</td>";
        $adbw=$sum_dry_weight/$count;
        echo "<td>".number_format($adbw,4)."</td>";
        echo "<tr>";
        }
