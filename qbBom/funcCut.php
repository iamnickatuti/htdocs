<?php
// Read the JSON file into a string
$json_str = file_get_contents('http://localhost/production/api/api_cutting.php');

// Decode the JSON string into an array
$data = json_decode($json_str, true);

// Get the start and end dates from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
} else {
    // Default date range is last 7 days
    $start_date = date('Y-m-d', strtotime('-7 days'));
    $end_date = date('Y-m-d');
}

// Group the data by Block_ID and filter by cutting date
$grouped_data = array();
foreach ($data as $row) {
    $block_id = $row['Block ID'];
    $cutting = $row['Cutting Date'];
    $dataa =  $row['Cut SKUs Dimensions'];
    $pattern = '/(\d+\.?\d*)[xX\*](\d+\.?\d*)[xX\*](\d+\.?\d*)/'; // regular expression pattern to match dimensions and capture each dimension, including decimals
    preg_match($pattern, $dataa, $matches); // search for dimensions in the string and capture each dimension
    $length = isset($matches[1]) ? $matches[1] : ''; // extract the first captured dimension as length
    $width = isset($matches[2]) ? $matches[2] : ''; // extract the second captured dimension as width
    $height = isset($matches[3]) ? $matches[3] : ''; // extract the third captured dimension as height
    $volume = $length * $width * $height;
    if ($cutting >= $start_date && $cutting <= $end_date) {
        if (!isset($grouped_data[$block_id])) {
            $grouped_data[$block_id] = array(
                'Cutting Date' => $cutting,
                'Block ID' => $block_id,
                'Block Category' => $row['Block Category'],
                'Cut SKU Volume' => $volume,
                'Dry Block Weight' => $row['Dry Block Weight'],
                'Total Actual Recycle Weight' => $row['Total Actual Recycle Weight'],
                'Block SKU' => $row['Block SKU'],
                'Total SKU Weight' => $row['Total SKU Weight'],
                'Total Expected Recycle Weight' => $row['Total Expected Recycle Weight']
            );
        } else {
            $grouped_data[$block_id]['Cut SKU Volume'] += $volume; // Add the volume to the existing cumulative sum
        }
    }
}

$sumsku = 0;
$filtered_data = array();

foreach ($data as $row) {
    $cutting = $row['Cutting Date'];
    $sku_weight = $row['Cut SKUs Qty'];
    if ($cutting >= $start_date && $cutting <= $end_date) {
        $sumsku += $sku_weight;
        $filtered_data[] = $row;
    }
}

$cutblocks = 0; // initialize the counter variable
foreach ($grouped_data as $row) {
    $cutblocks++; // increment the counter for each row
    echo '<tr>';
    echo '<td>' . $row['Block ID'] . '</td>';
    echo '<td>' . $row['Block Category'] . '</td>';
    echo '<td>' . $row['Dry Block Weight'] . '</td>';
    echo '<td>' . $row['Total SKU Weight'] . '</td>';
    echo '<td>' . $row['Total Actual Recycle Weight'] . '</td>';
    echo '<td>' . $row['Block SKU'] . '</td>';
    echo '<td>' . $row['Total Expected Recycle Weight'] . '</td>';
    echo '<td>' . $row['Cut SKU Volume'] . '</td>';

    echo '</tr>';
}


// Calculate the totals
$sumblocktype = 0;
$totalsku = 0;
$totalactual = 0;
$totalexpected = 0;
foreach ($grouped_data as $row) {
    if (is_numeric($row['Dry Block Weight'])) {
        $sumblocktype += $row['Dry Block Weight'];
    }
    if (is_numeric($row['Total SKU Weight'])) {
        $totalsku += $row['Total SKU Weight'];
    }
    if (is_numeric($row['Total Actual Recycle Weight'])) {
        $totalactual += $row['Total Actual Recycle Weight'];
    }
    if (is_numeric($row['Total Expected Recycle Weight'])) {
        $totalexpected += $row['Total Expected Recycle Weight'];
    }
}
?>
