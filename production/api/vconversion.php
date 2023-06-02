<?php
header('Content-Type: application/json');
$json_data = file_get_contents('https://reports.moko.co.ke/production/api/densities.php');
$data = json_decode($json_data, true);
$groupedData = array_reduce($data, function ($result, $item) {
//    $cut_sku_part_description = $item['Cut SKU Part Description'];
    $cutting_date = $item['Cutting Date'];
    $category = $item['Cut SKU Category'];
    $financeKey = $item['Finance Key'];
    $partNumber = $item['Cut SKU Part Number'];
    $blockSKU = $item['Block SKU'];
    $cut_sku_qty = $item['Cut SKU Quantity'];
    $TotalVolume = $item['TotalVolume'];

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

$jsonArray = array();

// Loop through the grouped data and build the JSON array
foreach ($groupedData as $category => $financeKeys) {
    foreach ($financeKeys as $financeKey => $partNumbers) {
        foreach ($partNumbers as $partNumber => $blockSKUs) {
            foreach ($blockSKUs as $blockSKU => $items) {
                $TotalVolume = 0;
                $cut_sku_qty = 0;
                $cut_sku_weight_total = 0;
                foreach ($items as $item) {
                    $cut_sku_qty += $item['Cut SKU Quantity'];
                    $cut_sku_weight_total += $item['Average Cut SKU Weight'] * $item['Cut SKU Quantity'];
                    $TotalVolume += $item['TotalVolume'];
                }
                $cut_sku_weight_avg = $cut_sku_qty != 0 ? $cut_sku_weight_total / $cut_sku_qty : 0;

                $jsonArray[] = array(
                    'Cutting Date' => $item['Cutting Date'],
                    'BOM Category' => $category,
                    'Finance Key' => $financeKey,
                    'Part Number' => $partNumber,
                    'Block SKU' => $blockSKU,
                    'Cut SKU Quantity' => $cut_sku_qty,
                    'Cut SKU Weight Average' => $cut_sku_weight_avg,
                    'Average Cut SKU Weight' => $cut_sku_weight_avg * $cut_sku_qty,
                    'volume' => number_format($TotalVolume,4)
                );
            }
        }
    }
}

// Output the JSON array
echo json_encode($jsonArray);
?>
