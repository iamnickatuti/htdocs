<?php
header('Content-Type: application/json');

// Fetch data from JSON endpoints
$json1Url = "https://reports.moko.co.ke/demandapi/products";
$json2Url = "https://reports.moko.co.ke/demandapi/rawmaterials";
$json1 = file_get_contents($json1Url);
$json2 = file_get_contents($json2Url);

// Convert JSON data to associative arrays
$data1 = json_decode($json1, true);
$data2 = json_decode($json2, true);

// Helper function to find sub raw materials based on Raw Material ID
function findSubRawMaterials($rawMaterialId, $data2, $level)
{
    $subRawMaterials = array();
    foreach ($data2 as $item) {
        if ($item['Raw Material'] === $rawMaterialId) {
            $subRawMaterial = array(
                'Raw Material' => $item['Sub Raw Material'],
                'RM Description' => $item['SRM Description'],
                'Component Quantity' => $item['Component Quantity'],
                'uom' => $item['uom'],
                '%_BOM_Share' => $item['%_BOM_Share']
            );
            if ($level < 10) {
                $subRawMaterial['Sub Raw Materials'] = findSubRawMaterials($item['Sub Raw Material'], $data2, $level + 1);
            }
            $subRawMaterials[] = $subRawMaterial;
        }
    }
    return $subRawMaterials;
}

// Process data from json1
$processedData = array();
foreach ($data1 as $productKey => $product) {
    $rawMaterials = $product['Raw Materials'];
    $processedRawMaterials = array();
    foreach ($rawMaterials as $rawMaterial) {
        $rawMaterialId = $rawMaterial['Raw Material'];
        if (substr($rawMaterialId, 0, 2) === "WP") {
            $subRawMaterials = findSubRawMaterials($rawMaterialId, $data2, 1); // Start at level 1
            $rawMaterial['Sub Raw Materials'] = $subRawMaterials;
        }
        $processedRawMaterials[] = $rawMaterial;
    }
    $product['Raw Materials'] = $processedRawMaterials;
    $processedData[$productKey] = $product;
}

// Convert the processed data to JSON
$outputData = json_encode($processedData);

// Output the JSON data
echo $outputData;
?>
