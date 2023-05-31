<?php
header('Content-Type: application/json');

$json_data = file_get_contents('https://reports.moko.co.ke/production/functions/finalTest.php');

$data = json_decode($json_data, true);

$groupedData = array_reduce($data, function ($result, $item) {
    $category = $item['Cut SKU Category'];
    $financeKey = $item['Finance Key'];
    $partNumber = $item['Cut SKU Part Number'];
    $blockSKU = $item['Block SKU'];
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

$count = 1;

// Loop through the grouped data and display the items that have common Category, Finance Key, Part Number, and Block SKU
foreach ($groupedData as $category => $financeKeys) {
    foreach ($financeKeys as $financeKey => $partNumbers) {
        foreach ($partNumbers as $partNumber => $blockSKUs) {
            foreach ($blockSKUs as $blockSKU => $items) {
                $cut_sku_qty = 0;
                $cut_sku_weight_total = 0;
                foreach ($items as $item) {
                    $cut_sku_qty += $item['Cut SKU Quantity'];
                    $cut_sku_weight_total += $item['Average Cut SKU Weight'] * $item['Cut SKU Quantity'];
                }
                $cut_sku_weight_avg = $cut_sku_qty != 0 ? $cut_sku_weight_total / $cut_sku_qty : 0;
                $cut_sku_part_description = $item['Cut SKU Part Description'];
                $data[] = array(
                    'category' => $category,
                    'financeKey' => $financeKey,
                    'partNumber' => $partNumber,
                    'blockSKU' => $blockSKU,
                    'cut_sku_qty' => $cut_sku_qty,
                    'cut_sku_weight_avg' => $cut_sku_weight_avg
                );
                $jsonArray = json_encode($data);

            }
        }
    }
}
echo $jsonArray;
?>