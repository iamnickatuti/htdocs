<?php
// Read the JSON file into a string
$json_str = file_get_contents('https://reports.moko.co.ke/production/api/api_cutting.php');

// Decode the JSON string into an array
$data = json_decode($json_str, true);
// Group the data by Block_ID
$grouped_data = array();
foreach ($data as $row) {
$block_id = $row['Block ID'];
if (!isset($grouped_data[$block_id])) {
$grouped_data[$block_id] = array(
'Block ID' => $block_id,
'Block Type' => $row['Block Type'],
'Dry Block Weight' => $row['Dry Block Weight'],
'Total Actual Recycle Weight' => $row['Total Actual Recycle Weight'],
'Block SKU' => $row['Block SKU'],
'Total SKU Weight' => $row['Total SKU Weight'],
'Total Expected Recycle Weight' => $row['Total Expected Recycle Weight']
);
} else {
// Add the current row's Dry Block Weight to the running sum
$grouped_data[$block_id]['Dry Block Weight'] += $row['Dry Block Weight'];
}
}

// Calculate the sum of Dry Block Weight
$sum_dry_block_weight = 0;
foreach ($grouped_data as $row) {
$sum_dry_block_weight += $row['Dry Block Weight'];
}

echo 'Sum of Dry Block Weight: ' . $sum_dry_block_weight;