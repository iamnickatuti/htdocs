<?php
include '../cradle_config.php';
global $conn;
$query= "WITH Cutting_output AS (
    SELECT
    ROW_NUMBER() OVER () AS 'index',
    DATE_FORMAT(outputs.cutting_date, '%Y-%M-%d') AS 'Cutting_Date',
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
    WHERE Cutting_output.Cutting_Date LIKE '2023%'
    ORDER BY Key_ DESC, Cut_SKU_Weights ASC";
$result = mysqli_query($conn, $query);
$row_countt = 0;
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
    $row_countt++;
    $block_id = $row['Block_ID'];
    $cut_sku_weight = $row['Cut_SKU_Weights'];
    $value = $row['Cut_SKU_Weights'];

    // Check if this is the first instance of this block ID
    if (!isset($first_instances[$block_id])) {
        // If it is, store the Cut_SKU_Weights in the array for first instances
        $first_instances[$block_id] = $cut_sku_weight;
        // Store the current row number in the array for first row numbers
        $first_row_numbers[$block_id] = $row_countt;
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
while ($row = mysqli_fetch_assoc($result)) {
    $block_id = $row['Block_ID'];
    $row_count++;
    $value = $row['Cut SKUs Quantity'];

    echo '<tr>';
    echo '<td>' . $row['Cutting_Date'] . '</td>';
    echo '<td>' . $block_id . '</td>';
    echo '<td>' . $row['Block SKU'] . '</td>';
    echo '<td>' . $row['Block Category'] . '</td>';
    echo '<td>' . $row['Cut SKU Part Number'] . '</td>';
    echo '<td>' . $row['Cut SKU Part Description'] . '</td>';
    echo '<td>' . $row['Cut SKU Category'] . '</td>';
    echo '<td>' . $row['Cut SKUs Quantity'] . '</td>';
    echo '<td>' . $row['Cut_SKU_Weights'] . '</td>';
//    echo '<td>' . $first_instances[$block_id] . '</td>';
//    echo '<td>' . $last_instances[$block_id] . '</td>';
//    echo '<td>' . $counts[$block_id] . '</td>';
//    echo '<td>' . $row_count . '</td>';

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
    echo "<td>".number_format($subsequent+$iaw,2)."</td>";
    echo "<td>".number_format(($subsequent+$iaw)*($skuqty),2)."</td>";
    echo "<td>". $dryblock ."</td>";
    echo "<td>".number_format($max,2)."</td>";
    echo "<td>".number_format($recycleweight,2)."</td>";
    echo "<td>".number_format($dryblock-$max,2)."</td>";
    echo "<td>".number_format($recycleweight-($dryblock-$max),2)."</td>";

    echo "</tr>";
}

