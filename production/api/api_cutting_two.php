<?php
include '../../cradle_config.php';
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
    CAST(
      REPLACE(blocks.weight, ',', '.') AS DECIMAL(10, 2)
    ) AS 'Block Weight (kgs)',
    CAST(
      REPLACE(dry_blocks.weight, ',', '.') AS DECIMAL(10, 2)
    ) AS 'Dry Block Weight (kgs)',
    skus.name AS 'Cut SKU Part Number',
    skus.description AS 'Cut SKU Part Description',
    categories1.name AS 'Cut SKU Category',
    units.name AS 'Cut SKU Unit of Measure',
    cradle.lines.name AS 'Cutting Line',
    outputs.quality AS 'Quality',
    outputs.quantity AS 'Cut SKUs Quantity',
    discount_descriptions.name AS 'Discount Descriptions',
    CAST(
      REPLACE(outputs.weight, ',', '.') AS DECIMAL(10, 2)
    ) AS 'Cut_SKU_Weights',
    CAST(
      REPLACE(recycled_cuttings.weight, ',', '.') AS DECIMAL(10, 2)
    ) AS 'Recycle Weight (kgs)',
    CONCAT(users.first_name, ' ', users.last_name) AS 'User'
  FROM
    (
      (
        (
          (
            (
              (
                (
                  (
                    (
                      (
                        (
                          outputs
                          LEFT JOIN blocks ON blocks.id = outputs.block_id
                        )
                        LEFT JOIN block_types ON block_types.id = blocks.block_type_id
                      )
                      LEFT JOIN categories ON categories.id = block_types.category_id
                    )
                    LEFT JOIN skus ON skus.id = outputs.sku_id
                  )
                  LEFT JOIN dry_blocks ON dry_blocks.block_id = outputs.block_id
                )
                LEFT JOIN categories AS categories1 ON categories1.id = skus.category_id
              )
              LEFT JOIN units ON units.id = skus.unit_id
            )
            LEFT JOIN cradle.lines ON cradle.lines.id = outputs.line_id
          )
          LEFT JOIN discount_descriptions ON discount_descriptions.id = outputs.discount_description_id
        )
        LEFT JOIN users ON users.id = outputs.user_id
      )
      LEFT JOIN recycled_cuttings ON recycled_cuttings.block_id = outputs.block_id
    )
)
SELECT
  *
FROM
  Cutting_output
WHERE
  Cutting_output.Cutting_Date LIKE '2023%'
ORDER BY
  Key_ DESC,
  Cut_SKU_Weights ASC";
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

$row_count = 0; // initialize row count
$current_sum = 0; // initialize sum

while ($row = mysqli_fetch_assoc($result)) {
    $row_count++; // increment row count
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

    // Store the Cut_SKU_Weights in the array for last instances, overwriting any previous
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
        $current_sum = 0; // reset sum
    }

    // add the value to the sum
    $current_sum += $value;

    $last_block_id = $block_id;
}

// store the sum of the last block ID
if ($last_block_id !== null) {
    $block_sums[$last_block_id] = $current_sum;
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
    $value = $row['Cut SKUs Quantity'];
    $row_count++;

    $cutting_date = $row['Cutting_Date'];
    $block_id = $row['Block_ID'];
    $block_sku = $row['Block SKU'];
    $block_category = $row['Block Category'];
    $sku_part_number = $row['Cut SKU Part Number'];
    $sku_part_desc = $row['Cut SKU Part Description'];
    $cut_sku_cat = $row['Cut SKU Category'];
    $cut_sku_qty = $row['Cut SKUs Quantity'];
    $cut_sku_weights = $row['Cut_SKU_Weights'];

    if ($first_row_numbers[$block_id] == $row_count) {
        $foccurance = 'True';
    } else {
        $foccurance = 'False';
    }
//    echo '<td>' . $first_row_numbers[$block_id] . '</td>';

    if ($foccurance == 'True') {
        $iaw = number_format($first_instances[$block_id] / $row['Cut SKUs Quantity'], 2);
    } else {
        $iaw = 0;
    }
//    echo '<td>' .$iaw. '</td>';

    $current_weight = $row['Cut_SKU_Weights'];
    if ($iaw !== 0) {
        $difference = 0;
    } else {
        $difference = $current_weight - $prev_weight;
    }
//    echo "<td>" .$difference . "</td>";

    $prev_weight = $current_weight;
    $skuqty = $row['Cut SKUs Quantity'];

    if ($skuqty != 0) {
        $subsequent = $difference / $skuqty;
    }
    if ($difference == 0) {
        $subsequent = 0;
    } else {
        $subsequent;
    }


    $recycle_weight = $row['Recycle Weight (kgs)'];
    $max = $last_instances[$block_id];
    $average_sku_weights = number_format($subsequent + $iaw, 2) ;
    $cumulative_sku_pb = number_format(($subsequent + $iaw) * ($skuqty), 2);
    $dry_block_weight = $row['Dry Block Weight (kgs)'];
    $sku_cut_weight = number_format($max, 2);
    $recorded_recycle_weight = number_format($recycle_weight, 2);
    $expected_recycle_weight = number_format($dry_block_weight - $max, 2);
    $recycle_variance = number_format($recycle_weight - ($dry_block_weight - $max), 2);


// Create an array of data
    $data = array(
        'Cutting Date' => $cutting_date,
        'Block ID' => $block_id,
        'Block SKU' => $block_sku,
        'Block Category' => $block_category,
        'SKU Part Number' => $sku_part_number,
        'SKU Part Description' => $sku_part_desc,
        'Cut SKU Category' => $cut_sku_cat,
        'Cut SKU Quantity' => $cut_sku_qty,
        'Cut SKU Weight' => $cut_sku_weights,
        'Recycle Weight' => $recycle_weight,
        'Max' => $max,
        'Average SKU Weight' => $average_sku_weights,
        'Cumulative Weight Per Block' => $cumulative_sku_pb,
        'Dry Block Weight' => $dry_block_weight,
        'SKU Cut Weight' => $sku_cut_weight,
        'Recorded Recycle Weight' => $recorded_recycle_weight,
        'Expected Recycle Weight' => $expected_recycle_weight,
        'Recycle Variance' => $recycle_variance
    );

// Convert the array to a JSON string
    $json[] = $data;

}
header('Content-Type: application/json');
echo json_encode($json);



