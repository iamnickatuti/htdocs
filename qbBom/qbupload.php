<?php
include '../cradle_config.php';
global $conn;
echo '<form method="GET" action="">
    <label for="start">Start Date:</label><input type="date" id="start" name="start" required>
    <label for="end">End Date:</label><input type="date" id="end" name="end" required>
    <button type="submit">Filter</button>
    </form>';

$start = isset($_GET["start"]) ? date("Y-m-d", strtotime($_GET["start"])) : "";
$end = isset($_GET["end"]) ? date("Y-m-d", strtotime($_GET["end"])) : "";

if (empty($start) || empty($end)) {
    echo "Please choose a date range.";
}
else{
    $query= "WITH Cutting_output AS (
    SELECT
    ROW_NUMBER() OVER () AS 'index',
    DATE_FORMAT(outputs.cutting_date,'%Y-%m-%d') AS 'Cutting_Date',
    outputs.block_id AS 'Key_',
    blocks.name AS 'Block_ID',
    blocks.is_cut,
    blocks.dimension AS 'Block Dimension',
    block_types.name AS 'Block SKU',
    categories.name AS 'Block Category',
    CAST(REPLACE(blocks.weight, ',', '.') AS DECIMAL(10,2)) AS 'Block Weight (kgs)',
    CAST(REPLACE(dry_blocks.weight, ',', '.') AS DECIMAL(10,2)) AS 'Dry Block Weight (kgs)',
    skus.name AS 'Cut SKU Part Number',
    skus.description AS 'Cut SKU Part Description',
    categories1.name AS 'Cut SKU Category',
    units.name AS 'Cut SKU Unit of Measure',
    cradle.lines.name AS 'Cutting Line',
    outputs.quality AS 'Quality',
    outputs.quantity AS 'Cut SKUs Quantity',
    discount_descriptions.name AS 'Discount Descriptions',
    CAST(REPLACE(outputs.weight, ',', '.') AS DECIMAL(10,2)) AS 'Cut_SKU_Weights',
    CAST(REPLACE(recycled_cuttings.weight, ',', '.') AS DECIMAL(10,2)) AS 'Recycle Weight (kgs)',
    CONCAT(users.first_name,' ',users.last_name) AS 'User'
    FROM (((((((((((outputs
    LEFT JOIN blocks ON blocks.id = outputs.block_id)
    LEFT JOIN block_types ON block_types.id = blocks.block_type_id)
    LEFT JOIN categories ON categories.id = block_types.category_id)
    LEFT JOIN skus ON skus.id = outputs.sku_id)
    LEFT JOIN dry_blocks ON dry_blocks.block_id = outputs.block_id)
    LEFT JOIN categories AS categories1 ON categories1.id = skus.category_id)
    LEFT JOIN units ON units.id = skus.unit_id)
    LEFT JOIN cradle.lines ON cradle.lines.id = outputs.line_id)
    LEFT JOIN discount_descriptions ON discount_descriptions.id = outputs.discount_description_id)
    LEFT JOIN users ON users.id = outputs.user_id)
    LEFT JOIN recycled_cuttings ON recycled_cuttings.block_id = outputs.block_id))
    
    SELECT *
    FROM Cutting_output
    WHERE Cutting_output.Cutting_Date BETWEEN '$start' AND '$end'
    ORDER BY Key_ DESC, Cut_SKU_Weights ASC";
    $result = mysqli_query($conn, $query);
    $row_count = 0;
// Create two empty arrays to store the data for the first and last instances of each block ID
    $first_instances = array();
    $last_instances = array();
    $counts = array();
    $first_row_numbers = array();
    $current_sum = 0;
    $last_block_id = null;
    $block_sums = array();

// Loop through each row in the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $row_count++;
        $block_id = $row['Block_ID'];
        $cut_sku_weight = $row['Cut_SKU_Weights'];
        $value = $row['Cut_SKU_Weights'];

        // Check if this is the first instance of this block ID
        if (!isset($first_instances[$block_id])) {
            // If it is, store the Cut_SKU_Weights in the array for first instances
            $first_instances[$block_id] = $cut_sku_weight;
            // Store the current row number in the array for first row numbers
            $first_row_numbers[$block_id] = $row_count;
        }

        // Store the Cut_SKU_Weights in the array for last instances, overwriting any previous value
        $last_instances[$block_id] = $cut_sku_weight;

        // Count the occurrences of each block ID
        if (isset($counts[$block_id])) {
            $counts[$block_id]++;
        } else {
            $counts[$block_id] = 1;
        }

        // if this is a new block ID, store the total sum for the previous block ID
        if ($block_id !== $last_block_id && $last_block_id !== null) {
            $block_sums[$last_block_id] = $current_sum;
            $current_sum = 0;
        }

        // add the value to the sum
        $current_sum += $value;

        $last_block_id = $block_id;
    }

    mysqli_data_seek($result, 0);
// Loop through each row in the result set
    $prev_weight = 0;
    $row_count = 0;
    $difference = 0;

// store the total sum for the last block ID
    if ($last_block_id !== null) {
        $block_sums[$last_block_id] = $current_sum;
    }

// execute query again to get full results
    $result = $conn->query($query);
// loop through the results and display rows with total sum for each block ID
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $block_id = $row['Block_ID'];
        $row_count++;
        $value = $row['Cut SKUs Quantity'];

        $cutting_date = $row['Cutting_Date'];
        $block_sku = $row['Block SKU'];
        $block_category = $row['Block Category'];
        $cut_sku_part_number = $row['Cut SKU Part Number'];
        $cut_sku_part_description = $row['Cut SKU Part Description'];
        $cut_sku_category = $row['Cut SKU Category'];
        $cut_sku_quantity = $row['Cut SKUs Quantity'];
        $cut_sku_weights =  $row['Cut_SKU_Weights'];
        $counts_ =  $counts[$block_id];

        if ($first_row_numbers[$block_id] == $row_count){
            $foccurance = 'True';
        }
        else{
            $foccurance = 'False';
        }
//    echo '<td>' . $first_row_numbers[$block_id] . '</td>';

        if ($foccurance == 'True'){
            $iaw = number_format($first_instances[$block_id]/$row['Cut SKUs Quantity'],2);
        }
        else{
            $iaw = 0;
        }
//    echo '<td>' .$iaw. '</td>';

        $current_weight = $row['Cut_SKU_Weights'];
        if ($iaw !== 0) {
            $difference = 0;
        }
        else {
            $difference = $current_weight - $prev_weight;
        }
//    echo "<td>" .$difference . "</td>";

        $prev_weight = $current_weight;
        $skuqty = $row['Cut SKUs Quantity'];

        if ($skuqty != 0) {
            $subsequent = $difference / $skuqty;
        }
        if ($difference == 0){
            $subsequent = 0;
        }
        else{
            $subsequent;
        }

        $dryblock=$row['Dry Block Weight (kgs)'];
        $recycleweight = $row['Recycle Weight (kgs)'];

        $max = $last_instances[$block_id] ;
        $average_unit_cut_weight = number_format($subsequent+$iaw,2);
        $cut_sku_part_description = $row['Cut SKU Part Description'];
        if (strpos($cut_sku_part_description, 'WP:Buns') === 0) {
            $category = 'WIP-Mattresses:Buns';
        }
        elseif(strpos($cut_sku_part_description, 'WP:Cores') === 0) {
            $category = 'WIP-Mattresses:Cores';
        }
        elseif(strpos($cut_sku_part_description, 'Wp:pouffe') === 0) {
            $category = 'WIP-Mattresses:Pouffe';
        }
        elseif(strpos($cut_sku_part_description, 'Fp:comfort Foam') === 0) {
            $category = 'Cushion';
        }
        elseif(strpos($cut_sku_part_description, 'Fp:filter Foam') === 0) {
            $category = 'Cushion';
        }
        elseif(strpos($cut_sku_part_description, 'Fp:recon Foam') === 0) {
            $category = 'Cushion';
        }
        elseif(strpos($cut_sku_part_description, 'FP:Comfort Foam') === 0) {
            $category = 'Cushion';
        }
        elseif(strpos($cut_sku_part_description, 'RM:Sofa Materials') === 0) {
            $category = 'WIP-Sofa';
        }


        $finance_key = $category . ":" . $cut_sku_part_number;
        $datasource[] = array(
            'Cutting Date' => $cutting_date,
            'Cut SKU Part Number' => $cut_sku_part_number,
            'Cut SKU Quantity' => $cut_sku_quantity,
            'Block Category' => $block_category,
            'Block SKU' => $block_sku,
            'Cut SKU Part Description' => $cut_sku_part_description,
            'Cut SKU Category' => $category,
            'Cut SKU Weights' => $cut_sku_weights,
            'Finance Key' => $finance_key,
            'Average Cut SKU Weight'=> $average_unit_cut_weight
        );
    }

    $source = json_encode($datasource);

    $data = json_decode($source, true);
    $groupedData = array_reduce($data, function ($result, $item) {
        $cut_sku_part_description = $item['Cut SKU Part Description'];
        $category = $item['Cut SKU Category'];
        $financeKey = $item['Finance Key'];
        $partNumber = $item['Cut SKU Part Number'];
        $blockSKU = $item['Block SKU'];
        $cut_sku_qty = $item['Cut SKU Quantity'];
        $cut_sku_weight = $item['Average Cut SKU Weight'];
        $Cutting_Date = $item['Cutting Date'];
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

    $resultArray = array(); // Initialize the result array

    $count = 1;

// Loop through the grouped data and add the items to the result array
    foreach ($groupedData as $category => $financeKeys) {
        foreach ($financeKeys as $financeKey => $partNumbers) {
            foreach ($partNumbers as $partNumber => $blockSKUs) {
                foreach ($blockSKUs as $blockSKU => $items) {
                    $cut_sku_qty = 0;
                    $cut_sku_weight_total = 0; // Initialize total weight
                    foreach ($items as $item) {
                        $cut_sku_qty += $item['Cut SKU Quantity'];
                        $cut_sku_weight_total += $item['Average Cut SKU Weight'] * $item['Cut SKU Quantity'];
                    }
                    $cut_sku_weight_avg = $cut_sku_qty != 0 ? $cut_sku_weight_total / $cut_sku_qty : 0; // Calculate average weight
                    $cut_sku_part_description = $item['Cut SKU Part Description'];
                    $Cutting_Date = $item['Cutting Date'];

                    $resultItem = array(
                        'Cut SKU Category' => $category,
                        'Finance Key' => $financeKey,
                        'Cut SKU Part Number' => $partNumber,
                        'Block SKU' => $blockSKU,
                        'Cut SKU Quantity' => $cut_sku_qty,
                        'Average Cut SKU Weight' => $cut_sku_weight_avg,
                        'Cut SKU Weights' => $cut_sku_weight_avg * $cut_sku_qty,
                        'Cut SKU Part Description' => $cut_sku_part_description,
                        'Cutting Date' => $Cutting_Date

                    );
                    $pattern = '/(\d+\.?\d*)[xX\*](\d+\.?\d*)[xX\*](\d+\.?\d*)/'; // regular expression pattern to match dimensions and capture each dimension, including decimals
                    preg_match($pattern, $cut_sku_part_description, $matches); // search for dimensions in the string and capture each dimension
                    $length = isset($matches[1]) ? $matches[1] : ''; // extract the first captured dimension as length
                    $width = isset($matches[2]) ? $matches[2] : ''; // extract the second captured dimension as width
                    $height = isset($matches[3]) ? $matches[3] : ''; // extract the third captured dimension as height
                    $resultItem['Dimensions'] = $length."x".$width."x".$height;
                    $resultItem['Volume'] = ($length*$width*$height)/61023.7;
                    $resultItem['TotalVolume'] = ($length*$width*$height)/61023.7;
                    $resultItem['WeightToVolumeRatio'] = $cut_sku_weight_avg/(($length*$width*$height)/61023.7);
                    $resultItem['WeightToTotalVolumeRatio'] = ($cut_sku_weight_avg * $cut_sku_qty)/(($length*$width*$height*$cut_sku_qty)/61023.7);
                    $resultArray[] = $resultItem;
                }
            }
        }
    }
// Convert the result array to JSON
    $json_data = json_encode($resultArray);
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
    $resultqb = json_encode($jsonArray);


    $url2 = "https://reports.moko.co.ke/api/conversion.php";
    $json2 = file_get_contents($url2);


    $json1Array = json_decode($resultqb, true);
    $json2Array = json_decode($json2, true);

    if ($json1Array === null || $json2Array === null) {
        // Error occurred while decoding JSON data
        die("Failed to decode JSON data.");
    }

    $results = [];

    foreach ($json1Array as $json1Item) {
        $blockSKU = isset($json1Item["Block SKU"]) ? $json1Item["Block SKU"] : "";
        $partNumber = isset($json1Item["Part Number"]) ? $json1Item["Part Number"] : "";

        $groupKey = $partNumber;

        if (isset($results[$groupKey])) {
            // Records with same Part Number exist, merge the data
            $results[$groupKey]["Cut SKU Quantity"] += intval($json1Item["Cut SKU Quantity"]);
            $results[$groupKey]["Average Cut SKU Weight"] += floatval($json1Item["Average Cut SKU Weight"]);
        } else {
            // Create a new record
            $results[$groupKey] = $json1Item;
        }
    }

// Convert the associative array to a sequential array
    $result = array_values($results);

    foreach ($result as &$newItem) {
        $blockSKU = isset($newItem["Block SKU"]) ? $newItem["Block SKU"] : "";
        $cutSKUQuantity = isset($newItem["Cut SKU Quantity"]) ? intval($newItem["Cut SKU Quantity"]) : 0;
        $averageCutSKUWeight = isset($newItem["Average Cut SKU Weight"]) ? floatval($newItem["Average Cut SKU Weight"]) : 0.0;
        $financeKey = isset($newItem["Finance Key"]) ? $newItem["Finance Key"] : "";
        $cutting_date = isset($newItem["Cutting Date"]) ? $newItem["Cutting Date"] : "";
        $bomCategory = isset($newItem["BOM Category"]) ? $newItem["BOM Category"] : "";
        $partNumber = isset($newItem["Part Number"]) ? $newItem["Part Number"] : "";
        $volume = isset($newItem["volume"]) ? floatval($newItem["volume"]) : 0.0; // Use "Volume" as the key

        $newItem = [
            "Finance Key" => $financeKey,
            "BOM Category" => $bomCategory,
            "Part Number" => $partNumber,
            "Block-RM" => $blockSKU,
            "Cut SKU Quantity" => $cutSKUQuantity,
            "Average Cut SKU Weight" => $averageCutSKUWeight,
            "Cutting Date" => $cutting_date,
            "Volume" => number_format($volume, 4) // Add the "Volume" field to the output
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

    $data = json_decode($jsonResult, true);

    $keys = array_keys($data[0]);
    $startIndex = 8;


    echo '<table id="myTable" class="table activate-select dt-responsive nowrap" style="font-size: 11px;">
                                                 <thead>
                                                   <tr>
                                                       <th>Cushion</th>
                                                       <th>Part Number</th>
                                                       <th>Raw Material</th>
                                                       <th>Qty</th>     
                                                       <th>Quantity Cut</th>    
                                                       <th>Cumulative Volume</th>
                                                       <th>Total Consumption</th>
                                                   </tr>
                                                 </thead>
                                                 <tbody>';

    foreach ($data as $record) {
        $partNumber = isset($record["Part Number"]) ? $record["Part Number"] : "";
        $cutSKUQuantity = isset($record["Cut SKU Quantity"]) ? $record["Cut SKU Quantity"] : "";
        $TotalVolumes = isset($record["Volume"]) ? $record["Volume"] : 0;
        $Category = isset($record["BOM Category"]) ? $record["BOM Category"] : 0;

        for ($i = $startIndex; $i < count($keys); $i++) {
            $key = $keys[$i];
            $value = $record[$key];

            echo '<tr>
                                                            <td>' . $Category. '</td>
                                                            <td>' . $partNumber . '</td>
                                                            <td>' . $key . '</td>
                                                            <td>' . $value/$cutSKUQuantity . '</td>
                                                            <td>' . $cutSKUQuantity . '</td>
                                                            <td>' . number_format($TotalVolumes*$cutSKUQuantity, 4) . '</td>
                                                            <td>' . $value. '</td>
                                                       </tr>';
        }
    }

    echo '</tbody>
      </table>';

}

