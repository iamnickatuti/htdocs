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
        <th>Raw Materials</th>
        <th>Component Quantity</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tableData as $product): ?>
                <?php foreach ($product['Raw Materials'] as $rawMaterial): ?>
                  <tr>
                    <td><?php echo $rawMaterial['Raw Material']; ?></td>
                    <td><?php echo $rawMaterial['Component Quantity']; ?></td>
                  </tr>
                    <?php if (isset($rawMaterial['Sub Raw Materials'])): ?>
                        <?php foreach ($rawMaterial['Sub Raw Materials'] as $subRawMaterial): ?>
                    <tr>
                        <td><?php echo $subRawMaterial['Raw Material']; ?></td>
                        <td><?php echo $subRawMaterial['Component Quantity']; ?></td>
                    </tr>
                            <?php if (isset($subRawMaterial['Sub Raw Materials'])): ?>
                                <?php foreach ($subRawMaterial['Sub Raw Materials'] as $subSubRawMaterial): ?>
                            <tr>
                                <td><?php echo $subSubRawMaterial['Raw Material']; ?></td>
                                <td><?php echo $subSubRawMaterial['Component Quantity']; ?></td>
                            </tr>
                                    <?php if (isset($subSubRawMaterial['Sub Raw Materials'])): ?>
                                        <?php foreach ($subSubRawMaterial['Sub Raw Materials'] as $subSubSubRawMaterial): ?>
                                    <tr>
                                        <td><?php echo $subSubSubRawMaterial['Raw Material']; ?></td>
                                        <td><?php echo $subSubSubRawMaterial['Component Quantity']; ?></td>
                                    </tr>
                                    <?php if (isset($subSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                <?php foreach ($subSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubRawMaterial): ?>
                                            <tr>
                                                <td><?php echo $subSubSubSubRawMaterial['Raw Material']; ?></td>
                                                <td><?php echo $subSubSubSubRawMaterial['Component Quantity']; ?></td>
                                            </tr>
                                            <?php if (isset($subSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                        <?php foreach ($subSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubRawMaterial): ?>
                                                    <tr>
                                                        <td><?php echo $subSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                        <td><?php echo $subSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                    </tr>
                                                    <?php if (isset($subSubSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                                <?php foreach ($subSubSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubSubRawMaterial): ?>
                                                            <tr>
                                                                <td><?php echo $subSubSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                                <td><?php echo $subSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                            </tr>
                                                            <?php if (isset($subSubSubSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                                        <?php foreach ($subSubSubSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubSubSubRawMaterial): ?>
                                                                    <tr>
                                                                        <td><?php echo $subSubSubSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                                        <td><?php echo $subSubSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                                    </tr>
                                                                    <?php if (isset($subSubSubSubSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                                                <?php foreach ($subSubSubSubSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubSubSubSubRawMaterial): ?>
                                                                            <tr>
                                                                                <td><?php echo $subSubSubSubSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                                                <td><?php echo $subSubSubSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                                            </tr>                                                                                          <?php if (isset($subSubSubSubSubSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                                                        <?php foreach ($subSubSubSubSubSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubSubSubSubSubRawMaterial): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $subSubSubSubSubSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                                                        <td><?php echo $subSubSubSubSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                                                    </tr>
                                                                                    <?php if (isset($subSubSubSubSubSubSubSubSubRawMaterial['Sub Raw Materials'])): ?>
                                                                                                <?php foreach ($subSubSubSubSubSubSubSubSubRawMaterial['Sub Raw Materials'] as $subSubSubSubSubSubSubSubSubSubRawMaterial): ?>
                                                                                            <tr>
                                                                                                <td><?php echo $subSubSubSubSubSubSubSubSubSubRawMaterial['Raw Material']; ?></td>
                                                                                                <td><?php echo $subSubSubSubSubSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                                                            </tr>
                                                                                            <?php // Add more levels as needed... ?>
                                                                                                <?php endforeach; ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
