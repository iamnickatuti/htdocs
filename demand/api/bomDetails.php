<?php
// Assuming you have established a database connection
include '../../cradle_config.php';
header('Content-Type: application/json');
// The SQL query
$query = "
SELECT
  bom_details.bom_id,
  boms.name AS 'BOM_Name',
  production_lines.name AS 'Production_Line',
  skus1.name AS 'Target_sku_Part_Number',
  skus1.description AS 'Target_sku_Part_Description',
  skus.sku_type_id,
  skus.name AS 'Component_part_number',
  skus.description AS 'Component_part_description',
  bom_details.quantity AS 'component_quantity',
  units.name AS 'Component_Unit_of_measure',
  bom_details.status,
  bom_distribution_entries.bom_distribution_id AS 'bom_distribution_id',
  bom_distribution_entries.share AS '%_bom_share'
FROM
  (
    (
      (
        (
          (
            (
              bom_details
              LEFT JOIN skus ON skus.id = bom_details.sku_id
            )
            LEFT JOIN boms ON boms.id = bom_details.bom_id
          )
          LEFT JOIN production_lines ON production_lines.id = boms.production_line_id
        )
        LEFT JOIN skus AS skus1 ON skus1.id = boms.sku_id
      )
      LEFT JOIN units ON units.id = skus.unit_id
    )
    LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
  )
  LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
GROUP BY
  bom_details.bom_id,
  boms.name,
  production_lines.name,
  skus1.name,
  skus1.description,
  skus.sku_type_id,
  skus.name,
  skus.description,
  bom_details.quantity,
  units.name,
  bom_details.status,
  bom_distribution_entries.bom_distribution_id,
  bom_distribution_entries.share
HAVING
  bom_distribution_id = (
    SELECT
      MAX(bom_distribution_entries.bom_distribution_id)
    FROM
      bom_distribution_entries
  )";

// Execute the query
$result = mysqli_query($conn, $query);

// Store the results in an associative array
$results = array();

// Loop through each row of the query result
while ($row = mysqli_fetch_assoc($result)) {
    // Create an associative array for each row
    $resultRow = array(
        'bom_id' => $row['bom_id'],
        'BOM_Name' => $row['BOM_Name'],
        'Production_Line' => $row['Production_Line'],
        'Target_sku_Part_Number' => $row['Target_sku_Part_Number'],
        'Target_sku_Part_Description' => $row['Target_sku_Part_Description'],
        'sku_type_id' => $row['sku_type_id'],
        'Component_part_number' => $row['Component_part_number'],
        'Component_part_description' => $row['Component_part_description'],
        'component_quantity' => $row['component_quantity'],
        'Component_Unit_of_measure' => $row['Component_Unit_of_measure'],
        'status' => $row['status'],
        'bom_distribution_id' => $row['bom_distribution_id'],
        '%_bom_share' => $row['%_bom_share']
    );

    // Add the row to the results array
    $results[] = $resultRow;
}

// Convert the results array to JSON
$jsonResult = json_encode($results);

// Output the JSON result
echo $jsonResult;

// Close the database connection
mysqli_close($conn);
?>
