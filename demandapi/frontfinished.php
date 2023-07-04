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
<table id="data-table">
    <thead>
    <tr>
        <th>Raw Material</th>
        <th>Raw Material Description</th>
        <th>Component Quantity</th>
        <th>UOM</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    // Retrieve the JSON data
    var jsonData = <?php echo $outputData; ?>;

    // Function to recursively create table rows for each level of data
    function createTableRows(data, level, parentElement) {
        for (var i = 0; i < data.length; i++) {
            var row = document.createElement('tr');

            var rawMaterialCell = document.createElement('td');
            rawMaterialCell.textContent = data[i]['Raw Material'];
            row.appendChild(rawMaterialCell);

            var descriptionCell = document.createElement('td');
            descriptionCell.textContent = data[i]['RM Description'];
            row.appendChild(descriptionCell);

            var quantityCell = document.createElement('td');
            quantityCell.textContent = data[i]['Component Quantity'];
            row.appendChild(quantityCell);

            var uomCell = document.createElement('td');
            uomCell.textContent = data[i]['uom'];
            row.appendChild(uomCell);

            parentElement.appendChild(row);

            if (level < 10 && data[i]['Sub Raw Materials']) {
                createTableRows(data[i]['Sub Raw Materials'], level + 1, parentElement);
            }
        }
    }

    // Call the function to create table rows using the JSON data
    var tableBody = document.querySelector('#data-table tbody');
    createTableRows(jsonData, 1, tableBody);
</script>
</body>
</html>
