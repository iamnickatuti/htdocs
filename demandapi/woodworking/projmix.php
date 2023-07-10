<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$json1 = file_get_contents('https://reports.moko.co.ke/demandapi/woodworking/categorymix.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demandapi/woodworking/projection.php');

// Decode JSON strings into arrays
$array1 = json_decode($json1, true);
$array2 = json_decode($json2, true);
// Initialize an empty result array
$result = [];
// Iterate over each element in $array2
foreach ($array2 as $item2) {
    // Check if the "Sub Category" exists in $array1
    $matchFound = false; // Flag to indicate if a match is found
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
                    $multipliedItem[$key] = ceil($value * $item1['proportion']);
                    // Assign "N/A" to null fields
                    if ($multipliedItem[$key] === null) {
                        $multipliedItem[$key] = "N/A";
                    }
                }
            }
            // Append the multiplied item to the result array
            $result[] = $multipliedItem;
            $matchFound = true;
        }
    }
    // If no match is found, append the item2 array with additional fields as is
    if (!$matchFound) {
        $item2WithNA = [
            "Parent Category" => $item2["Parent Category"],
            "Sub Category" => $item2["Sub Category"],
            "Part Number" => $item2["Sub Category"],
            "Part Description" => $item2["Sub Category"],
            "UOM" => $item2["UOM"],
        ];
        foreach ($item2 as $key => $value) {
            if ($key !== "Parent Category" && $key !== "Sub Category" && $key !== "UOM") {
                if ($value === null) {
                    $item2WithNA[$key] = "N/A";
                } else {
                    $item2WithNA[$key] = $value;
                }
            }
        }
        $result[] = $item2WithNA;
    }
}
// Convert the result array to JSON
$jsonResult = json_encode($result, JSON_PRETTY_PRINT);

// Output the JSON result
echo $jsonResult;
?>
