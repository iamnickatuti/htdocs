<?php
include '../../cradle_config.php';
global $conn;
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include '../sql/monthlyFlow.php';

global $cageQuery;
global $queryYard;
global $sqlStocktake;

$result = $conn->query($sqlStocktake);

if ($result) {
    $groupedRows = array();
    $openingBalanceMap = array(); // Map to store the opening balance for each SKU and month


    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Map the SKU name to the parent SKU based on the provided criteria
        $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
        $partJapan = array('RM-FS-JM001', 'RM-FS-JP002','RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005','RM-FS-JP006' , 'RM-FS-JP007', 'RM-FS-JP008');
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



$resultCage = mysqli_query($conn, $cageQuery);
$data = array();
while ($row = mysqli_fetch_assoc($resultCage)) {
    $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP006', 'RM-FS-SP007', 'RM-FS-SP008','RM-FS-SP009');
    $partJapan = array('RM-FS-JM001', 'RM-FS-JP002','RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005','RM-FS-JP006' , 'RM-FS-JP007', 'RM-FS-JP008');
    $partChina = array('RM-FS-CH001', 'RM-FS-CH002', 'RM-FS-CH003', 'RM-FS-CH004', 'RM-FS-CH005', 'RM-FS-CH006', 'RM-FS-CH007');
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

    // If no match is found, add the item from $materialData with quantity 0
    if (!$matchFound) {
        $item1['Cages'] = 0;
        $mergedArray[] = $item1;
    }
}

// Convert the merged array to JSON
$mergedJson = json_encode($mergedArray);


// Execute the query to retrieve the data
    $resultYard = mysqli_query($conn, $queryYard);

// Create an empty array to store the results
    $data = array();
    $groupedData = array();

    while ($row = mysqli_fetch_assoc($resultYard)) {
        while ($row = mysqli_fetch_assoc($resultYard)) {
            $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
            $partJapan = array('RM-FS-JM001', 'RM-FS-JP002','RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005','RM-FS-JP006' , 'RM-FS-JP007', 'RM-FS-JP008');
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
            // Format the date
            $date = date('Y-m', strtotime($row['Timed']));
            $row['Timed'] = $date;
            $row['Part Name'] = $psku;

            // Generate a unique key based on the combination of PSKU and Date
            $key = $row['Part Name'] . '-' . $row['Timed'];

            // Check if the key already exists in the groupedData array
            if (isset($groupedData[$key])) {
                // If the key exists, append the row to the existing array under the key
                $groupedData[$key]['Yards'] += $row['Yards'];
            } else {
                // If the key does not exist, create a new array with the row under the key
                $groupedData[$key] = $row;
            }
        }

// Convert the groupedData array to a sequential array
        $groupedArray = array_values($groupedData);

// Convert the grouped array to JSON
        $jsonDataa = json_encode($groupedArray);
    }


$allData = json_decode($mergedJson, true);
$yardData = json_decode($jsonDataa, true);

$combinedData = [];

foreach ($allData as $allItem) {
    $partDescription = $allItem['Part Description'];
    $duration = $allItem['Duration'];
    $matched = false;

    foreach ($yardData as $yardItem) {
        $partName = $yardItem['Part Name'];
        $timed = $yardItem['Timed'];

        if ($partDescription === $partName && $duration === $timed) {
            $combinedItem = array_merge($allItem, $yardItem);
            $combinedData[] = $combinedItem;
            $matched = true;
        }
    }

    if (!$matched) {
        $combinedItem = $allItem;
        $combinedItem['Yards'] = 0;
        $combinedData[] = $combinedItem;
    }
}

// Convert the combined data to JSON
$combinedJson = json_encode($combinedData);



$resultConsumption = $conn->query($sqlConsumption);
// Fetch the result and store it in an array
$data = array();
// Fetch each row from the result set
$groupedData = array();

while ($row = $resultConsumption->fetch_assoc()) {

    $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
    $partJapan = array('RM-FS-JM001', 'RM-FS-JP002','RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005','RM-FS-JP006' , 'RM-FS-JP007', 'RM-FS-JP008');
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
    // Format the date
    $datee = date('Y-m', strtotime($row['Tarehe']));
    $row['Tarehe'] = $datee;
    $row['Part Name'] = $psku;
    $consumption = $row['Consumption'];

    // Generate a unique key based on the combination of PSKU and datee
    $key = $row['Part Name'] . '-' . $row['Tarehe'];

    // Check if the key already exists in the groupedData array
    if (isset($groupedData[$key])) {
        // If the key exists, update the consumption by adding the current row's consumption
        $groupedData[$key]['Consumption'] += $consumption;
    } else {
        // If the key does not exist, create a new item in the groupedData array
        $groupedData[$key] = $row;
        $groupedData[$key]['Consumption'] = $consumption;
    }
}
$jsonDataa = json_encode($groupedData);

$finalData = json_decode($combinedJson, true);
$consData = json_decode($jsonDataa, true);

$combinedArray = [];

foreach ($finalData as $finalItem) {
    $matched = false; // Flag to check if a match is found

    foreach ($consData as $consItem) {
        if ($finalItem['Part Description'] === $consItem['Part Name'] && $finalItem['Duration'] === $consItem['Tarehe']) {
            $combinedItem = array_merge($finalItem, $consItem);
            $combinedArray[] = $combinedItem;
            $matched = true;
            break; // Exit the inner loop since a match is found
        }
    }

    if (!$matched) {
        // No match found, assign consumption to 0
        $finalItem['Consumption'] = 0;
        $combinedArray[] = $finalItem;
    }
}


$combinedJsonn = json_encode($combinedArray);
echo $combinedJsonn;
