<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/mix.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/projection.php');
// Decode JSON strings into arrays
$array1 = json_decode($json1, true);
$array2 = json_decode($json2, true);
// Initialize an empty result array
$result = [];
// Iterate over each element in $array2
foreach ($array2 as $item2) {
    // Check if the "Sub Category" exists in $array1
    foreach ($array1 as $item1) {
        if ($item1['Sub-category'] === $item2['Sub Category']) {
            $multipliedItem = [
                "Parent Category" => $item2["Parent Category"],
                "Sub Category" => $item2["Sub Category"],
                "Part Number" => $item1["Part Number"],
                "Part Description" => $item1["Part Description"],
                "UOM" => $item2["UOM"], // Add the unit of measure from json2
            ];
            // Multiply the month values by the proportion
            foreach ($item2 as $key => $value) {
                if ($key !== "Parent Category" && $key !== "Sub Category" && $key !== "UOM") {
                    $multipliedItem[$key] = floatval($value) * floatval($item1['proportion']);
                }
            }
            // Append the multiplied item to the result array
            $result[] = $multipliedItem;
        }
    }
}
// Convert the result array to JSON
$jsonResult = json_encode($result, JSON_PRETTY_PRINT);

// Output the JSON result
echo $jsonResult;
?>
