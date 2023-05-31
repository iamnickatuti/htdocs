<?php
// URL of the JSON files
$url1 = 'https://reports.moko.co.ke/production/api/vconversion.php';
$url2 = 'https://reports.moko.co.ke/production/api/conversion.php';

// Fetch the JSON content from the URLs
$json1 = file_get_contents($url1);
$json2 = file_get_contents($url2);

if ($json1 === false || $json2 === false) {
    // Error occurred while fetching JSON data
    die("Failed to fetch JSON data.");
}

$json1Array = json_decode($json1, true);
$json2Array = json_decode($json2, true);

if ($json1Array === null || $json2Array === null) {
    // Error occurred while decoding JSON data
    die("Failed to decode JSON data.");
}

$result = [];

foreach ($json1Array as $json1Item) {
    $blockSKU = isset($json1Item["Block SKU"]) ? $json1Item["Block SKU"] : "";
    $partNumber = isset($json1Item["Part Number"]) ? $json1Item["Part Number"] : "";

    $groupKey = $blockSKU . "|" . $partNumber;

    if (isset($result[$groupKey])) {
        // Records with same Block SKU and Part Number exist, merge the data
        $result[$groupKey]["Cut SKU Quantity"] += intval($json1Item["Cut SKU Quantity"]);
        $result[$groupKey]["Average Cut SKU Weight"] += floatval($json1Item["Average Cut SKU Weight"]);
    } else {
        // Create a new record
        $result[$groupKey] = $json1Item;
    }
}

// Convert the associative array to a sequential array
$result = array_values($result);

foreach ($result as &$newItem) {
    $blockSKU = isset($newItem["Block SKU"]) ? $newItem["Block SKU"] : "";
    $cutSKUQuantity = isset($newItem["Cut SKU Quantity"]) ? intval($newItem["Cut SKU Quantity"]) : 0;
    $averageCutSKUWeight = isset($newItem["Average Cut SKU Weight"]) ? floatval($newItem["Average Cut SKU Weight"]) : 0.0;
    $financeKey = isset($newItem["Finance Key"]) ? $newItem["Finance Key"] : "";
    $bomCategory = isset($newItem["BOM Category"]) ? $newItem["BOM Category"] : "";
    $partNumber = isset($newItem["Part Number"]) ? $newItem["Part Number"] : "";

    $newItem = [
        "Finance Key" => $financeKey,
        "BOM Category" => $bomCategory,
        "Part Number" => $partNumber,
        "Block-RM" => $blockSKU,
        "Cut SKU Quantity" => $cutSKUQuantity,
        "Average Cut SKU Weight" => $averageCutSKUWeight
    ];

    foreach ($json2Array as $json2Item) {
        if (isset($json2Item["Block Type"]) && $json2Item["Block Type"] === $blockSKU) {
            $parentSKU = isset($json2Item["Parent SKU"]) ? $json2Item["Parent SKU"] : "";
            $distributionValue = isset($json2Item["Distribution"]) ? floatval($json2Item["Distribution"]) : 0.0;
            $multipliedValue = round($averageCutSKUWeight * $distributionValue, 4);
            $newItem[$parentSKU] = $multipliedValue;
        }
    }
}

$jsonResult = json_encode($result);

if ($jsonResult === false) {
    // Error occurred while encoding JSON data
    die("Failed to encode JSON data.");
}

header('Content-Type: application/json');
echo $jsonResult;
?>
