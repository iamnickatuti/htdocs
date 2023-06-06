<?php
include '../../cradle_config.php';
global $conn;



$sql = "
SELECT
  skus.name,
  stocktake_references.tag AS 'Duration',
  cycles.name AS 'Cycle Name',
  stocktakes.quantity AS 'Qty'
FROM
  (((((((((
    cradle.stocktakes
    LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id
  )
  LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id
)
LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id
)
LEFT JOIN locations ON locations.id = stocktakes.location_id
)
LEFT JOIN items ON items.id = stocktakes.item_id
)
LEFT JOIN skus ON skus.id = stocktakes.sku_id
)
LEFT JOIN categories ON categories.id = skus.category_id
)
LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
)
LEFT JOIN units ON units.id = skus.unit_id
)
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16', '24')
  AND stocktake_references.cycle_id = 3
ORDER BY
  stocktakes.date DESC";

$result = $conn->query($sql);

if ($result) {
    $groupedRows = array();
    $openingBalanceMap = array(); // Map to store the opening balance for each SKU and month


    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Map the SKU name to the parent SKU based on the provided criteria
        $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
        $partJapan = array('RM-FS-JM001', 'RM-FS-JP002', 'RM-FS-JP004', 'RM-FS-JP005', 'RM-FS-JP007', 'RM-FS-JP008');
        $partChina = array('RM-FS-CH001', 'RM-FS-CH002', 'RM-FS-CH003', 'RM-FS-CH004', 'RM-FS-CH005');
        $partRecycle = array('RM-FM-FR001', 'RM-FM-FR004', 'RM-FM-FR005', 'RM-FM-FR006');
        $partTrial = array('RM-FS-TR001', 'MKE-SKU');
        $partBra = array('RM-FS-BR001');
        $partSweepings = array('RM-FS-SW001');
        $partMD1518 = array('RM-CH-MD007');
        $partMD1518H = array('RM-CH-MD008');

        if (in_array($row['name'], $partSpain)) {
            $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - SPAIN';
        } elseif (in_array($row['name'], $partJapan)) {
            $psku = 'Raw Material:Foam Scrap:Normal - Japan/ Code J';
        } elseif (in_array($row['name'], $partRecycle)) {
            $psku = 'Raw Material:Foam Scrap:Recycle Foam';
        } elseif (in_array($row['name'], $partChina)) {
            $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - CHINA';
        } elseif (in_array($row['name'], $partTrial)) {
            $psku = 'RM:Foam Scrap: Trial Foam';
        } elseif (in_array($row['name'], $partBra)) {
            $psku = 'Raw Material:Foam Scrap:Bra - Code B';
        } elseif (in_array($row['name'], $partSweepings)) {
            $psku = 'Raw Material:Foam Scrap:Sweepings';
        } elseif (in_array($row['name'], $partMD1518)) {
            $psku = 'Raw Material:Chemicals:MDI:MDI 1518';
        } elseif (in_array($row['name'], $partMD1518H)) {
            $psku = 'Raw Material:Chemicals:MDI:MDI 1518H';
        } elseif ($row['name'] === 'RM-FM-FR007') {
            $psku = 'Raw Material:Foam Scrap:Recon Mixed';
        } elseif ($row['name'] === 'RM-FS-FL001') {
            $psku = 'RM:Foam Scrap: Filter - Code F (GF)';
        } elseif ($row['name'] === 'RM-FS-FL002') {
            $psku = 'Raw Material:Foam Scrap:Filter - Code F (JF)';
        } elseif ($row['name'] === 'RM-CH-MD009') {
            $psku = 'Raw Material:Chemicals:MDI:MDI-Polyol';
        } elseif ($row['name'] === 'RM-FS-CM051') {
            $psku = 'Raw Material:Local Loose Foam';
        }


        $string = $row['Duration'];
        $date = DateTime::createFromFormat('Y M \S\t\o\c\k\t\a\k\e', $string);
        $dateFormat = $date->format('Y-m');
        $dateString = $dateFormat;
        $date = new DateTime($dateString);
        $date->modify('-1 month');
        $resultDate = $date->format('Y-m');


        // Create a unique key for grouping based on the combination of Duration and parent_sku
        $groupKey = $row['Duration'] . '-' . $psku;
        $resultDateKey = $resultDate . '-' . $psku;
        $openingBalanceMap[$resultDateKey] = $row['Qty'];


        if (!isset($groupedRows[$groupKey])) {
            $groupedRows[$groupKey] = array(
                'Part Number' => $row['name'],
                'Part Description' => $psku,
                'Duration' => $dateFormat,
                'Closing Balance' => $row['Qty']
            );
        } else {
            $groupedRows[$groupKey]['Closing Balance'] += $row['Qty'];
        }

        // Store the current month's closing balance as the opening balance for the next month
        $openingBalanceMap[$groupKey] = $row['Qty'];
    }

    // Convert the grouped rows to a simple array
    $groupedArray = array_values($groupedRows);

    // Convert the result to JSON
    $jsonArray = json_encode($groupedArray);


// Decode the JSON data into an associative array
    $data = json_decode($jsonArray, true);

// Sort the data by duration in ascending order
    usort($data, function ($a, $b) {
        return strtotime($a['Duration']) - strtotime($b['Duration']);
    });

// Create a new array to store the processed data
    $result = [];
// Iterate over the data array
    // Iterate over the data array
    foreach ($data as $key => $item) {
        // Get the part description and duration
        $partDescription = $item['Part Description'];
        $duration = $item['Duration'];

        // Calculate the previous month
        $date = new DateTime($duration);
        $date->modify('-1 month');
        $previousMonth = $date->format('Y-m');

        // Search for the item with the previous month and the same part description
        $previousItem = null;
        foreach ($data as $prevItem) {
            if ($prevItem['Part Description'] === $partDescription && $prevItem['Duration'] === $previousMonth) {
                $previousItem = $prevItem;
                break;
            }
        }

        // Check if a previous item was found
        if ($previousItem !== null) {
            // Get the closing balance of the previous month
            $previousClosingBalance = $previousItem['Closing Balance'];

            // Calculate the opening balance as the previous month's closing balance
            $openingBalance = $previousClosingBalance;
        } else {
            // If there is no previous month, use 0 as the opening balance
            $openingBalance = 0; // Set the opening balance as 0 for the first month
        }

        // Update the OpeningBalance value for the current item
        $data[$key]['Opening Balance'] = $openingBalance;
    }

// Encode the modified data array back to JSON
    $material = json_encode($data);

// Output the result JSON

}

//cage receipts

$cageQuery = "SELECT 
skus.name as 'Part Name',
skus.description as 'Part Description',
cage_receipts.value AS 'Quantity',
cage_receipts.created_at AS 'Masaa'
FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)";


$resultCage = mysqli_query($conn, $cageQuery);
$data = array();
while ($row = mysqli_fetch_assoc($resultCage)) {
    $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
    $partJapan = array('RM-FS-JM001', 'RM-FS-JP002', 'RM-FS-JP004', 'RM-FS-JP005', 'RM-FS-JP007', 'RM-FS-JP008');
    $partChina = array('RM-FS-CH001', 'RM-FS-CH002', 'RM-FS-CH003', 'RM-FS-CH004', 'RM-FS-CH005');
    $partRecycle = array('RM-FM-FR001', 'RM-FM-FR004', 'RM-FM-FR005', 'RM-FM-FR006');
    $partTrial = array('RM-FS-TR001', 'MKE-SKU');
    $partBra = array('RM-FS-BR001');
    $partSweepings = array('RM-FS-SW001');
    $partMD1518 = array('RM-CH-MD007');
    $partMD1518H = array('RM-CH-MD008');

    if (in_array($row['Part Name'], $partSpain)) {
        $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - SPAIN';
    } elseif (in_array($row['Part Name'], $partJapan)) {
        $psku = 'Raw Material:Foam Scrap:Normal - Japan/ Code J';
    } elseif (in_array($row['Part Name'], $partRecycle)) {
        $psku = 'Raw Material:Foam Scrap:Recycle Foam';
    } elseif (in_array($row['Part Name'], $partChina)) {
        $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - CHINA';
    } elseif (in_array($row['Part Name'], $partTrial)) {
        $psku = 'RM:Foam Scrap: Trial Foam';
    } elseif (in_array($row['Part Name'], $partBra)) {
        $psku = 'Raw Material:Foam Scrap:Bra - Code B';
    } elseif (in_array($row['Part Name'], $partSweepings)) {
        $psku = 'Raw Material:Foam Scrap:Sweepings';
    } elseif (in_array($row['Part Name'], $partMD1518)) {
        $psku = 'Raw Material:Chemicals:MDI:MDI 1518';
    } elseif (in_array($row['Part Name'], $partMD1518H)) {
        $psku = 'Raw Material:Chemicals:MDI:MDI 1518H';
    } elseif ($row['Part Name'] === 'RM-FM-FR007') {
        $psku = 'Raw Material:Foam Scrap:Recon Mixed';
    } elseif ($row['Part Name'] === 'RM-FS-FL001') {
        $psku = 'RM:Foam Scrap: Filter - Code F (GF)';
    } elseif ($row['Part Name'] === 'RM-FS-FL002') {
        $psku = 'Raw Material:Foam Scrap:Filter - Code F (JF)';
    } elseif ($row['Part Name'] === 'RM-CH-MD009') {
        $psku = 'Raw Material:Chemicals:MDI:MDI-Polyol';
    } elseif ($row['Part Name'] === 'RM-FS-CM051') {
        $psku = 'Raw Material:Local Loose Foam';
    }

    $date = date('Y-m', strtotime($row['Masaa']));
    $row['Masaa'] = $date;
    $row['Part Name'] = $psku; // Update the "Part Name" column value
    $data[] = $row;
}

// Initialize the groupedData array
$groupedData = array();

// Iterate over each row in the data array
foreach ($data as $row) {
    $key = $row['Masaa'] . '-' . $row['Part Name'];

    // Check if the key already exists in the groupedData array
    if (isset($groupedData[$key])) {
        // If the key exists, merge the row with the existing groupedData item
        $groupedData[$key] = array_merge($groupedData[$key], $row);
    } else {
        // If the key does not exist, create a new item in the groupedData array
        $groupedData[$key] = $row;
    }
}

// Find the missing combinations of Masaa and Part Name
$allCombinations = array();
foreach ($data as $row) {
    $key = $row['Masaa'] . '-' . $row['Part Name'];
    $allCombinations[$key] = true;
}

$missingCombinations = array_diff_key($allCombinations, $groupedData);

// Assign quantity of 0 to missing combinations
foreach ($missingCombinations as $key => $value) {
    list($masaa, $partName) = explode('-', $key);
    $missingRow = array(
        'Masaa' => $masaa,
        'Part Name' => $partName,
        'Quantity' => 0
    );
    $groupedData[$key] = $missingRow;
}

// Convert the groupedData array to a sequential array
$groupedArray = array_values($groupedData);

// Convert the grouped array to JSON
$cageReceipts = json_encode($groupedArray);

$cageData = json_decode($cageReceipts, true);
$materialData = json_decode($material, true);

// Initialize the merged array
$mergedArray = [];

// Iterate over each element in $materialData array
foreach ($materialData as $item1) {
    // Flag to track if a match is found
    $matchFound = false;

    // Iterate over each element in $cageData array
    foreach ($cageData as $item2) {
        // Check if Part Description matches Part Name and Duration matches Masaa
        if ($item1['Part Description'] === $item2['Part Name'] && $item1['Duration'] === $item2['Masaa']) {
            // Merge the two items into a new array
            $mergedItem = array_merge($item1, $item2);
            // Add the merged item to the merged array
            $mergedArray[] = $mergedItem;
            // Set match found flag to true
            $matchFound = true;
        }
    }

    // If no match is found, add the item from $materialData as is
    if (!$matchFound) {
        $mergedArray[] = $item1;
    }
}

// Convert the merged array to JSON
$mergedJson = json_encode($mergedArray);
header('Content-Type: application/json');
// Output the merged JSON array
echo $mergedJson;
