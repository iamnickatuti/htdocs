<?php
header('Content-Type: text/html');

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
$tableData = json_decode($outputData, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>JSON Data in HTML Table</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Raw Materials</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tableData as $product): ?>
        <tr>
            <td><?php echo $product['Product']; ?></td>
            <td>
                <?php foreach ($product['Raw Materials'] as $rawMaterial): ?>
                    <?php echo $rawMaterial['Raw Material']; ?><br>
                    <?php if (isset($rawMaterial['Sub Raw Materials'])): ?>
                        <?php foreach ($rawMaterial['Sub Raw Materials'] as $subRawMaterial): ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $subRawMaterial['Raw Material']; ?><br>
                            <?php if (isset($subRawMaterial['Sub Raw Materials'])): ?>
                                <?php foreach ($subRawMaterial['Sub Raw Materials'] as $subSubRawMaterial): ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $subSubRawMaterial['Raw Material']; ?><br>
                                    <?php // Add more levels as needed... ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
