<?php
include '../../cradle_config.php';

$query = "SELECT 
    block_components.id AS block_components,
    block_components.date AS date_block,
    block_components.block_id AS block_id,
    block_types.name AS 'block_type',
    blocks.dimension AS 'block_dimension',      
    blocks.name AS 'block_name',
    blocks.is_cut AS 'cut',  
    block_components.sku_id AS 'block_sku_id',
    skus.description AS 'component_part_description',
    skus.name AS 'Component_part_number',
    block_components.weight
FROM
    (((block_components
    LEFT JOIN blocks ON blocks.id = block_components.block_id)
    LEFT JOIN block_types ON block_types.id = blocks.block_type_id)
    LEFT JOIN skus ON skus.id = block_components.sku_id)
WHERE blocks.is_cut = 1";

function conversion()
{
    global $query, $conn;
    $result = mysqli_query($conn, $query);

    $data = array(); // Initialize the $data array
    while ($row = mysqli_fetch_assoc($result)) {
        $partSpain = array('RM-FS-SP001','RM-FS-SP002','RM-FS-SP003','RM-FS-SP004','RM-FS-SP005','RM-FS-SP007','RM-FS-SP008');
        $partJapan = array('RM-FS-JM001','RM-FS-JP002','RM-FS-JP004','RM-FS-JP005','RM-FS-JP007','RM-FS-JP008');
        $partChina = array('RM-FS-CH001','RM-FS-CH002','RM-FS-CH003','RM-FS-CH004','RM-FS-CH005');
        $partRecycle = array('RM-FM-FR001','RM-FM-FR004','RM-FM-FR005','RM-FM-FR006');
        $partTrial = array('RM-FS-TR001','MKE-SKU');
        $partBra = array('RM-FS-BR001');
        $partSweepings = array('RM-FS-SW001');
        $partMD1518 = array('RM-CH-MD007');
        $partMD1518H = array('RM-CH-MD008');

        if (in_array($row['Component_part_number'], $partSpain)) {
            $results = 'Raw Material:Foam Scrap:Normal - General/ Code G - SPAIN';
        }
        elseif (in_array($row['Component_part_number'], $partJapan)) {
            $results = 'Raw Material:Foam Scrap:Normal - Japan/ Code J';
        }
        elseif (in_array($row['Component_part_number'], $partRecycle)) {
            $results = 'Raw Material:Foam Scrap:Recycle Foam';
        }
        elseif (in_array($row['Component_part_number'], $partChina)) {
            $results = 'Raw Material:Foam Scrap:Normal - General/ Code G - CHINA';
        }
        elseif (in_array($row['Component_part_number'], $partTrial)) {
            $results = 'RM:Foam Scrap: Trial Foam';
        }
        elseif (in_array($row['Component_part_number'], $partBra)) {
            $results = 'Raw Material:Foam Scrap:Bra - Code B';
        }
        elseif (in_array($row['Component_part_number'], $partSweepings)) {
            $results = 'Raw Material:Foam Scrap:Sweepings';
        }
        elseif (in_array($row['Component_part_number'], $partMD1518)) {
            $results = 'Raw Material:Chemicals:MDI:MDI 1518';
        }
        elseif (in_array($row['Component_part_number'], $partMD1518H)) {
            $results = 'Raw Material:Chemicals:MDI:MDI 1518H';
        }
        elseif ($row['Component_part_number'] === 'RM-FM-FR007') {
            $results = 'Raw Material:Foam Scrap:Recon Mixed';
        }
        elseif ($row['Component_part_number'] === 'RM-FS-FL001') {
            $results = 'RM:Foam Scrap: Filter - Code F (GF)';
        }
        elseif ($row['Component_part_number'] === 'RM-FS-FL002') {
            $results = 'Raw Material:Foam Scrap:Filter - Code F (JF)';
        }
        elseif ($row['Component_part_number'] === 'RM-CH-MD009') {
            $results = 'Raw Material:Chemicals:MDI:MDI-Polyol';
        }
        elseif ($row['Component_part_number'] === 'RM-FS-CM051') {
            $results = 'Raw Material:Local Loose Foam';
        }

        $new_row = array(
            'Block Comp' => $row['block_type'],
            'Date Block' =>  $row['date_block'],
            'Block ID' => $row['block_id'],
            'Block Type' => $row['block_type'],
            'Block Name' => $row['block_name'],
            'Block SKU Id' => $row['block_sku_id'],
            'Component Number' => $row['component_part_description'],
            'Component Desc' => $row['component_part_description'],
            'Weight' => $row['weight'],
            'Psku' => $results
        );

        $data[] = $new_row;
    }
    $array = $data;
    $conversion = array(); // Initialize the $conversion array

    $count_array = array();
    $weight_array = array();
    // create arrays of unique Block Types and Pskus
    $blockTypes = array_unique(array_column($array, 'Block Type'));
    $pskus = array_unique(array_column($array, 'Psku'));

    foreach ($blockTypes as $blockType) {
        // calculate total weight for the block type
        $total_weight = 0;
        foreach ($array as $row) {
            if ($row['Block Type'] == $blockType) {
                if ($row['Weight'] === 0) {
                    $total_weight = 0;
                } else {
                    $total_weight += $row['Weight'];
                }
            }
        }

        foreach ($pskus as $psku) {
            $key = $blockType . ' - ' . $psku;

            $count_array[$key] = 0;
            $weight_array[$key] = 0;
            foreach ($array as $row) {
                if ($row['Block Type'] == $blockType && $row['Psku'] == $psku) {
                    $count_array[$key]++;
                    $weight_array[$key] += $row['Weight'];
                }
            }
            if ($count_array[$key] != 0) {
                $block_count = number_format($count_array[$key]);
                $percentageWeight = number_format(($weight_array[$key] / $total_weight), 6);
            }

            $new_roww = array(
                'Block Type' => $blockType,
                'Parent SKU' => $psku,
                'Count' => $block_count,
                'Distribution' => $percentageWeight,
                'Date Block' => $row['Date Block']
            );
            $conversion[] = $new_roww;
        }
    }

    $blockTypeWeights = array();

    // Calculate total weight for each block type
    foreach ($array as $row) {
        $blockType = $row['Block Type'];
        $weight = $row['Weight'];

        if (!isset($blockTypeWeights[$blockType])) {
            $blockTypeWeights[$blockType] = 0;
        }

        $blockTypeWeights[$blockType] += $weight;
    }

    $rows = array(); // Initialize an array to store the rows

    foreach ($conversion as $conversionRow) {
        $blockType = $conversionRow['Block Type'];
        $count = $conversionRow['Count'];
        $parentSKU = $conversionRow['Parent SKU'];

        // Calculate total weight for the block type
        $blockTypeWeight = 0;
        foreach ($array as $row) {
            if ($row['Block Type'] == $blockType) {
                $blockTypeWeight += $row['Weight'];
            }
        }

        $row = array(
            'Block Type' => $blockType,
            'Count' => $count,
            'Parent SKU' => $parentSKU,
            'SKU Weight' => $weight_array[$blockType . ' - ' . $parentSKU],
            'Block Type Weight' => $blockTypeWeight,
            'Distribution' => number_format($weight_array[$blockType . ' - ' . $parentSKU] / $blockTypeWeight, 8),
            'Date Block' => $conversionRow['Date Block'] // Add the 'Date Block' key
        );

        $rows[] = $row; // Add the row to the array
    }

// Convert the array to JSON
    $jsonArray = json_encode($rows);

// Output the JSON array
    header('Content-Type: application/json');
    echo $jsonArray;
}

conversion();
