<?php
$json_data = file_get_contents('https://reports.moko.co.ke/production/functions/finalTest.php');
$data = json_decode($json_data, true);
$groupedData = array_reduce($data, function ($result, $item) {
    $cut_sku_part_description = $item['Cut SKU Part Description'];
    $category = $item['Cut SKU Category'];
    $financeKey = $item['Finance Key'];
    $partNumber = $item['Cut SKU Part Number'];
    $blockSKU = $item['Block SKU'];
    $cut_sku_qty = $item['Cut SKU Quantity'];
    $cut_sku_weight = $item['Average Cut SKU Weight'];
    $Cutting_Date = $item['Cutting Date'];
    if (!isset($result[$category])) {
        $result[$category] = array();
    }
    if (!isset($result[$category][$financeKey])) {
        $result[$category][$financeKey] = array();
    }
    if (!isset($result[$category][$financeKey][$partNumber])) {
        $result[$category][$financeKey][$partNumber] = array();
    }
    if (!isset($result[$category][$financeKey][$partNumber][$blockSKU])) {
        $result[$category][$financeKey][$partNumber][$blockSKU] = array();
    }
    $result[$category][$financeKey][$partNumber][$blockSKU][] = $item;

    return $result;
}, array());

$resultArray = array(); // Initialize the result array

$count = 1;

// Loop through the grouped data and add the items to the result array
foreach ($groupedData as $category => $financeKeys) {
    foreach ($financeKeys as $financeKey => $partNumbers) {
        foreach ($partNumbers as $partNumber => $blockSKUs) {
            foreach ($blockSKUs as $blockSKU => $items) {
                $cut_sku_qty = 0;
                $cut_sku_weight_total = 0; // Initialize total weight
                foreach ($items as $item) {
                    $cut_sku_qty += $item['Cut SKU Quantity'];
                    $cut_sku_weight_total += $item['Average Cut SKU Weight'] * $item['Cut SKU Quantity'];
                }
                $cut_sku_weight_avg = $cut_sku_qty != 0 ? $cut_sku_weight_total / $cut_sku_qty : 0; // Calculate average weight
                $cut_sku_part_description = $item['Cut SKU Part Description'];
                $Cutting_Date = $item['Cutting Date'];

                $resultItem = array(
                    'Cut SKU Category' => $category,
                    'Finance Key' => $financeKey,
                    'Cut SKU Part Number' => $partNumber,
                    'Block SKU' => $blockSKU,
                    'Cut SKU Quantity' => $cut_sku_qty,
                    'Average Cut SKU Weight' => $cut_sku_weight_avg,
                    'Cut SKU Weights' => $cut_sku_weight_avg * $cut_sku_qty,
                    'Cut SKU Part Description' => $cut_sku_part_description,
                    'Cutting Date' => $Cutting_Date

                );
                $pattern = '/(\d+\.?\d*)[xX\*](\d+\.?\d*)[xX\*](\d+\.?\d*)/'; // regular expression pattern to match dimensions and capture each dimension, including decimals
                preg_match($pattern, $cut_sku_part_description, $matches); // search for dimensions in the string and capture each dimension
                $length = isset($matches[1]) ? $matches[1] : ''; // extract the first captured dimension as length
                $width = isset($matches[2]) ? $matches[2] : ''; // extract the second captured dimension as width
                $height = isset($matches[3]) ? $matches[3] : ''; // extract the third captured dimension as height
                $resultItem['Dimensions'] = $length."x".$width."x".$height;
                $resultItem['Volume'] = ($length*$width*$height)/61020;
                $resultItem['TotalVolume'] = ($length*$width*$height*$cut_sku_qty)/61020;
                $resultItem['WeightToVolumeRatio'] = $cut_sku_weight_avg/(($length*$width*$height)/61020);
                $resultItem['WeightToTotalVolumeRatio'] = ($cut_sku_weight_avg * $cut_sku_qty)/(($length*$width*$height*$cut_sku_qty)/61020);

                $resultArray[] = $resultItem;
            }
        }
    }
}

// Convert the result array to JSON
$jsonResult = json_encode($resultArray);

// Output the JSON result
header('Content-Type: application/json');

echo $jsonResult;
?>
